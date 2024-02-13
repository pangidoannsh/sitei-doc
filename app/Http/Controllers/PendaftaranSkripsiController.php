<?php

namespace App\Http\Controllers;

use \PDF;

use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\StatusKP;
use App\Models\Mahasiswa;
use App\Models\Konsentrasi;
use Illuminate\Http\Request;
use App\Models\PendaftaranKP;
use App\Models\PublikasiJurnal;
use App\Models\PenjadwalanSempro;
use App\Models\KapasitasBimbingan;
use App\Models\PendaftaranSkripsi;

use App\Models\PenjadwalanSkripsi;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

use App\Models\PenilaianSkripsiPenguji;
use Illuminate\Notifications\Notifiable;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\PenilaianSkripsiPembimbing;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Notifications\NotifPendaftaranSkripsi;


class PendaftaranSkripsiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexskripsi()
    {
        return view('pendaftaran.skripsi.index', [
            
        ]);
    }

    //USUL JUDUL
    public function indexusuljudul()
    {
        $pendaftaran_skripsi = PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first();
        $skripsi = PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->get();

        return view('pendaftaran.skripsi.usul-judul.index', [
            'pendaftaran_skripsi' => $pendaftaran_skripsi,
            'skripsi' => $skripsi,
        ]);
    }

    public function createusuljudul()
    {
        return view('pendaftaran.skripsi.usul-judul.create', [
            'dosens' => Dosen::all(), 
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->get(),
            'skripsi' => PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first(),
        ]);
    }
    public function storeusuljudul(Request $request)
    {
        $request->validate([                   
            'judul_skripsi' => 'required',                                           
            'krs_berjalan' => 'required|mimes:pdf|max:200',
            'khs' => 'required|mimes:pdf|max:200',
            'transkip_nilai' => 'required|mimes:pdf|max:200',
            'pembimbing_1_nip' => 'required',             
        ]);

       
        $pembimbing1_nip = $request->input('pembimbing_1_nip');
        $pembimbing2_nip = $request->input('pembimbing_2_nip');
        $dosen = Dosen::where('nip', $pembimbing1_nip)->first();
        $dosen2 = Dosen::where('nip', $pembimbing2_nip)->first();

        $maxMahasiswaPerDosen = KapasitasBimbingan::value('kapasitas_skripsi');
        
        $pendaftaranSkripsiCount = $dosen->pendaftaranSkripsiPembimbing1()
         ->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
            ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
            ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar')->count()
            + $dosen->pendaftaranSkripsiPembimbing2()
             ->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
            ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
            ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar')->count();
    if ($pembimbing2_nip != null){ 
        $pendaftaranSkripsiCount2 = $dosen2->pendaftaranSkripsiPembimbing1()
         ->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
            ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
            ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar')->count()
            + $dosen2->pendaftaranSkripsiPembimbing2()
             ->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
            ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
            ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar')->count();
            }
            
        if ($pendaftaranSkripsiCount >= $maxMahasiswaPerDosen) {
           Alert::warning('<h4 class="text-bold mb-0">Pembimbing 1 Penuh!</h4> <h5 class="mt-3">Silahkan Usulkan Pembimbing Lain</h5>')
                    ->showConfirmButton('Kembali', 'grey')
                    ->footer('<a class="btn btn-info p-2 px-3" formtarget="_blank" target="_blank" href="/kuota-bimbingan/skripsi">Cek Kuota Pembimbing</a>');

            return  back();
        }
        if ($pembimbing2_nip != null){ 
        if ($pendaftaranSkripsiCount2 >= $maxMahasiswaPerDosen) {
           Alert::warning('<h4 class="text-bold mb-0">Pembimbing 2 Penuh!</h4> <h5 class="mt-3">Silahkan Usulkan Pembimbing Lain</h5>')
                    ->showConfirmButton('Kembali', 'grey')
                    ->footer('<a class="btn btn-info p-2 px-3" formtarget="_blank" target="_blank" href="/kuota-bimbingan/skripsi">Cek Kuota Pembimbing</a>');

            return  back();
        }
        }

        PendaftaranSkripsi::create([
            'mahasiswa_nim' => auth()->user()->nim,                
            'prodi_id' => auth()->user()->prodi_id,   
            'konsentrasi_id' => auth()->user()->konsentrasi_id,                          
            'judul_skripsi' =>$request->judul_skripsi,                       
            // 'krs_berjalan' =>$request->file('krs_berjalan')->store('file'),                        
            // 'khs' =>$request->file('khs')->store('file'),                        
            'krs_berjalan' =>str_replace('public/', '', $request->file('krs_berjalan')->store('public/file')),                        
            'khs' =>str_replace('public/', '', $request->file('khs')->store('public/file')),                        
            'transkip_nilai' =>str_replace('public/', '', $request->file('transkip_nilai')->store('public/file')),                        
            'pembimbing_1_nip' =>$request->pembimbing_1_nip,
            'pembimbing_2_nip' =>$request->pembimbing_2_nip,
            
            'keterangan' => 'Menunggu persetujuan Admin Prodi',
            'tgl_created_usuljudul' => Carbon::now(),
        ]);

        Alert::success('Berhasil!', 'Judul Diusulkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usuljudul/index');

    }

    public function create_ulang_usuljudul()
    {
        return view('pendaftaran.skripsi.usul-judul.create-ulang', [
            'dosens' => Dosen::all(), 
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->get(),
            'skripsi' => PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first(),
        ]);
    }
    public function store_ulang_usuljudul(Request $request)
    {  
         $pendaftaran_skripsi = PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->latest('created_at')->first();

        if($pendaftaran_skripsi->status_skripsi == 'USULAN JUDUL DITOLAK' || $pendaftaran_skripsi->status_skripsi == 'USULKAN JUDUL ULANG'){
        $request->validate([                   
            'judul_skripsi' => 'required',                                           
            'krs_berjalan' => 'required|mimes:pdf|max:200',
            'khs' => 'required|mimes:pdf|max:200',
            'transkip_nilai' => 'required|mimes:pdf|max:200',
            'pembimbing_1_nip' => 'required',             
        ]);

       
        $pembimbing1_nip = $request->input('pembimbing_1_nip');
        $pembimbing2_nip = $request->input('pembimbing_2_nip');
        $dosen = Dosen::where('nip', $pembimbing1_nip)->first();
        $dosen2 = Dosen::where('nip', $pembimbing2_nip)->first();

        $maxMahasiswaPerDosen = KapasitasBimbingan::value('kapasitas_skripsi');
        
        $pendaftaranSkripsiCount = $dosen->pendaftaranSkripsiPembimbing1()
         ->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
            ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
            ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar')->count()
            + $dosen->pendaftaranSkripsiPembimbing2()
             ->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
            ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
            ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar')->count();
    if ($pembimbing2_nip != null){ 
        $pendaftaranSkripsiCount2 = $dosen2->pendaftaranSkripsiPembimbing1()
         ->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
            ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
            ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar')->count()
            + $dosen2->pendaftaranSkripsiPembimbing2()
             ->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
            ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
            ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar')->count();
            }
            
        if ($pendaftaranSkripsiCount >= $maxMahasiswaPerDosen) {
           Alert::warning('<h4 class="text-bold mb-0">Pembimbing 1 Penuh!</h4> <h5 class="mt-3">Silahkan Usulkan Pembimbing Lain</h5>')
                    ->showConfirmButton('Kembali', 'grey')
                    ->footer('<a class="btn btn-info p-2 px-3" formtarget="_blank" target="_blank" href="/kuota-bimbingan/skripsi">Cek Kuota Pembimbing</a>');

            return  back();
        }
        if ($pembimbing2_nip != null){ 
        if ($pendaftaranSkripsiCount2 >= $maxMahasiswaPerDosen) {
           Alert::warning('<h4 class="text-bold mb-0">Pembimbing 2 Penuh!</h4> <h5 class="mt-3">Silahkan Usulkan Pembimbing Lain</h5>')
                    ->showConfirmButton('Kembali', 'grey')
                    ->footer('<a class="btn btn-info p-2 px-3" formtarget="_blank" target="_blank" href="/kuota-bimbingan/skripsi">Cek Kuota Pembimbing</a>');

            return  back();
        }
        }

        PendaftaranSkripsi::create([
            'mahasiswa_nim' => auth()->user()->nim,                
            'prodi_id' => auth()->user()->prodi_id,   
            'konsentrasi_id' => auth()->user()->konsentrasi_id,                          
            'judul_skripsi' =>$request->judul_skripsi,                       
            // 'krs_berjalan' =>$request->file('krs_berjalan')->store('file'),                        
            // 'khs' =>$request->file('khs')->store('file'),                        
            'krs_berjalan' =>str_replace('public/', '', $request->file('krs_berjalan')->store('public/file')),                        
            'khs' =>str_replace('public/', '', $request->file('khs')->store('public/file')),                        
            'transkip_nilai' =>str_replace('public/', '', $request->file('transkip_nilai')->store('public/file')),                        
            'pembimbing_1_nip' =>$request->pembimbing_1_nip,
            'pembimbing_2_nip' =>$request->pembimbing_2_nip,
            
            'keterangan' => 'Menunggu persetujuan Admin Prodi',
            'tgl_created_usuljudul' => Carbon::now(),
        ]);

        Alert::success('Berhasil!', 'Judul Diusulkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usuljudul/index');

    }else{
        Alert::error('Gagal!', 'Anda telah melakukan usulan judul.')->showConfirmButton('Ok', '#dc3545');
        return back();
    }
}


    public function detailusuljudul(Request $request,$id)
    
    {
        if (auth()->user()->role_id == 5 ) {            
            return view('pendaftaran.skripsi.usul-judul.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
            ]);
        }
       
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.skripsi.usul-judul.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.skripsi.usul-judul.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.skripsi.usul-judul.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.skripsi.usul-judul.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.skripsi.usul-judul.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.skripsi.usul-judul.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
        if (auth()->user()->nim >0) {     
            return view('pendaftaran.skripsi.usul-judul.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('mahasiswa_nim', Auth::user()->nim)->get(),            
            ]);
        } 

    }
    //DETAIL PERSETUJUAN DOSEN
    public function detailpersetujuan_usulanjudul($id)
    {
        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-usul-judul', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-usul-judul', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-usul-judul', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-usul-judul', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-usul-judul', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-usul-judul', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-usul-judul', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 

    }

    //SEMINAR PROPOSAL

    public function indexsempro()
    {   
        return view('pendaftaran.skripsi.sempro.index', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->get(),
        ]);
    } 

    public function createsempro($id)
    {   
        return view('pendaftaran.skripsi.sempro.create', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);
    } 
    public function storesempro(Request $request, $id)
    {
        $request->validate([                                                          
            'krs_berjalan' => 'required|mimes:pdf|max:200',
            'khs' => 'required|mimes:pdf|max:200',
            'logbook' => 'required|mimes:pdf|max:200',
            'naskah' => 'required|mimes:pdf|max:5120',
            'sti_30' => 'required|mimes:pdf|max:200',          
            'sti_31' => 'nullable|mimes:pdf|max:200',          
        ]);

        $skripsi = PendaftaranSkripsi::find($id);
        $skripsi->krs_berjalan = str_replace('public/', '', $request->file('krs_berjalan')->store('public/file'));
        $skripsi->khs = str_replace('public/', '', $request->file('khs')->store('public/file'));
        $skripsi->logbook = str_replace('public/', '', $request->file('logbook')->store('public/file'));
        $skripsi->naskah = str_replace('public/', '', $request->file('naskah')->store('public/file'));
        $skripsi->sti_30 = str_replace('public/', '', $request->file('sti_30')->store('public/file'));

        if ($request->hasFile('sti_31')) {
        $skripsi->sti_31 = str_replace('public/', '', $request->file('sti_31')->store('public/file'));
        }
        
        $skripsi->jenis_usulan = 'Daftar Seminar Proposal';
        $skripsi->tgl_created_sempro = Carbon::now();
        $skripsi->status_skripsi = 'DAFTAR SEMPRO';
        $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 1';
        $skripsi->update();

        Alert::success('Berhasil!', 'Data berhasil ditambahkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usuljudul/index');
    }

    public function detailsempro($id)
    
    {
        //ADMIN
        if (auth()->user()->role_id == 1) {     
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 2) {            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 3) {            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 4) {  
            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 

        //DOSEN

        if (auth()->user()->role_id == 5 ) {            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
            ]);
        }
       
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->get(),
            ]);
        } 
        if (auth()->user()->nim > 0) {     
            return view('pendaftaran.skripsi.sempro.detail', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('mahasiswa_nim', Auth::user()->nim)->get(),            
            ]);
        } 

    }
    //DETAIL PERSETUJUAN DOSEN SEMPRO
    public function detailpersetujuan_daftarsempro($id)
    {
        
        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sempro', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sempro', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sempro', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sempro', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sempro', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sempro', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sempro', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 

    }

    //DAFTAR SIDANG

    public function indexsidang()
    {   
        return view('pendaftaran.skripsi.sidang.index', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('mahasiswa_nim', Auth::user()->nim)->get(),
        ]);
    } 
    public function createsidang($id)
    {   
        return view('pendaftaran.skripsi.sidang.create', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);
    } 
    public function storesidang(Request $request, $id)
    {
        $request->validate([        
            'skor_turnitin'=>'required',                                                  
            'resume_turnitin' => 'required|mimes:pdf|max:200',
            'sti_9' => 'required|mimes:pdf|max:200',
            // 'sti_11' => 'mimes:pdf|max:200',
            'naskah' => 'required|mimes:pdf|max:10240',
            'konsultasi_pa' => 'required|mimes:pdf|max:200',
            'transkip_nilai' => 'required|mimes:pdf|max:200',
            'khs' => 'required|mimes:pdf|max:200',
            'toefl' => 'required|mimes:pdf|max:200',
            'logbook' => 'required|mimes:pdf|max:200',
            'pasang_poster' => 'nullable|mimes:pdf|max:200', 
            'sti_10' => 'nullable|mimes:pdf|max:200',  
            'url_poster'=>'nullable',          
            'sti_30' => 'required|mimes:pdf|max:200',           
            'sti_31' => 'nullable|mimes:pdf|max:200',          
        ]);

        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->skor_turnitin = $request->skor_turnitin;
        $skripsi->resume_turnitin = str_replace('public/', '', $request->file('resume_turnitin')->store('public/file'));
        $skripsi->sti_9 = str_replace('public/', '', $request->file('sti_9')->store('public/file'));
        // $skripsi->sti_11 = str_replace('public/', '', $request->file('sti_11')->store('public/file'));
        $skripsi->naskah = str_replace('public/', '', $request->file('naskah')->store('public/file'));
        $skripsi->konsultasi_pa = str_replace('public/', '', $request->file('konsultasi_pa')->store('public/file'));
        $skripsi->transkip_nilai = str_replace('public/', '', $request->file('transkip_nilai')->store('public/file'));
        $skripsi->khs = str_replace('public/', '', $request->file('khs')->store('public/file'));
        $skripsi->toefl = str_replace('public/', '', $request->file('toefl')->store('public/file'));
        $skripsi->logbook = str_replace('public/', '', $request->file('logbook')->store('public/file'));
        
        $skripsi->url_poster = $request->url_poster;
        $skripsi->sti_30 = str_replace('public/', '', $request->file('sti_30')->store('public/file'));
        // $skripsi->sti_31 = str_replace('public/', '', $request->file('sti_31')->store('public/file'));

         if ($request->hasFile('sti_10')) {
        $skripsi->sti_10 = str_replace('public/', '', $request->file('sti_10')->store('public/file'));
        }
        
        if ($request->hasFile('sti_31')) {
        $skripsi->sti_31 = str_replace('public/', '', $request->file('sti_31')->store('public/file'));
        }
        if ($request->hasFile('pasang_poster')) {
        $skripsi->pasang_poster = str_replace('public/', '', $request->file('pasang_poster')->store('public/file'));
        }
       
        
        $skripsi->jenis_usulan = 'Daftar Sidang Skripsi';
        $skripsi->tgl_created_sidang = Carbon::now();
        $skripsi->status_skripsi = 'DAFTAR SIDANG';
        $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 1';
        $skripsi->update();


        $jurnal = new PublikasiJurnal();

        $request->validate([           
            'file_jurnal' => 'nullable|mimes:pdf',           
        ]);

        if ($request->hasFile('file_jurnal')) {
        $jurnal->file_jurnal = str_replace('public/', '', $request->file('file_jurnal')->store('public/file'));
        }

        $jurnal->pendaftaran_skripsi_id = $skripsi->id;
        $jurnal->mahasiswa_nim = $skripsi->mahasiswa_nim;
        $jurnal->link_jurnal = $request->link_jurnal;
        $jurnal->indeksasi_jurnal = $request->indeksasi_jurnal;
        $jurnal->judul_jurnal = $request->judul_jurnal;
        $jurnal->status_publikasi_jurnal = $request->status_publikasi_jurnal;

        if ($request->file_jurnal && $request->judul_jurnal && $request->status_publikasi_jurnal) {
            $jurnal->save();
        }
        

        
        Alert::success('Berhasil!', 'Daftar Sidang Diusulkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usuljudul/index');
    }

    public function detailsidang($id)
    
    {
        return view('pendaftaran.skripsi.sidang.detail', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);
    }
    //DETAIL PERSETUJUAN DOSEN SIDANG
    public function detailpersetujuan_daftarsidang($id)
    {
        
        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sidang', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sidang', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sidang', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sidang', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sidang', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sidang', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-daftar-sidang', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 

    }

    public function createperpanjangan1_skripsi($id)
    {   
        return view('pendaftaran.skripsi.perpanjangan-skripsi.create1', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);
    } 

    public function storeperpanjangan1_skripsi(Request $request, $id)
    {
        $request->validate([         
            'sti_22' => 'required|mimes:pdf|max:200',           
        ]);

        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->sti_22 = str_replace('public/', '', $request->file('sti_22')->store('public/file'));
       
        $skripsi->jenis_usulan = 'Permohonan Perpanjangan 1 Waktu Skripsi';
        $skripsi->tgl_created_perpanjangan1 = Carbon::now();
        $skripsi->status_skripsi = 'PERPANJANGAN 1';
        $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 1';
        $skripsi->update();

        
        Alert::success('Berhasil!', 'Perpanjangan 1 Waktu Skripsi berhasil diusulkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usuljudul/index');
    }

    public function storeperpanjangan2_skripsi(Request $request, $id)
    {
        $request->validate([         
            'sti_22' => 'required|mimes:pdf|max:200',           
        ]);

        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->sti_22 = str_replace('public/', '', $request->file('sti_22')->store('public/file'));
       
        $skripsi->jenis_usulan = 'Permohonan Perpanjangan 2 Waktu Skripsi';
        $skripsi->tgl_created_perpanjangan2 = Carbon::now();
        $skripsi->status_skripsi = 'PERPANJANGAN 2';
        $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 1';
        $skripsi->update();

        
       if ($skripsi->update()) {
        Alert::success('Berhasil!', 'Perpanjangan 2 Waktu Skripsi berhasil diusulkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usuljudul/index');
    } else {
        Alert::error('Gagal!', 'Perpanjangan 2 Waktu Skripsi belum diusulkan')->showConfirmButton('Ok', '#dc3545');
        return redirect('/usuljudul/index');
    }
        
    }

    //PERPANJANGAN REVISI
    public function createperpanjangan_revisi($id)
    {   
        return view('pendaftaran.skripsi.perpanjangan.perpanjangan-revisi', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);
    } 
    public function storeperpanjangan_revisi(Request $request, $id)
    {
        $request->validate([         
            'sti_23' => 'required|mimes:pdf|max:200',           
        ]);

        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->sti_23 = str_replace('public/', '', $request->file('sti_23')->store('public/file'));
       
        $skripsi->jenis_usulan = 'Permohonan Perpanjangan Revisi Buku Skripsi';
        $skripsi->tgl_created_revisi = Carbon::now();
        $skripsi->status_skripsi = 'PERPANJANGAN REVISI';
        $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 1';
        $skripsi->update();

        
        Alert::success('Berhasil!', 'Data berhasil ditambahkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usuljudul/index');
    }

    public function detailperpanjangan_revisi($id)
    
    {
         return view('pendaftaran.skripsi.perpanjangan.detail-revisi', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);   
    }

    public function detailperpanjangan_1($id)
    
    {
       
        return view('pendaftaran.skripsi.perpanjangan-skripsi.detail1', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);
    }
    public function detailperpanjangan_2($id)
    
    {
        return view('pendaftaran.skripsi.perpanjangan-skripsi.detail2', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);
    }
    //DETAIL PERSETUJUAN DOSEN PERPANJANGAN REVISI
    public function detailpersetujuan_perpanjangan_revisi($id)
    {
        
        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-revisi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-revisi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-revisi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-revisi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-revisi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-revisi', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-revisi', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 

    }
    public function detailpersetujuan_bukti_buku_skripsi($id)
    {
        $pendaftaran_skripsi = PendaftaranSkripsi::find($id);

         $penjadwalan_skripsi = PenjadwalanSkripsi::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim)->latest('created_at')->first();


        $nilai_pembimbing1 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingsatu_nip)->latest('created_at')->first();
        
        $nilai_pembimbing2 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingdua_nip)->latest('created_at')->first();

        
        $nilai_penguji1 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujisatu_nip)->latest('created_at')->first();
        
        $nilai_penguji2 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujidua_nip)->latest('created_at')->first();
        
        $nilai_penguji3 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujitiga_nip)->latest('created_at')->first();

        //DOSEN ROLE
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-laporan-skripsi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
                'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3, 
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-laporan-skripsi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
                'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3, 
            ]);
        }
        if (auth()->user()->role_id == 8) {     
            return view('pendaftaran.dosen.detail-persetujuan-laporan-skripsi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
                'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3, 
            ]);
        } 
       
        if (auth()->user()->role_id == 9) {            
            return view('pendaftaran.dosen.detail-persetujuan-laporan-skripsi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
                'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3, 
            ]);
        }
        if (auth()->user()->role_id == 10) {            
            return view('pendaftaran.dosen.detail-persetujuan-laporan-skripsi', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
                'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3, 
            ]);
        }
        if (auth()->user()->role_id == 11) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-laporan-skripsi', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
                'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3, 
            ]);
        } 
        //DOSEN
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-laporan-revisi', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
                'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3, 
            ]);
        } 

    }

    //DETAIL PERSETUJUAN DOSEN PERPANJANGAN 1
    public function detailpersetujuan_perpanjangan_1($id)
    {
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-1', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-1', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-1', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
        
         if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-1', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }

    }
    public function detailpersetujuan_perpanjangan_2($id)
    {
        if (auth()->user()->role_id == 6) {            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-2', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '1')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 7) {            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-2', [
                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->where('prodi_id', '2')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }
        if (auth()->user()->role_id == 8) {  
            
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-2', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('prodi_id', '3')->orWhere('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        } 
        
        if (auth()->user()->nip > 0) {  
            return view('pendaftaran.dosen.detail-persetujuan-perpanjangan-2', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
        }

    }

    public function detail_perpanjangan_revisi_pemb($id)
    {
       return view('pendaftaran.dosen.detail-perpanjangan-revisi-pemb', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
    }
    public function detail_perpanjangan_1_pemb($id)
    {
       return view('pendaftaran.dosen.detail-perpanjangan-1-pemb', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
    }
    public function detail_perpanjangan_2_pemb($id)
    {
       return view('pendaftaran.dosen.detail-perpanjangan-2-pemb', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),
            ]);
    }
    public function detail_bukti_buku_skripsi($id)
    {
         $pendaftaran_skripsi = PendaftaranSkripsi::find($id);

         $penjadwalan_skripsi = PenjadwalanSkripsi::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim)->latest('created_at')->first();


        $nilai_pembimbing1 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingsatu_nip)->latest('created_at')->first();
        
        $nilai_pembimbing2 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingdua_nip)->latest('created_at')->first();

        
        $nilai_penguji1 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujisatu_nip)->latest('created_at')->first();
        
        $nilai_penguji2 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujidua_nip)->latest('created_at')->first();
        
        $nilai_penguji3 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujitiga_nip)->latest('created_at')->first();

        $jurnal = PublikasiJurnal::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim )->latest('created_at')->first();

       return view('pendaftaran.dosen.detail-laporan-skripsi-pemb', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),

                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
            'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3,
            'jurnal' => $jurnal,
            ]);
    }
    
    public function detail_riwayat_pemb_bukti_buku_skripsi($id)
    {
         $pendaftaran_skripsi = PendaftaranSkripsi::find($id);

         $penjadwalan_skripsi = PenjadwalanSkripsi::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim)->latest('created_at')->first();


        $nilai_pembimbing1 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingsatu_nip)->latest('created_at')->first();
        
        $nilai_pembimbing2 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingdua_nip)->latest('created_at')->first();

        
        $nilai_penguji1 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujisatu_nip)->latest('created_at')->first();
        
        $nilai_penguji2 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujidua_nip)->latest('created_at')->first();
        
        $nilai_penguji3 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujitiga_nip)->latest('created_at')->first();

        $jurnal = PublikasiJurnal::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim )->latest('created_at')->first();

       return view('pendaftaran.skripsi.laporan-skripsi.detail-riwayat-pemb', [
                'pendaftaran_skripsi' =>  PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', Auth::user()->nip)->orWhere('id', $id)->where('pembimbing_2_nip', Auth::user()->nip)->get(),

                'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
            'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3,
            'jurnal' => $jurnal,
            ]);
    }


        public function detailbukti_buku_skripsi($id)
    {
        $pendaftaran_skripsi = PendaftaranSkripsi::find($id);

         $penjadwalan_skripsi = PenjadwalanSkripsi::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim)->latest('created_at')->first();


        $nilai_pembimbing1 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingsatu_nip)->latest('created_at')->first();
        
        $nilai_pembimbing2 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingdua_nip)->latest('created_at')->first();

        
        $nilai_penguji1 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujisatu_nip)->latest('created_at')->first();
        
        $nilai_penguji2 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujidua_nip)->latest('created_at')->first();
        
        $nilai_penguji3 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujitiga_nip)->latest('created_at')->first();

        $jurnal = PublikasiJurnal::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim )->latest('created_at')->first();

        return view('pendaftaran.skripsi.laporan-skripsi.detail', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
            'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3,
            'jurnal' => $jurnal, 
        ]);
    }
       
    public function detail_riwayat_prodi_bukti_buku_skripsi($id)
    {
        $pendaftaran_skripsi = PendaftaranSkripsi::find($id);

         $penjadwalan_skripsi = PenjadwalanSkripsi::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim)->latest('created_at')->first();


        $nilai_pembimbing1 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingsatu_nip)->latest('created_at')->first();
        
        $nilai_pembimbing2 = PenilaianSkripsiPembimbing::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('pembimbing_nip', $penjadwalan_skripsi->pembimbingdua_nip)->latest('created_at')->first();

        
        $nilai_penguji1 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujisatu_nip)->latest('created_at')->first();
        
        $nilai_penguji2 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujidua_nip)->latest('created_at')->first();
        
        $nilai_penguji3 = PenilaianSkripsiPenguji::where('penjadwalan_skripsi_id', $penjadwalan_skripsi->id)
        ->where('penguji_nip', $penjadwalan_skripsi->pengujitiga_nip)->latest('created_at')->first();

        $jurnal = PublikasiJurnal::where('mahasiswa_nim', $pendaftaran_skripsi->mahasiswa_nim )->latest('created_at')->first();

        return view('pendaftaran.skripsi.laporan-skripsi.detail-riwayat-prodi', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
            'nilaipembimbing1' => $nilai_pembimbing1,
            'nilaipembimbing2' => $nilai_pembimbing2,
            'nilaipenguji1' => $nilai_penguji1, 
            'nilaipenguji2' => $nilai_penguji2, 
            'nilaipenguji3' => $nilai_penguji3, 
            'jurnal' => $jurnal, 
        ]);
    }

     public function createbukti_buku_skripsi($id)
    {  

        return view('pendaftaran.skripsi.laporan-skripsi.create', [
            'pendaftaran_skripsi' => PendaftaranSkripsi::where('id', $id)->get(),
        ]);
    } 
    public function storebukti_buku_skripsi(Request $request, $id)
    {
        $request->validate([         
            'sti_17' => 'required|mimes:pdf|max:200',           
            'sti_29' => 'nullable|mimes:pdf|max:200',           
            'naskah' => 'required|mimes:pdf|max:200',           
        ]);

        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->sti_17 = str_replace('public/', '', $request->file('sti_17')->store('public/file'));
        // $skripsi->sti_29 = str_replace('public/', '', $request->file('sti_29')->store('public/file'));
        $skripsi->naskah = str_replace('public/', '', $request->file('naskah')->store('public/file'));

        if ($request->hasFile('sti_29')) {
        $skripsi->sti_29 = str_replace('public/', '', $request->file('sti_29')->store('public/file'));
        }
       
        $skripsi->jenis_usulan = 'Bukti Penyerahan Buku Skripsi';
        $skripsi->tgl_created_sti_17 = Carbon::now();
        $skripsi->status_skripsi = 'BUKTI PENYERAHAN BUKU SKRIPSI';
        $skripsi->keterangan = 'Menunggu persetujuan Koordinator Skripsi';
        $skripsi->update();

        
        Alert::success('Berhasil!', 'Data berhasil ditambahkan')->showConfirmButton('Ok', '#28a745');
        return redirect('/usuljudul/index');
    }

    // Surat Permohonan Pengajuan Topik Skripsi

    public function suratpermohonanpengajuantopikskripsi($id){
        $pendaftaran_skripsi = PendaftaranSkripsi::findOrFail($id);

        $kaprodi1 = Dosen::where('role_id', '6')->first();
        $kaprodi2 = Dosen::where('role_id', '7')->first();
        $kaprodi3 = Dosen::where('role_id', '8')->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-surat-permohonan-pengajuan-topik-skripsi').'/'. $pendaftaran_skripsi->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('pendaftaran.skripsi.usul-judul.surat-permohonan-pengajuan-topik-skripsi',compact('pendaftaran_skripsi','kaprodi1','kaprodi2','kaprodi3','qrcode','pdf'));
        
        return $pdf->stream('KPTI-1 Surat Permohonan KP.pdf', array("Attachment" => false));
    }

    public function formpengajuantopikskripsi($id){
        $pendaftaran_skripsi = PendaftaranSkripsi::findOrFail($id);
        $pembimbing = PendaftaranSkripsi::where('id', $id)->where('pembimbing_1_nip', auth()->user()->nip)->
        orWhere('id', $id)->where('pembimbing_2_nip', auth()->user()->nip)->first();
        $kaprodi1 = Dosen::where('role_id', '6')->first();
        $kaprodi2 = Dosen::where('role_id', '7')->first();
        $kaprodi3 = Dosen::where('role_id', '8')->first();
        $koor1 = Dosen::where('role_id', '9')->first();
        $koor2 = Dosen::where('role_id', '10')->first();
        $koor3 = Dosen::where('role_id', '11')->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-form-pengajuan-topik-skripsi').'/'. $pendaftaran_skripsi->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('pendaftaran.skripsi.usul-judul.form-pengajuan-topik-skripsi',compact('pendaftaran_skripsi','pembimbing','qrcode', 'pdf', 'kaprodi1', 'kaprodi2', 'kaprodi3', 'koor1', 'koor2', 'koor3'));
        
        return $pdf->stream('STI/TE-2 Form Pengajuan Topik Skripsi.pdf', array("Attachment" => false));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PendaftaranSkripsi  $pendaftaranSkripsi
     * @return \Illuminate\Http\Response
     */
    public function destroy(PendaftaranSkripsi $pendaftaranSkripsi)
    {
        //
    }


    // APPROVAL ADMIN
        public function approveusuljudul_admin(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);
        $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 1';
        $skripsi->tgl_disetujui_usuljudul_admin = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Usulan judul disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
    }

    public function tolakusuljudul_admin(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'USULAN JUDUL DITOLAK';
        $skripsi->keterangan = 'Ditolak Admin Prodi';
        $skripsi->alasan = $request->alasan;
        $skripsi->update();

        Alert::error('Ditolak', 'Usulan judul berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
        
    }


    //APPROVAL PEMBIMBING

    public function approveusuljudul_pembimbing(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        if ($skripsi->pembimbing_2_nip == null) {
        $skripsi->keterangan = 'Menunggu persetujuan Koordinator Skripsi';
        $skripsi->tgl_disetujui_usuljudul_pemb1 = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Usulan judul disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
        }else {

        $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 2';
        $skripsi->tgl_disetujui_usuljudul_pemb1 = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Usulan judul disetujui')->showConfirmButton('Ok', '#28a745');
        // $skripsi->notify(new NotifPendaftaranSkripsi($skripsi->mahasiswa->email));
        return back();
        }

    }
    public function tolakusuljudul_pembimbing(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'USULAN JUDUL DITOLAK';
        $skripsi->keterangan = 'Ditolak Pembimbing 1';
        $skripsi->alasan = $request->alasan;
        $skripsi->update();

        
        Alert::error('Ditolak', 'Usulan judul berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        
        return  back();
    }

    public function approveusuljudul_pembimbing2(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);
        $skripsi->keterangan = 'Menunggu persetujuan Koordinator Skripsi';
        $skripsi->tgl_disetujui_usuljudul_pemb2 = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Usulan judul disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
    }
    
    public function tolakusuljudul_pembimbing2(Request $request, $id)
    {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'USULAN JUDUL DITOLAK';
        $skripsi->keterangan = 'Ditolak Pembimbing 2';
        $skripsi->alasan = $request->alasan;
        $skripsi->update();

        Alert::error('Ditolak', 'Usulan judul berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }


    // APPROVAL KOORDINATOR
    public function approveusuljudul(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);
        $skripsi->keterangan = 'Menunggu persetujuan Koordinator Program Studi';
        $skripsi->tgl_disetujui_usuljudul_koordinator = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui', 'Usulan judul disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
    }

    public function tolakusuljudul_koordinator(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'USULAN JUDUL DITOLAK';
        $skripsi->keterangan = 'Ditolak Koordinator Skripsi';
        $skripsi->alasan = $request->alasan;
        $skripsi->update();

        Alert::error('Ditolak', 'Usulan judul berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }

    

    public function approveusuljudul_kaprodi(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);
        $skripsi->status_skripsi = 'JUDUL DISETUJUI';
        $skripsi->keterangan = 'Usulan Judul Skripsi Disetujui';
        $skripsi->tgl_disetujui_usuljudul_kaprodi = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Usulan judul disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
    }
    public function tolakusuljudul_kaprodi(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'USULAN JUDUL DITOLAK';
        $skripsi->keterangan = 'Ditolak Koordinator Program Studi';
        $skripsi->alasan = $request->alasan;
        $skripsi->update();


        Alert::error('Ditolak', 'Usulan judul berhasil ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }


    //DAFTAR-SEMPRO

    public function approve_sempro_admin(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->keterangan = 'Menunggu Jadwal Seminar Proposal';
        $skripsi->tgl_disetujui_jadwalsempro = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Daftar Sempro Disetujui')->showConfirmButton('Ok', '#28a745');
        return back();

    }
    public function tolak_sempro_admin(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SEMPRO DITOLAK';
        $skripsi->keterangan = 'Ditolak Koordinator Skripsi';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_created_sempro = null;
        $skripsi->update();

        Alert::error('Ditolak!', 'Daftar Sempro Ditolak')->showConfirmButton('Ok', '#dc3545');
        return back();
    
    }

    public function approvedaftarsempro_pembimbing(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        if ($skripsi->pembimbing_2_nip == null) {
        $skripsi->keterangan = 'Menunggu persetujuan Admin Prodi';
        $skripsi->tgl_disetujui_sempro_pemb1 = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Daftar Sempro disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
        }else {
        $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 2';
        $skripsi->tgl_disetujui_sempro_pemb1 = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Daftar Sempro disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
        }

    }
    public function tolakdaftarsempro_pembimbing(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SEMPRO DITOLAK';
        $skripsi->keterangan = 'Ditolak Pembimbing 1';
        $skripsi->alasan = $request->alasan;
        $skripsi->update();

        Alert::error('Ditolak', 'Daftar sempro ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }
    public function approvedaftarsempro_pembimbing2(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->keterangan = 'Menunggu persetujuan Admin Prodi';
        $skripsi->tgl_disetujui_sempro_pemb2 = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Daftar sempro disetujui')->showConfirmButton('Ok', '#28a745');
        return back();

    }
    public function tolakdaftarsempro_pembimbing2(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SEMPRO DITOLAK';
        $skripsi->keterangan = 'Ditolak Pembimbing 2';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_disetujui_pemb1 = null;
        $skripsi->update();

        Alert::error('Ditolak', 'Daftar Sempro Ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }

    public function approvesempro_admin(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->status_skripsi = 'DAFTAR SEMPRO DISETUJUI';
        $skripsi->keterangan = 'Menunggu Jadwal Seminar Proposal';
        $skripsi->tgl_disetujui_sempro_admin = Carbon::now();
        $skripsi->update();

        $penjadwalanSempro = new PenjadwalanSempro();
        $penjadwalanSempro->mahasiswa_nim = $skripsi->mahasiswa_nim;
        $penjadwalanSempro->prodi_id = $skripsi->prodi_id;
        $penjadwalanSempro->pembimbingsatu_nip = $skripsi->pembimbing_1_nip;
        $penjadwalanSempro->pembimbingdua_nip = $skripsi->pembimbing_2_nip ?? null;
        $penjadwalanSempro->judul_proposal = $skripsi->judul_skripsi;
        $penjadwalanSempro->dibuat_oleh = auth()->user()->username;
        $penjadwalanSempro->save();

        Alert::success('Disetujui!', 'Daftar Sempro Disetujui')->showConfirmButton('Ok', '#28a745');
        return back();

    }
    
    public function tolaksempro_admin(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SEMPRO DITOLAK';
        $skripsi->keterangan = 'Ditolak Admin prodi';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_disetujui_sempro_pemb1 = null;
        $skripsi->tgl_disetujui_sempro_pemb2 = null;
        $skripsi->tgl_disetujui_sempro_admin = null;
        $skripsi->update();

        Alert::error('Ditolak!', 'Daftar Sempro Ditolak')->showConfirmButton('Ok', '#dc3545');
        return back();
    
    }

    // public function approve_sempro_koordinator(Request $request, $id)
    // {
    //     $skripsi = PendaftaranSkripsi::find($id);

    //     $skripsi->status_skripsi = 'SEMPRO DIJADWALKAN';
    //     $skripsi->jenis_usulan = 'Seminar Proposal';
    //     $skripsi->keterangan = 'Seminar Proposal Dijadwalkan';
    //     $skripsi->tgl_disetujui_jadwalsempro = Carbon::now();
    //     $skripsi->update();

    //     Alert::success('Disetujui!', 'Daftar Sempro Disetujui')->showConfirmButton('Ok', '#28a745');
    //     return back();

    // }
    // public function tolak_sempro_koordinator(Request $request, $id)
    // {
    //     $request->validate([                                           
    //         'alasan' => 'required',
    //     ]);

    //     $skripsi = PendaftaranSkripsi::find($id);        
    //     $skripsi->status_skripsi = 'DAFTAR SEMPRO ULANG';
    //     $skripsi->keterangan = 'Ditolak Koordinator Skripsi';
    //     $skripsi->alasan = $request->alasan;
    //     $skripsi->tgl_created_sempro = null;
    //     $skripsi->update();

    //     Alert::error('Ditolak!', 'Daftar Sempro Ditolak')->showConfirmButton('Ok', '#dc3545');
    //     return back();
    
    // }

    //APPROVAL SEMPRO SELESAI PEMBIMBING
    // public function approveselesaisempro_pemb($id)
    // {
    //     $skripsi = PendaftaranSkripsi::find($id);

    //     $skripsi->status_skripsi = 'SEMPRO SELESAI';
    //     $skripsi->keterangan = 'Seminar Proposal Selesai';
    //     $skripsi->tgl_semproselesai = Carbon::now();
    //     $skripsi->update();

    //     Alert::success('Selesai', 'Seminar Proposal Selesai!')->showConfirmButton('Ok', '#28a745');;
    //     return  back();
    // }
    // public function tolakselesaisempro_pemb(Request $request, $id)
    // {
    //      $request->validate([                                           
    //         'alasan' => 'required',
    //     ]);

    //     $skripsi = PendaftaranSkripsi::find($id);        
    //     $skripsi->status_skripsi = 'USULKAN JUDUL ULANG';
    //     $skripsi->keterangan = 'Tidak Lulus Seminar Proposal';
    //     $skripsi->alasan = $request->alasan;
    //     $skripsi->tgl_created_sempro = null;
    //     $skripsi->update();

    //     Alert::error('Tidak Lulus', 'Tidak Lulus Seminar Proposal!')->showConfirmButton('Ok', '#dc3545');
    //     return  back();
    // }


     //DAFTAR SIDANG
     public function approvedaftarsidang_pembimbing(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
         if ($skripsi->pembimbing_2_nip == null) {
         $skripsi->keterangan = 'Menunggu persetujuan Admin Prodi';
         $skripsi->tgl_disetujui_sidang_pemb1 = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Daftar sidang disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
         }else {
 
         $skripsi->keterangan = 'Menunggu persetujuan Pembimbing 2';
         $skripsi->tgl_disetujui_sidang_pemb1 = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Daftar Sidang Disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
         }
 
     }
     public function tolakdaftarsidang_pembimbing(Request $request, $id)
     { 
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SIDANG DITOLAK';
        $skripsi->keterangan = 'Ditolak Pembimbing 1';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_created_sidang = null;
        $skripsi->update();
 
         Alert::error('Ditolak', 'Daftar Sidang Skripsi Ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
     }

     public function approvedaftarsidang_pembimbing2(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);
        $skripsi->keterangan = 'Menunggu persetujuan Admin Prodi';
        $skripsi->tgl_disetujui_sidang_pemb2 = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Daftar sidang disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
    }
    public function tolakdaftarsidang_pembimbing2(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SIDANG DITOLAK';
        $skripsi->keterangan = 'Ditolak Pembimbing 2';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_created_sidang = null;
        $skripsi->tgl_disetujui_sidang_pemb1 = null;
        $skripsi->update();

        Alert::error('Ditolak', 'Daftar Sidang Skripsi Ditolak!!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }
    public function approve_sidang_koordinator(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        // $skripsi->status_skripsi = 'SIDANG DIJADWALKAN';
        // $skripsi->jenis_usulan = 'Sidang Skripsi';
        $skripsi->keterangan = 'Menunggu persetujuan Koordinator Program Studi';
        $skripsi->tgl_disetujui_sidang_koordinator = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Daftar Sidang Skripsi Disetujui')->showConfirmButton('Ok', '#28a745');
        return back();

    }
    public function tolak_sidang_koordinator(Request $request, $id)
    {
       $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SIDANG DITOLAK';
        $skripsi->keterangan = 'Ditolak Koordinator Skripsi';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_created_sidang_pemb1 = null;
        $skripsi->tgl_created_sidang_pemb2 = null;
        $skripsi->tgl_created_sidang_admin = null;
        $skripsi->update();

        Alert::error('Ditolak!', 'Daftar Sidang Skripsi ditolak')->showConfirmButton('Ok', '#dc3545');
        return back();
        
    }
   
    public function approve_sidang_kaprodi(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->status_skripsi = 'DAFTAR SIDANG DISETUJUI';
        $skripsi->keterangan = 'Menunggu Jadwal Sidang Skripsi';
        $skripsi->tgl_disetujui_sidang_kaprodi = Carbon::now();
        $skripsi->update();
        
        
        $penjadwalan_sempro = PenjadwalanSempro::where('mahasiswa_nim', $skripsi->mahasiswa_nim )->latest('created_at')->first();

        $penjadwalanSkripsi = new PenjadwalanSkripsi();
        $penjadwalanSkripsi->mahasiswa_nim = $skripsi->mahasiswa_nim;
        $penjadwalanSkripsi->prodi_id = $skripsi->prodi_id;
        $penjadwalanSkripsi->pembimbingsatu_nip = $skripsi->pembimbing_1_nip;
        $penjadwalanSkripsi->pembimbingdua_nip = $skripsi->pembimbing_2_nip;

        $penjadwalanSkripsi->pengujisatu_nip = $penjadwalan_sempro->pengujisatu_nip;
        $penjadwalanSkripsi->pengujidua_nip = $penjadwalan_sempro->pengujidua_nip;
        $penjadwalanSkripsi->pengujitiga_nip = $penjadwalan_sempro->pengujitiga_nip;
        
        $penjadwalanSkripsi->judul_skripsi = $skripsi->judul_skripsi;
        $penjadwalanSkripsi->save();

        Alert::success('Disetujui!', 'Daftar Sidang Skripsi Disetujui')->showConfirmButton('Ok', '#28a745');
        return back();

    }
    public function tolak_sidang_kaprodi(Request $request, $id)
    {
       $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SIDANG DITOLAK';
        $skripsi->keterangan = 'Ditolak Koordinator Program Studi';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_created_sidang_pemb1 = null;
        $skripsi->tgl_created_sidang_pemb2 = null;
        $skripsi->tgl_created_sidang_admin = null;
        $skripsi->tgl_created_sidang_koordinator = null;
        $skripsi->update();

        Alert::error('Ditolak!', 'Daftar Sidang Skripsi ditolak')->showConfirmButton('Ok', '#dc3545');
        return back();
        
    }

    public function approvesidang_admin(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->keterangan = 'Menunggu persetujuan Koordinator Skripsi';
        $skripsi->tgl_disetujui_sidang_admin = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Daftar Sidang Disetujui')->showConfirmButton('Ok', '#28a745');
        return back();

    }

    public function tolaksidang_admin(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SIDANG DITOLAK';
        $skripsi->keterangan = 'Ditolak Admin prodi';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_created_sidang = null;
        $skripsi->update();

        Alert::error('Ditolak!', 'Daftar Sidang Ditolak')->showConfirmButton('Ok', '#dc3545');
        return back();
    
    }
    
    public function approvetunggu_sidang_admin(Request $request, $id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->status_skripsi = 'SIDANG DIJADWALKAN';
        $skripsi->keterangan = 'Sidang Skripsi Dijadwalkan';
        $skripsi->tgl_jadwal_sidang_admin = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui!', 'Daftar Sidang Disetujui')->showConfirmButton('Ok', '#28a745');
        return back();

    }

    public function tolaktunggu_sidang_admin(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SIDANG DITOLAK';
        $skripsi->keterangan = 'Ditolak Admin prodi';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_created_sidang = null;
        $skripsi->update();

        Alert::error('Ditolak!', 'Daftar Sidang Ditolak')->showConfirmButton('Ok', '#dc3545');
        return back();
    
    }

    //APPROVAL SIDANG SELESAI PEMBIMBING
    public function approveselesaisidang_pemb($id)
    {
        $skripsi = PendaftaranSkripsi::find($id);

        $skripsi->status_skripsi = 'SIDANG SELESAI';
        $skripsi->keterangan = 'Sidang Skripsi Selesai';
        $skripsi->tgl_disetujui_sidang = Carbon::now();
        $skripsi->update();

        Alert::success('Disetujui', 'Sidang Skripsi Selesai!')->showConfirmButton('Ok', '#28a745');
        return  back();
    }
    public function tolakselesaisidang_pemb(Request $request, $id)
    {
        $request->validate([                                           
            'alasan' => 'required',
        ]);

        $skripsi = PendaftaranSkripsi::find($id);        
        $skripsi->status_skripsi = 'DAFTAR SIDANG DITOLAK';
        $skripsi->keterangan = 'Tidak Lulus Sidang Skripsi';
        $skripsi->alasan = $request->alasan;
        $skripsi->tgl_created_sidang = null;
        $skripsi->update();

        Alert::error('Tidak Lulus', 'Tidak Lulus Sidang Skripsi!')->showConfirmButton('Ok', '#dc3545');
        return  back();
    }



     public function approveperpanjangan1_pembimbing(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
        //  $skripsi->status_skripsi = 'PERPANJANGAN 1 DISETUJUI';
         $skripsi->keterangan = 'Menunggu persetujuan Koordinator Program Studi';
         $skripsi->tgl_disetujui_perpanjangan1_pemb1 = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Perpanjangan 1 Waktu Skripsi disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
       
     }
     public function tolakperpanjangan1_pembimbing(Request $request, $id)
     {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

         $skripsi = PendaftaranSkripsi::find($id);
         $skripsi->status_skripsi = 'PERPANJANGAN 1 DITOLAK';
         $skripsi->keterangan = 'Ditolak Dosen Pembimbing';
         $skripsi->alasan = $request->alasan;
         $skripsi->update();
 
         Alert::error('Ditolak', 'Perpanjangan 1 Waktu Skripsi ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
     }
     
     public function approveperpanjangan1_kaprodi(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
         $skripsi->status_skripsi = 'PERPANJANGAN 1 DISETUJUI';
         $skripsi->keterangan = 'Perpanjangan 1 Waktu Skripsi Disetujui';
         $skripsi->tgl_disetujui_perpanjangan1_kaprodi = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Perpanjangan 1 Waktu Skripsi disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
       
     }
     public function tolakperpanjangan1_kaprodi(Request $request, $id)
     {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

         $skripsi = PendaftaranSkripsi::find($id);
    
         $skripsi->status_skripsi = 'PERPANJANGAN 1 DITOLAK';
         $skripsi->keterangan = 'Ditolak Koordinator Program Studi';
         $skripsi->tgl_disetujui_perpanjangan1_pemb1 = null;
         $skripsi->alasan = $request->alasan;
         $skripsi->update();
 
         Alert::error('Ditolak', 'Perpanjangan 1 Waktu Skripsi ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
     }



     public function approveperpanjangan2_pembimbing(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
        //  $skripsi->status_skripsi = 'PERPANJANGAN 2 DISETUJUI';
         $skripsi->keterangan = 'Menunggu persetujuan Koordinator Program Studi';
         $skripsi->tgl_disetujui_perpanjangan2_pemb1 = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Perpanjangan 2 Waktu Skripsi disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
       
 
     }
     public function tolakperpanjangan2_pembimbing(Request $request, $id)
     {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

         $skripsi = PendaftaranSkripsi::find($id);
    
         $skripsi->status_skripsi = 'PERPANJANGAN 2 DITOLAK';
         $skripsi->keterangan = 'Ditolak Dosen Pembimbing';
         $skripsi->tgl_created_perpanjangan2 = null;
         $skripsi->tgl_disetujui_perpanjangan2_pemb1 = null;
         $skripsi->alasan = $request->alasan;
         $skripsi->update();
 
         Alert::error('Ditolak', 'Perpanjangan 2 Waktu Skripsi ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
     }
     public function approveperpanjangan2_kaprodi(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
         $skripsi->status_skripsi = 'PERPANJANGAN 2 DISETUJUI';
         $skripsi->keterangan = 'Perpanjangan 2 Waktu Skripsi Disetujui';
         $skripsi->tgl_disetujui_perpanjangan2_kaprodi = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Perpanjangan 2 Waktu Skripsi disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
       
 
     }
     public function tolakperpanjangan2_kaprodi(Request $request, $id)
     {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

         $skripsi = PendaftaranSkripsi::find($id);
    
         $skripsi->status_skripsi = 'PERPANJANGAN 2 DITOLAK';
         $skripsi->keterangan = 'Ditolak Koordinator Program Studi';
         $skripsi->tgl_created_perpanjangan2 = null;
         $skripsi->tgl_disetujui_perpanjangan2_pemb1 = null;
         $skripsi->tgl_disetujui_perpanjangan2_koordinator = null;
         $skripsi->alasan = $request->alasan;
         $skripsi->update();
 
         Alert::error('Ditolak', 'Perpanjangan 2 Waktu Skripsi ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
     }

     
     //PERPANJANGAN REVISI
     public function approveperpanjangan_revisi_pembimbing(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
        //  $skripsi->status_skripsi = 'PERPANJANGAN REVISI DISETUJUI';
         $skripsi->keterangan = 'Menunggu persetujuan Koordinator Program Studi';
         $skripsi->tgl_disetujui_revisi_pemb1 = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Perpanjangan Revisi Skripsi disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
       
 
     }
     public function tolakperpanjangan_revisi_pembimbing(Request $request, $id)
     {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

         $skripsi = PendaftaranSkripsi::find($id);
    
         $skripsi->status_skripsi = 'PERPANJANGAN REVISI DITOLAK';
         $skripsi->keterangan = 'Ditolak Koordinator Program Studi';
        //  $skripsi->tgl_created_perpanjangan2 = null;
         $skripsi->alasan = $request->alasan;
         $skripsi->update();
 
         Alert::error('Ditolak', 'Perpanjangan Revisi Skripsi ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
     }
     public function approveperpanjangan_revisi_kaprodi(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
         $skripsi->status_skripsi = 'PERPANJANGAN REVISI DISETUJUI';
         $skripsi->keterangan = 'Perpanjangan Revisi Skripsi disetujui';
         $skripsi->tgl_disetujui_revisi_kaprodi = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Perpanjangan Revisi Skripsi disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
       
     }
     public function tolakperpanjangan_revisi_kaprodi(Request $request, $id)
     {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

         $skripsi = PendaftaranSkripsi::find($id);
    
         $skripsi->status_skripsi = 'PERPANJANGAN REVISI DITOLAK';
         $skripsi->keterangan = 'Ditolak Koordinator Program Studi';
        //  $skripsi->tgl_created_perpanjangan2 = null;
         $skripsi->alasan = $request->alasan;
          $skripsi->tgl_disetujui_revisi_pemb1 = null;
         $skripsi->update();
 
         Alert::error('Ditolak', 'Perpanjangan Revisi Skripsi ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
     }
     
     public function approvebuku_skripsi_koordinator(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
        //  $skripsi->status_skripsi = 'SKRIPSI SELESAI';
         $skripsi->status_skripsi = 'LULUS';
         $skripsi->keterangan = 'Proses Skripsi Selesai!';
         $skripsi->keterangan = 'Nilai Skripsi Telah Keluar';
         $skripsi->tgl_disetujui_sti_17_koordinator = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Bukti Penyerahan Buku Skripsi disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
       
 
     }
     public function tolakbuku_skripsi_koordinator(Request $request, $id)
     {
         $request->validate([                                           
            'alasan' => 'required',
        ]);

         $skripsi = PendaftaranSkripsi::find($id);
    
         $skripsi->status_skripsi = 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK';
         $skripsi->keterangan = 'Bukti Penyerahan Buku Skripsi ditolak';
        //  $skripsi->tgl_created_perpanjangan2 = null;
         $skripsi->alasan = $request->alasan;
         $skripsi->update();
 
         Alert::error('Ditolak', 'Bukti Penyerahan Buku Skripsi ditolak!')->showConfirmButton('Ok', '#dc3545');
        return  back();
     }

     public function approvelulus_koordinator(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
         $skripsi->status_skripsi = 'LULUS';
         $skripsi->keterangan = 'Nilai Skripsi Telah Keluar';
         $skripsi->tgl_disetujui_sti_17 = Carbon::now();
         $skripsi->update();
 
         Alert::success('Disetujui!', 'Bukti Penyerahan Buku Skripsi disetujui')->showConfirmButton('Ok', '#28a745');
        return back();
 
     }
     
     public function lewat_batas_sidang(Request $request, $id)
     {
         $skripsi = PendaftaranSkripsi::find($id);
 
         $skripsi->status_skripsi = 'USULKAN JUDUL ULANG';
         $skripsi->keterangan = 'Lewat Batas Daftar Sidang Skripsi';
         $skripsi->alasan = 'Anda Lewat Batas';
         $skripsi->update();
 
        //  Alert::success('Berhasil!', 'Mahasiswa dihapus dari Daftar Bimbingan')->showConfirmButton('Ok', '#28a745');
        //  return back();
         return back()->with('message', 'Berhasil! Satu mahasiswa dihapus dari Daftar Bimbingan Anda.');
 
     }



}
