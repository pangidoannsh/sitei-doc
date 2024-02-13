<?php

namespace App\Http\Controllers;
use \PDF;


use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\StatusKP;
use App\Models\Mahasiswa;
use App\Models\Konsentrasi;
use App\Models\PermohonanKP;
use Illuminate\Http\Request;
use App\Models\PendaftaranKP;
use App\Models\PenjadwalanKP;
use App\Models\KapasitasBimbingan;
use App\Models\PendaftaranSkripsi;
use App\Models\PenilaianKPPenguji;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PenilaianKPPembimbing;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PendaftaranKPController extends Controller
{
    
    public function index()   
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim)->latest('created_at')->first();
        $pendaftaran_kp = PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first();
        $pendaftaran_skripsi = PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first();

    return view('pendaftaran.index', [
        'mahasiswa' => $mahasiswa,
        'pendaftaran_kp' => $pendaftaran_kp,
        'pendaftaran_skripsi' => $pendaftaran_skripsi,
    ]);
    }

    public function indexkp()   
    {
        //  $pendaftaran_kp = PendaftaranKP::all()->first();

        return view('pendaftaran.kerja-praktek.index', [
            'dosens' => Dosen::all(), 
            'prodi' => Prodi::all(),
            'konsentrasi' => Konsentrasi::all(),
            'mahasiswa' => Mahasiswa::where('nim', Auth::user()->nim)->get(),
            'pendaftaran_kp' => PendaftaranKP::all()->sortBy('update_at'),
       
        ]);
    }

    public function indexusulankp()
    {
        $pendaftaran_kp = PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first();
        $kp = PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->get();
        

        return view('pendaftaran.kerja-praktek.usulan-kp.index', [
            'pendaftaran_kp' => $pendaftaran_kp,
            'kps' => $kp,
        ]);
    }

    public function createusulankp()
    {
        return view('pendaftaran.kerja-praktek.usulan-kp.create', [
            'dosens' => Dosen::all(), 
            'pendaftaran_kp' => PendaftaranKP::all()->sortBy('created_at'),
            'kp' => PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first(),
            
        ]);
    }    

    public function storeusulankp(Request $request)
    {
        
        $request->validate([                                           
            'krs_berjalan' => 'required|mimes:pdf|max:200',
            'transkip_nilai' => 'required|mimes:pdf|max:200',
            'dosen_pembimbing_nip' => 'required',
            'nama_perusahaan' => 'required',
            'alamat_perusahaan' => 'required',
            'bidang_usaha' => 'required',
            'tanggal_rencana' => 'required',
                         
        ]);
       
  
        $dosen = Dosen::where('nip', $request->dosen_pembimbing_nip)->first();

        $kapasitasBimbingan = KapasitasBimbingan::value('kapasitas_kp');
        
        $jumlahBimbinganSaatIni = $dosen->pendaftaran_kp()
            ->where('status_kp', '!=', 'USULAN KP DITOLAK')
            ->where('status_kp', '!=', 'USULKAN KP ULANG')
            ->where('keterangan', '!=', 'Nilai KP Telah Keluar')
            ->count();

        if ($jumlahBimbinganSaatIni >= $kapasitasBimbingan) {
            Alert::warning('Pembimbing Penuh', 'Silahkan Usulkan Pembimbing Lain!')
                    ->showConfirmButton('Ok', '#dc3545')
                    ->footer('<a class="btn btn-info p-2 px-3" href="/kuota-bimbingan/kp">Cek Kuota Pembimbing</a>');

            return  back();
        }

        PendaftaranKP::create([
            'mahasiswa_nim' => auth()->user()->nim,               
            'prodi_id' => auth()->user()->prodi_id,   
            'konsentrasi_id' => auth()->user()->konsentrasi_id,              
            'krs_berjalan' =>str_replace('public/', '', $request->file('krs_berjalan')->store('public/file')),                    
            // 'transkip_nilai' =>$request->file('transkip_nilai')->store('file'),                        
            'transkip_nilai' =>str_replace('public/', '', $request->file('transkip_nilai')->store('public/file')),                        
            'dosen_pembimbing_nip' =>$request->dosen_pembimbing_nip,   
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'bidang_usaha' => $request->bidang_usaha,
            'tanggal_rencana' => $request->tanggal_rencana,
            
            'keterangan' => 'Menunggu persetujuan Admin Prodi',
            'tgl_created_usulankp' => Carbon::now(),

        ]);

        Alert::success('Berhasil!', 'Berhasil disimpan')->showConfirmButton('Ok', '#28a745');

        return redirect('/usulankp/index');
    }
    

    public function kapasitas_index()
    {
        return view('settings.kapasitas-index', [
            'dosens' => Dosen::all(),
            'kapasitas_bimbingan' => KapasitasBimbingan::all(),
            
        ]);
    }

    public function kapasitas_bimbingan_edit(Request $request, $id)
    {
        $kp = KapasitasBimbingan::find($id); 

        return view('settings.kapasitas-bimbingan', [
            'dosens' => Dosen::all(),
            'kp' => $kp,
            // 'pendaftaran_kp' => PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->get(),
            
        ]);
    }

