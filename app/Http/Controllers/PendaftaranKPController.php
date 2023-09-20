<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;


use App\Models\PermohonanKP;
use App\Models\PendaftaranKP;
use App\Models\PenjadwalanKP;
use App\Models\PendaftaranSkripsi;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\StatusKP;
use App\Models\Konsentrasi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;
use \PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
            'kp' => $kp,
        ]);
    }

    public function createusulankp()
    {
        
        return view('pendaftaran.kerja-praktek.usulan-kp.create', [
            'dosens' => Dosen::all(), 
            'pendaftaran_kp' => PendaftaranKP::all()->sortBy('created_at'),
            
        ]);
    }
    

    public function storeusulankp(Request $request)
    {
        
        $request->validate([                                           
            'krs_berjalan' => 'required|mimes:pdf,jpeg,png,jpg|max:200',
            'transkip_nilai' => 'required|mimes:pdf,jpeg,png,jpg|max:200',
            'dosen_pembimbing_nip' => 'required',
            'nama_perusahaan' => 'required',
            'alamat_perusahaan' => 'required',
            'bidang_usaha' => 'required',
            'tanggal_rencana' => 'required',
                         
        ]);
       
  
        $dosen = Dosen::where('nip', $request->dosen_pembimbing_nip)->first();

        $kapasitasBimbingan = 10; 

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
            'mahasiswa_nama' =>auth()->user()->nama,               
            'prodi_id' => auth()->user()->prodi_id,   
            'konsentrasi_id' => auth()->user()->konsentrasi_id,              
            'krs_berjalan' =>$request->file('krs_berjalan')->store('file'),                        
            'transkip_nilai' =>$request->file('transkip_nilai')->store('file'),                        
            'dosen_pembimbing_nip' =>$request->dosen_pembimbing_nip,   
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'bidang_usaha' => $request->bidang_usaha,
            'tanggal_rencana' => $request->tanggal_rencana,
            
            'keterangan' => 'Menunggu persetujuan Admin Prodi',
            'tgl_created_usulankp' => Carbon::now(),

        ]);

        Alert::success('Berhasil!', 'KP Diusulkan')->showConfirmButton('Ok', '#28a745');

        return redirect('/usulankp/index');
    }
    public function createusulankp_ulang()
    {
        return view('pendaftaran.kerja-praktek.usulan-kp.create-ulang', [
            'dosens' => Dosen::all(), 
            'pendaftaran_kp' => PendaftaranKP::where('mahasiswa_nim', Auth::user()->nim)->get(),
            
        ]);
    }
    

    public function storeusulankp_ulang(Request $request, $id)
    {
        
        $request->validate([                                           
            'krs_berjalan' => 'required|mimes:pdf,jpeg,png,jpg|max:200',
            'transkip_nilai' => 'required|mimes:pdf,jpeg,png,jpg|max:200',
            'dosen_pembimbing_nip' => 'required',
            'nama_perusahaan' => 'required',
            'alamat_perusahaan' => 'required',
            'bidang_usaha' => 'required',
            'tanggal_rencana' => 'required',
                         
        ]);

        $kp = PendaftaranKP::find($id);
        $kp->dosen_pembimbing_nip = $request->dosen_pembimbing_nip;
        $kp->nama_perusahaan = $request->nama_perusahaan;
        $kp->alamat_perusahaan = $request->alamat_perusahaan;
        $kp->bidang_usaha = $request->bidang_usaha;
        $kp->tanggal_rencana = $request->tanggal_rencana;

        $kp->jenis_usulan = 'Usulan Kerja Praktek';
        $kp->tgl_created_usulankp = Carbon::now();
        $kp->status_kp = 'USULAN KP';
        $kp->keterangan = 'Menunggu persetujuan Pembimbing';
        $kp->update();

        Alert::success('Berhasil!', 'Data berhasil ditambahkan')->showConfirmButton('Ok', '#28a745');
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
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-usulankp', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->get(),
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
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-balasankp', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
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

    public function detailpersetujuan_kpti10($id)
    { 
        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->get(),
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-kpti10', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('dosen_pembimbing_nip', Auth::user()->nip)->get(),
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
            'surat_balasan' => 'required|mimes:pdf,jpeg,png,jpg|max:200',
            'tanggal_mulai' => 'required',
        ]);

        $kp = PendaftaranKP::find($id);
        $kp->surat_balasan = $request->file('surat_balasan')->store('file');
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
            ->orWhere('id', $id)->where('status_kp','DAFTAR SEMINAR KP DITOLAK' )->where('mahasiswa_nim', Auth::user()->nim)->get(), 
        ]);
    }

    public function storesemkp(Request $request, $id)
    {
        $request->validate([                                           
            'judul_laporan' => 'required',
            'laporan_kp' => 'required|mimes:pdf|max:1024',
            'kpti_11' => 'required|mimes:pdf,jpeg,png,jpg|max:200',
            'sti_31' => 'required|mimes:pdf,jpeg,png,jpg|max:200',
        ]);

        $kp = PendaftaranKP::find($id);
        $kp->judul_laporan = $request->judul_laporan;
        $kp->laporan_kp = $request->file('laporan_kp')->store('file');
        $kp->kpti_11 = $request->file('kpti_11')->store('file');
        $kp->sti_31 = $request->file('sti_31')->store('file');

        $kp->jenis_usulan = 'Daftar Seminar Kerja Praktek';
        $kp->tgl_created_semkp = Carbon::now();
        $kp->status_kp = 'DAFTAR SEMINAR KP';
        $kp->keterangan = 'Menunggu persetujuan Pembimbing';
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
            'kpti_10' => 'required|mimes:pdf,jpeg,png,jpg|max:200',
            'laporan_akhir' => 'required|mimes:pdf|max:1024',
        ]);

        $kp = PendaftaranKP::find($id);

        $kp->kpti_10 = $request->file('kpti_10')->store('file');
        $kp->laporan_akhir = $request->file('laporan_akhir')->store('file');
        $kp->jenis_usulan = 'Penyerahan file KPTI-10/Bukti penyerahan laporan';
        $kp->status_kp = 'BUKTI PENYERAHAN LAPORAN';
        $kp->keterangan = 'Menunggu persetujuan Koordinator KP';
        $kp->update();

        Alert::success('Berhasil!', 'Data berhasil ditambahkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usulankp/index');
    }
    public function detailkpti_10($id)
    {
        //ADMIN
        if (auth()->user()->role_id == 1) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 2) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 3) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 4) {  
            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
                // 'penjadwalan_kp' => PenjadwalanKP::where('prodi_id', '3')->get(),
            ]);
        } 
        if (auth()->user()->role_id == 5 ) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->get(),
            ]);
        }
       
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' =>  PendaftaranKP::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
        if (auth()->user()->nim > 0) {     
            return view('pendaftaran.kerja-praktek.kpti-10.detail', [
                'pendaftaran_kp' => PendaftaranKP::where('id', $id)->where('mahasiswa_nim', Auth::user()->nim)->get(),          
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
        $kp->update();

        Alert::error('Ditolak', 'Usulan ditolak!')->showConfirmButton('Ok', '#dc3545');
        
        return  back();
    }

    //APPROVAL USULAN KP ADMINPRODI
    public function approveusulankp_admin(Request $request, $id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->keterangan = 'Menunggu persetujuan Pembimbing';
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
            $kp->tgl_disetujui_usulankp = Carbon::now();
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
        $kp->keterangan = 'Unggah Ulang Surat Balasan Perusahaan';
        $kp->alasan = $request->alasan;
        $kp->update();

        Alert::error('Ditolak', 'Surat Balasan Perusahaan ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }


//APPROVAL SEMINAR KP PEMBIMBING      
    public function approveusulan_semkp_pemb($id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->keterangan = 'Menunggu Jadwal Seminar KP';
        // $kp->tgl_disetujui_semkp = Carbon::now();
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
        $kp->keterangan = 'Ditolak Calon Dosen Pembimbing';
        $kp->alasan = $request->alasan;
        $kp->update();


        Alert::error('Ditolak', 'Seminar KP berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }
    
    //APPROVAL SEMINAR KP KOORDINATOR
    public function approveusulan_semkp_koordinator($id)
    {
        $kp = PendaftaranKP::find($id);
        $kp->status_kp = 'SEMINAR KP DIJADWALKAN';
        $kp->keterangan = 'Seminar KP Dijadwalkan';
        $kp->tgl_dijadwalkan = Carbon::now();
        $kp->update();

        Alert::success('Disetujui', 'Seminar KP dijadwalkan!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }
     public function tolak_semkp_koordinator(Request $request, $id)
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
        $kp->keterangan = 'Proses Kerja Praktek Selesai, Bukti Penyerahan Laporan KP Disetujui';
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
        $kp->keterangan = 'Unggah Ulang KPTI-10/Bukti Penyerahan Laporan KP';
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