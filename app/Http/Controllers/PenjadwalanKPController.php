<?php

namespace App\Http\Controllers;
use \PDF;
use Carbon\Carbon;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\JamKPKam;
use App\Models\JamKPSel;
use App\Models\Mahasiswa;
use App\Models\Konsentrasi;
use App\Models\PenilaianKP;
use Illuminate\Http\Request;
use App\Models\PenjadwalanKP;
use App\Models\PendaftaranKP;
use App\Models\PenilaianKPPenguji;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PenilaianKPPembimbing;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PenjadwalanKPController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id == 2) {            
            return view('penjadwalankp.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('status_seminar', 0)->where('prodi_id', 1)->get(),
            ]);
        }
        if (auth()->user()->role_id == 3) {            
            return view('penjadwalankp.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('status_seminar', 0)->where('prodi_id', 2)->get(),
            ]);
        }
        if (auth()->user()->role_id == 4) {            
            return view('penjadwalankp.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('status_seminar', 0)->where('prodi_id', 3)->get(),
            ]);
        }
    }

    public function create()
    {
        if (auth()->user()->role_id == 2) {            
            return view('penjadwalankp.create', [    
                'prodis' => Prodi::all(),
                'mahasiswas' => Mahasiswa::where('prodi_id', 1)->get()->sortBy('nama'),
                'dosens' => Dosen::all()->sortBy('nama'),
                // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
                // 'jamkpsels' => JamKPSel::all()->sortBy('id'),
                // 'jamkpkams' => JamKPKam::all()->sortBy('id'),
            ]);
        }        
        if (auth()->user()->role_id == 3) {            
            return view('penjadwalankp.create', [    
                'prodis' => Prodi::all(),
                'mahasiswas' => Mahasiswa::where('prodi_id', 2)->get()->sortBy('nama'),
                'dosens' => Dosen::all()->sortBy('nama'),
                // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
                // 'jamkpsels' => JamKPSel::all()->sortBy('id'),
                // 'jamkpkams' => JamKPKam::all()->sortBy('id'),           
            ]);
        }        
        if (auth()->user()->role_id == 4) {            
            return view('penjadwalankp.create', [    
                'prodis' => Prodi::all(),
                'mahasiswas' => Mahasiswa::where('prodi_id', 3)->get()->sortBy('nama'),
                'dosens' => Dosen::all()->sortBy('nama'), 
                // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
                // 'jamkpsels' => JamKPSel::all()->sortBy('id'),
                // 'jamkpkams' => JamKPKam::all()->sortBy('id'),           
            ]);
        }
    
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_nim' => 'required',
            'pembimbing_nip' => 'required',
            'prodi_id' => 'required',                                                             
            'judul_kp' => 'required',
            'penguji_nip' => 'required',
        ]);

        PenjadwalanKP::create([
            'mahasiswa_nim' => $request->mahasiswa_nim,
            'pembimbing_nip' => $request->pembimbing_nip,                      
            'prodi_id' => $request->prodi_id,            
            'judul_kp' => $request->judul_kp,
            'penguji_nip' => $request->penguji_nip,  
            'lokasi' => $request->lokasi,
            'waktu' => $request->waktu,
            'tanggal' => $request->tanggal,
            'dibuat_oleh' => auth()->user()->username,
        ]);

        return redirect('/form')->with('message', 'Jadwal Berhasil Ditambahkan!');
    }

    public function edit($id)
    {
        $decrypted = Crypt::decryptString($id);
        $kps = PenjadwalanKP::findOrFail($decrypted);

        return view('penjadwalankp.edit', [                      
            'kp' => $kps,
            'kpp' => PendaftaranKP::where('mahasiswa_nim', $kps->mahasiswa_nim )->latest('created_at')->first(),
            'prodis' => Prodi::all(),
            'mahasiswas' => Mahasiswa::all()->sortBy('nama'),
            'dosens' => Dosen::all()->sortBy('nama'), 
            // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
            // 'jamkpsels' => JamKPSel::all()->sortBy('id'),
            // 'jamkpkams' => JamKPKam::all()->sortBy('id'),  
        ]);
    }
    

    public function update(Request $request, PenjadwalanKP $penjadwalan_kp)
    {
        $rules = [
            'mahasiswa_nim' => 'required',
            'pembimbing_nip' => 'required',
            'penguji_nip' => 'required',
            'prodi_id' => 'required',                        
            'judul_kp' => 'required',
            'lokasi' => 'max:255',
            'waktu' => 'max:255',
        ];
               
        $validated = $request->validate($rules);

        if($request->waktu_selasa != null) {
            $request->waktu = $request->waktu_selasa;
        }
        if($request->waktu_kamis != null) {
            $request->waktu = $request->waktu_kamis;
        }
        if(isset($request->ruangan_id)) {
            $validated["lokasi"]= $request->ruangan_id;
        }
        if(isset($request->tanggal)) {
            $validated["tanggal"]= $request->tanggal;
        }
        
        if(auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4){
        $validated["waktu"] = $request->waktu;
        $validated['dibuat_oleh'] = auth()->user()->username;
        PenjadwalanKP::where('id', $penjadwalan_kp->id)
            ->update($validated);
        }
        if(auth()->user()->role_id == 9 || auth()->user()->role_id == 10 || auth()->user()->role_id == 11 ){
        $validated["waktu"] = $request->waktu;
        $validated['dibuat_oleh'] = auth()->user()->nip;
        PenjadwalanKP::where('id', $penjadwalan_kp->id)
            ->update($validated);
        }

        $kp = PendaftaranKP::where('mahasiswa_nim', $penjadwalan_kp->mahasiswa_nim )->latest('created_at')->first();

        $kp->status_kp = 'SEMINAR KP DIJADWALKAN';
        $kp->keterangan = 'Seminar KP Dijadwalkan';
        $kp->tgl_dijadwalkan = Carbon::now();
        $kp->update();

        // return redirect('/form')->with('message', 'Jadwal Berhasil Diubah!');
        Alert::success('Berhasil!', 'Jadwal berhasil diubah')->showConfirmButton('Ok', '#28a745');
        return back();
    }

        public function edit_koordinator($id)
    {
        $decrypted = Crypt::decryptString($id);
        $kps = PenjadwalanKP::findOrFail($decrypted);

        return view('penjadwalankp.edit', [                      
            'kp' => $kps,
            'kpp' => PendaftaranKP::where('mahasiswa_nim', $kps->mahasiswa_nim )->latest('created_at')->first(),
            'prodis' => Prodi::all(),
            'mahasiswas' => Mahasiswa::all()->sortBy('nama'),
            'dosens' => Dosen::all()->sortBy('nama'), 
            // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
            // 'jamkpsels' => JamKPSel::all()->sortBy('id'),
            // 'jamkpkams' => JamKPKam::all()->sortBy('id'),  
        ]);
    }

    public function update_koordinator(Request $request, PenjadwalanKP $penjadwalan_kp)
    {
        $rules = [
            'mahasiswa_nim' => 'required',
            'pembimbing_nip' => 'required',
            'penguji_nip' => 'required',
            'prodi_id' => 'required',                        
            'judul_kp' => 'required',
            'lokasi' => 'max:255',
            'waktu' => 'max:255',
        ];
               
        $validated = $request->validate($rules);

        if($request->waktu_selasa != null) {
            $request->waktu = $request->waktu_selasa;
        }
        if($request->waktu_kamis != null) {
            $request->waktu = $request->waktu_kamis;
        }
        if(isset($request->ruangan_id)) {
            $validated["lokasi"]= $request->ruangan_id;
        }
        if(isset($request->tanggal)) {
            $validated["tanggal"]= $request->tanggal;
        }
        
        if(auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4){
        $validated["waktu"] = $request->waktu;
        $validated['dibuat_oleh'] = auth()->user()->nama;
        PenjadwalanKP::where('id', $penjadwalan_kp->id)
            ->update($validated);
        }
        if(auth()->user()->role_id == 9 || auth()->user()->role_id == 10 || auth()->user()->role_id == 11 ){
        $validated["waktu"] = $request->waktu;
        $validated['dibuat_oleh'] = auth()->user()->nip;
        PenjadwalanKP::where('id', $penjadwalan_kp->id)
            ->update($validated);
        }

        $kp = PendaftaranKP::where('mahasiswa_nim', $penjadwalan_kp->mahasiswa_nim )->latest('created_at')->first();

        $kp->status_kp = 'SEMINAR KP DIJADWALKAN';
        $kp->keterangan = 'Seminar KP Dijadwalkan';
        $kp->tgl_dijadwalkan = Carbon::now();
        $kp->update();

        // return redirect('/form')->with('message', 'Jadwal Berhasil Diubah!');
        Alert::success('Berhasil!', 'Jadwal berhasil diubah')->showConfirmButton('Ok', '#28a745');
        return back();
    }

    public function destroy($id)
    {   
        $decrypted = Crypt::decryptString($id);
        PenjadwalanKP::destroy($decrypted);
        return redirect('/form')->with('message', 'Data Berhasil Dihapus!');
    }

    public function approve($id)
    {
        $jadwal = PenjadwalanKP::find($id);
        $jadwal->status_seminar = 1;
        $jadwal->update();

        $pendaftaran_kp = PendaftaranKP::where('mahasiswa_nim', $jadwal->mahasiswa_nim )->latest('created_at')->first();

        $pendaftaran_kp->status_kp = 'SEMINAR KP SELESAI';
        $pendaftaran_kp->keterangan = 'Seminar KP Selesai';
        $pendaftaran_kp->tgl_selesai_semkp = Carbon::now();
        $pendaftaran_kp->update();

        Alert::success('Berhasil!', 'Seminar Telah Selesai')->showConfirmButton('Ok', '#28a745');
        // return redirect('/kp-skripsi/riwayat-penilaian-kp');
        return back();
    }
    
    public function tolak($id)
    {
        $jadwal = PenjadwalanKP::find($id);
        $jadwal->status_seminar = 1;
        $jadwal->update();

        $pendaftaran_kp = PendaftaranKP::where('mahasiswa_nim', $jadwal->mahasiswa_nim )->latest('created_at')->first();

        $pendaftaran_kp->status_kp = 'USULKAN KP ULANG';
        $pendaftaran_kp->keterangan = 'Anda belum Lulus Seminar KP';
        $pendaftaran_kp->alasan = 'Nilai Seminar belum mencukupi';
        $pendaftaran_kp->update();

        Alert::success('Berhasil!', 'Seminar Telah Selesai')->showConfirmButton('Ok', '#dc3545');
        // return redirect('/kp-skripsi/riwayat-penilaian-kp');
        return back();
    }


    public function riwayat()
    {
        return view('penjadwalankp.riwayat-penjadwalan-kp', [
            'penjadwalan_kps' => PenjadwalanKP::where('status_seminar', 1)->get(),
        ]);
    }

    public function approve_koordinator($id)
    {
        $jadwal = PenjadwalanKP::find($id);        
        $jadwal->status_seminar = 2;
        $jadwal->update();

        return redirect('/persetujuan-koordinator')->with('message', 'Berita Acara Disetujui!');
    }

    public function tolak_koordinator($id)
    {
        $jadwal = PenjadwalanKP::find($id);        
        $jadwal->status_seminar = 0;
        $jadwal->update();

        return redirect('/persetujuan-koordinator')->with('message', 'Berita Acara Ditolak!');
    }

    public function approve_kaprodi($id)
    {
        $jadwal = PenjadwalanKP::find($id);        
        $jadwal->status_seminar = 3;
        $jadwal->update();

        return redirect('/persetujuan-kaprodi')->with('message', 'Berita Acara Disetujui!');
    }

    public function tolak_kaprodi($id)
    {
        $jadwal = PenjadwalanKP::find($id);        
        $jadwal->status_seminar = 0;
        $jadwal->update();

        return redirect('/persetujuan-kaprodi')->with('message', 'Berita Acara Ditolak!');
    }

    public function ceknilaikp($id)
    {
        $decrypted = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanKP::findOrFail($decrypted);        
        $nilaipenguji = PenilaianKPPenguji::where('penjadwalan_kp_id', $decrypted)->where('penguji_nip', $penjadwalan->penguji_nip)->first();

        $nilaipembimbing = PenilaianKPPembimbing::where('penjadwalan_kp_id', $decrypted)->where('pembimbing_nip', $penjadwalan->pembimbing_nip)->first();

        return view('penjadwalankp.cek-nilai-kp', [
            'penjadwalan' => $penjadwalan,
            'nilaipembimbing' => $nilaipembimbing,
            'nilaipenguji' => $nilaipenguji,
        ]);
    }

    public function nilaikp($id)
    {
        $decrypted = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanKP::findOrFail($decrypted);        
        $nilaipenguji = PenilaianKPPenguji::where('penjadwalan_kp_id', $decrypted)->where('penguji_nip', $penjadwalan->penguji_nip)->first();

        $nilaipembimbing = PenilaianKPPembimbing::where('penjadwalan_kp_id', $decrypted)->where('pembimbing_nip', $penjadwalan->pembimbing_nip)->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-kp').'/'. $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalankp.nilai-kp',compact('penjadwalan','qrcode','nilaipenguji','nilaipembimbing', 'pdf'));
        
        return $pdf->stream('KPTI/TE-7 Form Nilai Penguji Seminar KP.pdf', array("Attachment" => false));
        
    }

    public function beritaacarakp($id)
    {
        $decrypted = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanKP::findOrFail($decrypted);    

        $nilaipenguji = PenilaianKPPenguji::where('penjadwalan_kp_id', $decrypted)->where('penguji_nip', $penjadwalan->penguji_nip)->first();

        $nilaipembimbing = PenilaianKPPembimbing::where('penjadwalan_kp_id', $decrypted)->where('pembimbing_nip', $penjadwalan->pembimbing_nip)->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-kp').'/'. $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalankp.beritaacara-kp',compact('penjadwalan','qrcode','nilaipenguji','nilaipembimbing', 'pdf'));
        
        return $pdf->stream('KPTI/TE-8 Berita Acara Seminar Kerja Praktek.pdf', array("Attachment" => false));
    }

    public function perbaikan($id)
    {
        $decrypted = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanKP::findOrFail($decrypted);         
        $penilaianpenguji = PenilaianKPPenguji::where('penjadwalan_kp_id', $decrypted)->where('penguji_nip', auth()->user()->nip)->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-kp').'/'. $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalankp.perbaikan-kp',compact('penjadwalan','qrcode','penilaianpenguji', 'pdf'));
        
        return $pdf->stream('KPTI/TE-9 Lembar Perbaikan Seminar Kerja Praktek.pdf', array("Attachment" => false)); 
        
    }

    public function perbaikanpengujikp($id, $penguji)
    {
        $decrypted = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanKP::findOrFail($decrypted);        
        $penilaianpenguji = PenilaianKPPenguji::where('penjadwalan_kp_id', $decrypted)->where('penguji_nip', $penguji)->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-kp').'/'. $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalankp.perbaikan-kp',compact('penjadwalan','qrcode','penilaianpenguji', 'pdf'));
        
        return $pdf->stream('KPTI/TE-9 Lembar Perbaikan Seminar Kerja Praktek.pdf', array("Attachment" => false));    
    }
}