public function kapasitasbimbingan_store(Request $request, $id)
{
   
        $kp = KapasitasBimbingan::find($id);
        $kp->kapasitas_kp = $request->kapasitas_kp;
        $kp->kapasitas_skripsi = $request->kapasitas_skripsi;
        $kp->update();

       Alert::success('Berhasil', 'Data berhasil disimpan')->showConfirmButton('Ok', '#28a745');
        return  back();
}



    public function createusulankp_ulang()
    {
        return view('pendaftaran.kerja-praktek.usulan-kp.create-ulang', [
            'dosens' => Dosen::all(),
            'pendaftaran_kp' => PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first(),
            
        ]);
    }
    

    public function storeusulankp_ulang(Request $request)
    {
        
        $request->validate([                                           
            'krs_berjalan' => 'required|mimes:pdf|max:200',
            'transkip_nilai' => 'required|mimes:pdf|max:200',
            'dosen_pembimbing_nip' => 'required',
            'nama_perusahaan' => 'required',
            'alamat_perusahaan' => 'required',
            'bidang_usaha' => 'required',
            'tanggal_rencana' => 'required',
                         
        ]);
       
  
        $dosen = Dosen::where('nip', $request->dosen_pembimbing_nip)->first();

        $kapasitasBimbingan = KapasitasBimbingan::value('kapasitas_kp');
        
        $jumlahBimbinganSaatIni = $dosen->pendaftaran_kp()
            ->where('status_kp', '!=', 'USULAN KP DITOLAK')
            ->where('status_kp', '!=', 'USULKAN KP ULANG')
            ->where('keterangan', '!=', 'Nilai KP Telah Keluar')
            ->count();

        if ($jumlahBimbinganSaatIni >= $kapasitasBimbingan) {
            Alert::warning('Pembimbing Penuh', 'Silahkan Usulkan Pembimbing Lain!')
                    ->showConfirmButton('Ok', '#dc3545')
                    ->footer('<a class="btn btn-info p-2 px-3" href="/kuota-bimbingan/kp">Cek Kuota Pembimbing</a>');

            return  back();
        }

        PendaftaranKP::create([
            'mahasiswa_nim' => auth()->user()->nim,               
            'prodi_id' => auth()->user()->prodi_id,   
            'konsentrasi_id' => auth()->user()->konsentrasi_id,              
            'krs_berjalan' =>str_replace('public/', '', $request->file('krs_berjalan')->store('public/file')),                    
            // 'transkip_nilai' =>$request->file('transkip_nilai')->store('file'),                        
            'transkip_nilai' =>str_replace('public/', '', $request->file('transkip_nilai')->store('public/file')),                        
            'dosen_pembimbing_nip' =>$request->dosen_pembimbing_nip,   
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'bidang_usaha' => $request->bidang_usaha,
            'tanggal_rencana' => $request->tanggal_rencana,
            
            'keterangan' => 'Menunggu persetujuan Admin Prodi',
            'tgl_created_usulankp' => Carbon::now(),

        ]);

        Alert::success('Berhasil!', 'Berhasil disimpan')->showConfirmButton('Ok', '#28a745');

        return redirect('/usulankp/index');
    }


    public function detailusulankp($id)
    {
        
        //DOSEN ROLE
        if (auth()->user()->role_id == 5 ) {            
            return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
       
        if (auth()->user()->c == 6) {            
            return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
        //MAHASISWA
        if (auth()->user()->nim > 0) {     
            return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('mahasiswa_nim', Auth::user()->nim)->get(),          
            ]);
        } 
        //DOSEN
        // if (auth()->user()->nip > 0) {  
        //     return view('pendaftaran.kerja-praktek.usulan-kp.detail', [
        //         'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
        //     ]);
        // } 

    }

    //DETAIL PERSETUJUAN
    public function detailpersetujuan_usulankp($id)
    {
        //ADMIN
        
        // if (auth()->user()->role_id == 1) {     
        //     return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
        //         'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
        //     ]);
        // } 
       
        if (auth()->user()->role_id == 2) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 3) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 4) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
        
        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        } 

    }
    //DETAIL PERSETUJUAN BALASAN KP
    public function detailpersetujuan_balasankp($id)
    {
        
        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->get(),
            ]);
        } 
        // DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        } 

    }
    //DETAIL PERSETUJUAN SEMKP
    public function detailpersetujuan_semkp($id)
    {
        // ADMIN
        
        // if (auth()->user()->role_id == 1) {     
        //     return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
        //         'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
        //     ]);
        // } 
       
        if (auth()->user()->role_id == 2) {            
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 3) {            
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 4) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
        
        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->get(),
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-seminar-kp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
            ]);
        } 

    }

    public function suratpermohonankp($id){
        $pendaftaran_kp = PendaftaranKP::findOrFail($id);

        $kaprodi1 = Dosen::where('role_id', '6')->first();
        $kaprodi2 = Dosen::where('role_id', '7')->first();
        $kaprodi3 = Dosen::where('role_id', '8')->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-surat-permohonan-kp').'/'. $pendaftaran_kp->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('pendaftaran.kerja-praktek.balasan-kp.surat-permohonan-kp',compact('pendaftaran_kp','kaprodi1','kaprodi2','kaprodi3','qrcode','pdf'));
        
        return $pdf->stream('KPTI-1 Surat Permohonan KP.pdf', array("Attachment" => false));
    }

    public function formpermohonankp($id){
        $pendaftaran_kp = PendaftaranKP::findOrFail($id);
        $pembimbing = PendaftaranKP::where('id', $id)->where('dosen_pembimbing_nip', auth()->user()->nip)->first();
        $kaprodi1 = Dosen::where('role_id', '6')->first();
        $kaprodi2 = Dosen::where('role_id', '7')->first();
        $kaprodi3 = Dosen::where('role_id', '8')->first();
        $koor1 = Dosen::where('role_id', '9')->first();
        $koor2 = Dosen::where('role_id', '10')->first();
        $koor3 = Dosen::where('role_id', '11')->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-form-permohonan-kp').'/'. $pendaftaran_kp->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('pendaftaran.kerja-praktek.balasan-kp.form-permohonan-kp',compact('pendaftaran_kp','pembimbing','qrcode', 'pdf', 'kaprodi1', 'kaprodi2', 'kaprodi3', 'koor1', 'koor2', 'koor3'));
        
        return $pdf->stream('KPTI/TE-2 Form Permohonan Kerja Praktek.pdf', array("Attachment" => false));
    }

    public function detailpersetujuan_kpti10($id)
    { 
        $pendaftaran_kp = PendaftaranKP::find($id);

        $penjadwalan_kp = PenjadwalanKP::where('mahasiswa_nim', $pendaftaran_kp->mahasiswa_nim)->latest('created_at')->first();
        $nilai_pembimbing = PenilaianKPPembimbing::where('penjadwalan_kp_id', $penjadwalan_kp->id)->latest('created_at')->first();
        $nilai_penguji = PenilaianKPPenguji::where('penjadwalan_kp_id', $penjadwalan_kp->id)->latest('created_at')->first();

        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 

    }


 //DETAIL SURAT BALASAN PERUSAHAAN 
    public function detailbalasankp($id)
    
    {
        if (auth()->user()->role_id == 5 ) {            
            return view('pendaftaran.kerja-praktek.balasan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
       
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.kerja-praktek.balasan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.kerja-praktek.balasan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.kerja-praktek.balasan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.kerja-praktek.balasan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.kerja-praktek.balasan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.kerja-praktek.balasan-kp.detail', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
        if (auth()->user()->nim > 0) {     
            return view('pendaftaran.kerja-praktek.balasan-kp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('mahasiswa_nim', Auth::user()->nim)->get(),          
            ]);
        } 
    }

//SURAT BALASAN PERUSAHAAN
    public function createbalasan($id)
    {
        return view('pendaftaran.kerja-praktek.balasan-kp.create', [
          'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('status_kp','USULAN KP DITERIMA' )->where('mahasiswa_nim', Auth::user()->nim)
          ->orWhere('id', $id)->where('status_kp','SURAT PERUSAHAAN DITOLAK' )->where('mahasiswa_nim', Auth::user()->nim)->get(),   
        ]);
    }

    public function storebalasan(Request $request, $id)
    {
        $request->validate([                                           
            'surat_balasan' => 'required|mimes:pdf|max:200',
            'tanggal_mulai' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);
        $kp->surat_balasan = str_replace('public/', '', $request->file('surat_balasan')->store('public/file'));
        $kp->tanggal_mulai = $request->tanggal_mulai;

        $kp->jenis_usulan = 'Surat Balasan Perusahaan';
        $kp->tgl_created_balasan = Carbon::now();
        $kp->status_kp = 'SURAT PERUSAHAAN';
        $kp->keterangan = 'Menunggu persetujuan Koordinator KP';
        $kp->update();

        Alert::success('Berhasil!', 'Data berhasil ditambahkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usulankp/index');
    }

    public function indexsemkp()
    {
        return view('pendaftaran.kerja-praktek.usulan-semkp.index', [
          'pendaftaran_kp' => PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->get(),   
          'penjadwalan_kp' => PenjadwalanKP::where('mahasiswa_nim', Auth::user()->nim)->get(),   
        ]);
    }

    public function detailusulansemkp($id)
    {
        //ADMIN
        if (auth()->user()->role_id == 1) {     
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 2) {            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 3) {            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 4) {  
            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
                // 'penjadwalan_kp' => PenjadwalanKP::where('prodi_id', '3')->get(),
            ]);
        } 

        //DOSEN

        if (auth()->user()->role_id == 5 ) {            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
       
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
        //MAHASISWA
        if (auth()->user()->nim > 0) {     
            return view('pendaftaran.kerja-praktek.usulan-semkp.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('mahasiswa_nim', Auth::user()->nim)->get(),          
            ]);
        } 
    }

    public function createsemkp($id)
    {
        return view('pendaftaran.kerja-praktek.usulan-semkp.create', [
            'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('status_kp','KP DISETUJUI' )->where('mahasiswa_nim', Auth::user()->nim)
            ->orWhere('id', $id)->where('status_kp','DAFTAR SEMINAR KP DITOLAK' )->where('mahasiswa_nim', Auth::user()->nim)
            ->orWhere('id', $id)->where('status_kp','DAFTAR SEMINAR KP ULANG' )->where('mahasiswa_nim', Auth::user()->nim)->get(),
        ]);
    }

    public function storesemkp(Request $request, $id)
    {
        $request->validate([                                           
            'judul_laporan' => 'required',
            'laporan_kp' => 'required|mimes:pdf|max:5120',
            'kpti_11' => 'required|mimes:pdf|max:200',
            'sti_31' => 'nullable|mimes:pdf|max:200',
        ]);

        $kp = PendaftaranKP::find($id);
        $kp->judul_laporan = $request->judul_laporan;
        $kp->laporan_kp = str_replace('public/', '', $request->file('laporan_kp')->store('public/file'));
        $kp->kpti_11 = str_replace('public/', '', $request->file('kpti_11')->store('public/file'));  

        if ($request->hasFile('sti_31')) {
        $kp->sti_31 = str_replace('public/', '', $request->file('sti_31')->store('public/file'));
        }

        $kp->jenis_usulan = 'Daftar Seminar Kerja Praktek';
        $kp->tgl_created_semkp = Carbon::now();
        $kp->status_kp = 'DAFTAR SEMINAR KP';
        $kp->keterangan = 'Menunggu persetujuan Admin Prodi';
        $kp->update();


        Alert::success('Berhasil!', 'Data berhasil ditambahkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usulankp/index');
    }

    //KPTI-10 / BUKTI PENYERAHAN LAPORAN
    public function indexkpti_10()
    {
        return view('pendaftaran.kerja-praktek.kpti-10.index', [
          'pendaftaran_kp' => PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->get(),   
        //   'penjadwalan_kp' => PenjadwalanKP::where('mahasiswa_nim', Auth::user()->nim)->get(),   
        ]);
    }
    public function createkpti_10($id)
    {
        return view('pendaftaran.kerja-praktek.kpti-10.create', [
            'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('status_kp','SEMINAR KP SELESAI' )->where('mahasiswa_nim', Auth::user()->nim)
            ->orWhere('id', $id)->where('status_kp','BUKTI PENYERAHAN LAPORAN DITOLAK' )->where('mahasiswa_nim', Auth::user()->nim)->get(),    
        ]);
    }
    public function storekpti_10(Request $request, $id)
    {

        $request->validate([                                         
            'kpti_10' => 'required|mimes:pdf|max:200',
            'laporan_akhir' => 'required|mimes:pdf|max:1024',
        ]);

        $kp = PendaftaranKP::find($id);

        $kp->kpti_10 = str_replace('public/', '', $request->file('kpti_10')->store('public/file'));
        $kp->laporan_akhir = str_replace('public/', '', $request->file('laporan_akhir')->store('public/file'));
        $kp->jenis_usulan = 'Penyerahan file KPTI-10/Bukti penyerahan laporan';
        $kp->status_kp = 'BUKTI PENYERAHAN LAPORAN';
        $kp->keterangan = 'Menunggu persetujuan Koordinator KP';
        $kp->tgl_created_kpti10 = Carbon::now();
        $kp->update();

        Alert::success('Berhasil!', 'Data berhasil ditambahkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usulankp/index');
    }
    public function detailkpti_10($id)
    {
        $pendaftaran_kp = PendaftaranKP::find($id);

        $penjadwalan_kp = PenjadwalanKP::where('mahasiswa_nim', $pendaftaran_kp->mahasiswa_nim)->latest('created_at')->first();
        $nilai_pembimbing = PenilaianKPPembimbing::where('penjadwalan_kp_id', $penjadwalan_kp->id)->latest('created_at')->first();
        $nilai_penguji = PenilaianKPPenguji::where('penjadwalan_kp_id', $penjadwalan_kp->id)->latest('created_at')->first();


        //ADMIN
        if (auth()->user()->role_id == 1) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 
       
        if (auth()->user()->role_id == 2) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 3) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 4) {  
            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
                // 'penjadwalan_kp' => PenjadwalanKP::where('prodi_id', '3')->get(),
            ]);
        } 
        if (auth()->user()->role_id == 5 ) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
       
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 
        if (auth()->user()->nim > 0) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('mahasiswa_nim', Auth::user()->nim)->get(),      
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji,    
            ]);
        } 
    }
    
    public function detail_riwayat_prodi_kpti_10($id)
    {
        $pendaftaran_kp = PendaftaranKP::find($id);

        $penjadwalan_kp = PenjadwalanKP::where('mahasiswa_nim', $pendaftaran_kp->mahasiswa_nim)->latest('created_at')->first();
        $nilai_pembimbing = PenilaianKPPembimbing::where('penjadwalan_kp_id', $penjadwalan_kp->id)->latest('created_at')->first();
        $nilai_penguji = PenilaianKPPenguji::where('penjadwalan_kp_id', $penjadwalan_kp->id)->latest('created_at')->first();


        //ADMIN
        if (auth()->user()->role_id == 1) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 
       
        if (auth()->user()->role_id == 2) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 3) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 4) {  
            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
                // 'penjadwalan_kp' => PenjadwalanKP::where('prodi_id', '3')->get(),
            ]);
        } 
        if (auth()->user()->role_id == 5 ) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
       
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.kerja-praktek.kpti-10.detail-riwayat-prodi', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji, 
            ]);
        } 
        if (auth()->user()->nim > 0) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('mahasiswa_nim', Auth::user()->nim)->get(),      
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_penguji' => $nilai_penguji,    
            ]);
        } 
    }
   
    public function destroy(PendaftaranKP $pendaftaranKP)
    {
        //
    }

    //APPROVAL USULAN KP PEMBIMBING
    public function approveusulankp_pemb(Request $request, $id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->keterangan = 'Menunggu persetujuan Koordinator KP';
        $kp->tgl_disetujui_usulankp_pembimbing = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Usulan KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }
    public function tolakusulankp_pemb(Request $request,$id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id); 
        $kp->status_kp = 'USULAN KP DITOLAK';
        $kp->keterangan = 'Ditolak Calon Pembimbing';
        $kp->alasan = $request->alasan;
        $kp->tgl_disetujui_usulankp_admin = null;
        $kp->update();

        Alert::error('Ditolak', 'Usulan ditolak!')->showConfirmButton('Ok', '#dc3545');
        
        return  back();
    }

    //APPROVAL USULAN KP ADMINPRODI
    public function approveusulankp_admin(Request $request, $id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->keterangan = 'Menunggu persetujuan Pembimbing';
        $kp->tgl_disetujui_usulankp_admin = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Usulan KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }
    public function tolakusulankp_admin(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id); 
        $kp->status_kp = 'USULAN KP DITOLAK';
        $kp->keterangan = 'Ditolak Admin Prodi';
        $kp->alasan = $request->alasan;
        $kp->update();

        Alert::error('Ditolak', 'Usulan KP ditolak!')->showConfirmButton('Ok', '#dc3545');
        
        return  back();
    }

//APPROVAL USULAN KP KOORDINATOR KP    
    public function approveusulankp_koordinator(Request $request, $id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->keterangan = 'Menunggu persetujuan Koordinator Program Studi';
        $kp->tgl_disetujui_usulankp_koordinator = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Usulan KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }

    public function tolakusulan_koordinator(Request $request,$id)
    {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);   
        $kp->status_kp = 'USULAN KP DITOLAK';
        $kp->keterangan = 'Ditolak Koordinator KP';
        $kp->alasan = $request->alasan;
        $kp->tgl_disetujui_usulankp_admin = null;
        $kp->tgl_disetujui_usulankp_pembimbing = null;
        $kp->update();

        Alert::error('Ditolak', 'Usulan KP ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }
    
    //APPROVAL USULAN KP KAPRODI   
        public function approveusulankp_kaprodi(Request $request, $id)
        {
            $kp = PendaftaranKP::find($id);
            $kp->status_kp = 'USULAN KP DITERIMA';
            $kp->keterangan = 'Usulan Kerja Praktek diterima';
            $kp->tgl_disetujui_usulankp_kaprodi = Carbon::now();
            $kp->update();
    
            Alert::success('Disetujui', 'Usulan KP disetujui!')->showConfirmButton('Ok', '#28a745');
            return  back();
        }
    
        public function tolakusulan_kaprodi(Request $request, $id)
        {
            $request->validate([                                           
            'alasan' => 'required',
            ]);

            $kp = PendaftaranKP::find($id);        
            $kp->status_kp = 'USULAN KP DITOLAK';
            $kp->keterangan = 'Ditolak Koordinator Program Studi';
            $kp->alasan = $request->alasan;
            $kp->tgl_disetujui_usulankp_admin = null;
            $kp->tgl_disetujui_usulankp_pembimbing = null;
            $kp->tgl_disetujui_usulankp_koordinator = null;
            $kp->update();
    
            Alert::error('Ditolak', 'Usulan KP berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
            return  back();
        }

        //APPROVAL SURAT PERUSAHAAN KOORDINATOR KP    
    public function approvebalasankp_koordinator(Request $request, $id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->status_kp = 'KP DISETUJUI';
        $kp->keterangan = 'Kerja Praktek disetujui';
        $kp->tgl_disetujui_balasan = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }

    public function tolakbalasankp_koordinator(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);   
        $kp->status_kp = 'SURAT PERUSAHAAN DITOLAK';
        $kp->keterangan = 'Ditolak Koordinator KP';
        $kp->alasan = $request->alasan;
        $kp->update();

        Alert::error('Ditolak', 'Surat Balasan Perusahaan ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }


//APPROVAL SEMINAR KP Admin      
    public function approvesemkp_admin($id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->keterangan = 'Menunggu persetujuan Pembimbing';
        $kp->tgl_disetujui_semkp_admin = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Seminar KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }

    public function tolaksemkp_admin(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);   
        $kp->status_kp = 'DAFTAR SEMINAR KP DITOLAK';
        $kp->keterangan = 'Ditolak Admin Prodi';
        $kp->alasan = $request->alasan;
        $kp->update();


        Alert::error('Ditolak', 'Seminar KP berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }

//APPROVAL SEMINAR KP PEMBIMBING      
    public function approveusulan_semkp_pemb($id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->keterangan = 'Menunggu persetujuan Koordinator KP';
        $kp->tgl_disetujui_semkp_pembimbing = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Seminar KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }

    public function tolakusulan_semkp_pemb(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);   
        $kp->status_kp = 'DAFTAR SEMINAR KP DITOLAK';
        $kp->keterangan = 'Ditolak Dosen Pembimbing';
        $kp->alasan = $request->alasan;
        $kp->tgl_disetujui_semkp_admin = null;
        $kp->update();


        Alert::error('Ditolak', 'Seminar KP berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }
    
    //APPROVAL SEMINAR KP KOORDINATOR
    public function approveusulan_semkp_koordinator($id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->keterangan = 'Menunggu persetujuan Koordinator Program Studi';
        $kp->tgl_disetujui_semkp_koordinator = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Seminar KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }
     public function tolak_semkp_koordinator(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);   
        $kp->status_kp = 'DAFTAR SEMINAR KP DITOLAK';
        $kp->keterangan = 'Ditolak Koordinator KP';
        $kp->alasan = $request->alasan;
        $kp->tgl_disetujui_semkp_admin = null;
        $kp->tgl_disetujui_semkp_pembimbing = null;
        $kp->update();

        Alert::error('Ditolak', 'Seminar KP berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }
    //APPROVAL SEMINAR KP KOORDINATOR
    public function approveusulan_semkp_kaprodi($id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->status_kp = 'DAFTAR SEMINAR KP DISETUJUI';
        $kp->keterangan = 'Menunggu Jadwal Seminar KP';
        $kp->tgl_disetujui_semkp_kaprodi = Carbon::now();
        $kp->update();

            $penjadwalanKP = new PenjadwalanKP();
            $penjadwalanKP->mahasiswa_nim = $kp->mahasiswa_nim;
            $penjadwalanKP->prodi_id = $kp->prodi_id;
            $penjadwalanKP->pembimbing_nip = $kp->dosen_pembimbing_nip;
            $penjadwalanKP->penguji_nip = $kp->dosen_pembimbing_nip;
            $penjadwalanKP->judul_kp = $kp->judul_laporan;
            $penjadwalanKP->dibuat_oleh = auth()->user()->nama;
            $penjadwalanKP->save();

        Alert::success('Disetujui', 'Seminar KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }
     public function tolak_semkp_kaprodi(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);   
        $kp->status_kp = 'DAFTAR SEMINAR KP DITOLAK';
        $kp->keterangan = 'Ditolak Koordinator Program Studi';
        $kp->alasan = $request->alasan;
        $kp->tgl_disetujui_semkp_admin = null;
        $kp->tgl_disetujui_semkp_pembimbing = null;
        $kp->tgl_disetujui_semkp_koordinator = null;
        $kp->update();

        Alert::error('Ditolak', 'Seminar KP berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }
    //APPROVAL SEMINAR KP
    public function approveusulan_semkp_jadwal($id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->status_kp = 'SEMINAR KP DIJADWALKAN';
        $kp->keterangan = 'Seminar KP Dijadwalkan';
        $kp->tgl_dijadwalkan = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Seminar KP dijadwalkan!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }
     public function tolak_semkp_jadwal(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);   
        $kp->status_kp = 'DAFTAR SEMINAR KP DITOLAK';
        $kp->keterangan = 'Ditolak Admin Koordinator KP';
        $kp->alasan = $request->alasan;
        $kp->update();

        Alert::error('Ditolak', 'Seminar KP berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }

    //APPROVAL SEMINAR KP SELESAI PEMBIMBING
    public function approveselesaiseminarkp_pemb($id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->status_kp = 'SEMINAR KP SELESAI';
        $kp->keterangan = 'Seminar Kerja Praktek Selesai';
        $kp->tgl_selesai_semkp = Carbon::now();
        $kp->update();

        Alert::success('Berhasil', 'Seminar KP Selesai!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }
     public function tolakselesaiseminarkp_pemb(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

         $kp = PendaftaranKP::find($id);   
        $kp->jenis_usulan = 'Seminar Kerja Praktek';
        $kp->status_kp = 'USULKAN KP ULANG';
        $kp->keterangan = 'Tidak Lulus Seminar Kerja Praktek';
        $kp->alasan = $request->alasan;
        $kp->update();


        Alert::error('Tidak Lulus', 'Tidak Lulus Seminar Kerja Praktek!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }


//APPROVAL KPTI-10 Bukti penyerahan laporan KP KOORDINATOR KP    
    public function approvekpti10_koordinator(Request $request, $id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->status_kp = 'KP SELESAI';
        $kp->keterangan = 'Nilai KP Telah Keluar';
        $kp->tgl_disetujui_kpti_10_koordinator = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Bukti penyerahan laporan KP disetujui!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }

    public function tolakkpti10_koordinator(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);   
        $kp->status_kp = 'BUKTI PENYERAHAN LAPORAN DITOLAK';
        $kp->keterangan = 'Ditolak Koordinator KP';
        $kp->alasan = $request->alasan;
        $kp->update();

        Alert::error('Ditolak', 'Bukti penyerahan laporan KP berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }

     public function approvenilai_keluar_koordinator(Request $request, $id)
    {
        $kp = PendaftaranKP::find($id);
        // $kp->status_kp = 'KP SELESAI';
        $kp->keterangan = 'Nilai KP Telah Keluar';
        $kp->update();

        Alert::success('Disetujui', 'Nilai KP Telah Keluar!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }


}
