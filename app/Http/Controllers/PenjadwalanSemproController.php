<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\Ruangan;
use App\Models\JamSel;
use App\Models\JamKam;
use App\Models\Konsentrasi;
use Illuminate\Http\Request;
use App\Models\PenjadwalanKP;
use \PDF;
use App\Models\PenjadwalanSempro;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Crypt;
use App\Models\PenilaianSemproPenguji;
use App\Models\PenilaianSemproPembimbing;
use App\Models\PendaftaranSkripsi;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use RealRashid\SweetAlert\Facades\Alert;

class PenjadwalanSemproController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id == 2) {
            return view('penjadwalansempro.index', [
                'penjadwalan_sempros' => PenjadwalanSempro::where('status_seminar', 0)->where('prodi_id', 1)->get(),
            ]);
        }
        if (auth()->user()->role_id == 3) {
            return view('penjadwalansempro.index', [
                'penjadwalan_sempros' => PenjadwalanSempro::where('status_seminar', 0)->where('prodi_id', 2)->get(),
            ]);
        }
        if (auth()->user()->role_id == 4) {
            return view('penjadwalansempro.index', [
                'penjadwalan_sempros' => PenjadwalanSempro::where('status_seminar', 0)->where('prodi_id', 3)->get(),
            ]);
        }
    }

    public function create()
    {
        if (auth()->user()->role_id == 2) {
            return view('penjadwalansempro.create', [
                'prodis' => Prodi::all(),
                'mahasiswas' => Mahasiswa::where('prodi_id', 1)->get()->sortBy('nama'),
                'dosens' => Dosen::all(),
                // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
                // 'jamsels' => JamSel::all()->sortBy('id'),
                // 'jamkams' => JamKam::all()->sortBy('id'),                
            ]);
        }
        if (auth()->user()->role_id == 3) {
            return view('penjadwalansempro.create', [
                'prodis' => Prodi::all(),
                'mahasiswas' => Mahasiswa::where('prodi_id', 2)->get()->sortBy('nama'),
                'dosens' => Dosen::all()->sortBy('nama'),
                // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
                // 'jamsels' => JamSel::all()->sortBy('id'),
                // 'jamkams' => JamKam::all()->sortBy('id'),                       
            ]);
        }
        if (auth()->user()->role_id == 4) {
            return view('penjadwalansempro.create', [
                'prodis' => Prodi::all(),
                'mahasiswas' => Mahasiswa::where('prodi_id', 3)->get()->sortBy('nama'),
                'dosens' => Dosen::all()->sortBy('nama'),
                // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'), 
                // 'jamsels' => JamSel::all()->sortBy('id'),
                // 'jamkams' => JamKam::all()->sortBy('id'),                    
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'mahasiswa_nim' => 'required',
                'pembimbingsatu_nip' => 'required',
                'prodi_id' => 'required',
                'judul_proposal' => 'required',
                'pengujisatu_nip' => 'required',
            ]
        );

        PenjadwalanSempro::create([
            'mahasiswa_nim' => $request['mahasiswa_nim'],
            'pembimbingsatu_nip' => $request['pembimbingsatu_nip'],
            'pembimbingdua_nip' => $request['pembimbingdua_nip'],
            'pengujisatu_nip' => $request['pengujisatu_nip'],
            'pengujidua_nip' => $request['pengujidua_nip'],
            'pengujitiga_nip' => $request['pengujitiga_nip'],
            'prodi_id' => $request['prodi_id'],
            'judul_proposal' => $request['judul_proposal'],
            'tanggal' => $request['tanggal'],
            'waktu' => $request['waktu'],
            'lokasi' => $request['lokasi'],
            'dibuat_oleh' => auth()->user()->username,
        ]);

        return redirect('/form')->with('message', 'Jadwal Berhasil Ditambahkan!');
    }


    public function edit($id)
    {
        $decrypted = Crypt::decryptString($id);
        $sempro = PenjadwalanSempro::findOrFail($decrypted);

        return view('penjadwalansempro.edit', [
            'sempro' => $sempro,
            'semprop' => PendaftaranSkripsi::where('mahasiswa_nim', $sempro->mahasiswa_nim)->latest('created_at')->first(),
            'prodis' => Prodi::all(),
            'mahasiswas' => Mahasiswa::all()->sortBy('nama'),
            'dosens' => Dosen::all()->sortBy('nama'),
            // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
            // 'jamsels' => JamSel::all()->sortBy('id'),
            // 'jamkams' => JamKam::all()->sortBy('id'),
        ]);
    }

    public function update(Request $request, PenjadwalanSempro $penjadwalan_sempro, PendaftaranSkripsi $pendaftaranid)
    {
        $rules = [
            'mahasiswa_nim' => 'required',
            'pembimbingsatu_nip' => 'nullable',
            'pengujisatu_nip' => 'required',
            'pengujidua_nip' => 'required',
            // 'pengujitiga_nip' => 'required',         
            'prodi_id' => 'required',
            'judul_proposal' => 'required',
        ];

        if ($request->pembimbingdua_nip) {
            if ($penjadwalan_sempro->pembimbingdua_nip != $request->pembimbingdua_nip) {
                $rules['pembimbingdua_nip'] = 'required';
            }
        }

        if ($request->pengujitiga_nip) {
            if ($penjadwalan_sempro->pengujitiga_nip != $request->pengujitiga_nip) {
                $rules['pengujitiga_nip'] = 'required';
            }
        }

        $validated = $request->validate($rules);
        $validated['dibuat_oleh'] = auth()->user()->username;

        $edit = PenjadwalanSempro::find($penjadwalan_sempro->id);
        $edit->mahasiswa_nim = $validated['mahasiswa_nim'];
        $edit->pembimbingsatu_nip = $validated['pembimbingsatu_nip'];

        if ($request->pembimbingdua_nip) {
            if ($penjadwalan_sempro->pembimbingdua_nip != $request->pembimbingdua_nip) {
                if ($request->pembimbingdua_nip == 1) {
                    $edit->pembimbingdua_nip = null;
                } else {
                    $edit->pembimbingdua_nip = $validated['pembimbingdua_nip'];
                }
            }
        }

        $edit->pengujisatu_nip = $validated['pengujisatu_nip'];
        $edit->pengujidua_nip = $validated['pengujidua_nip'];
        $edit->pengujitiga_nip = $request['pengujitiga_nip'];

        if ($request->waktu_selasa != null) {
            $request->waktu = $request->waktu_selasa;
        }
        if ($request->waktu_kamis != null) {
            $request->waktu = $request->waktu_kamis;
        }
        if (isset($request->lokasi)) {
            $edit->lokasi = $request->lokasi;
        }
        if (isset($request->tanggal)) {
            $edit->tanggal = $request->tanggal;
        }
        $edit->prodi_id = $validated['prodi_id'];
        $edit->judul_proposal = $validated['judul_proposal'];
        $edit->dibuat_oleh = $validated['dibuat_oleh'];
        $edit->waktu = $request->waktu;
        $edit->update();


        $pendaftaran_skripsi = PendaftaranSkripsi::where('mahasiswa_nim', $edit->mahasiswa_nim)->latest('created_at')->first();

        $pendaftaran_skripsi->status_skripsi = 'SEMPRO DIJADWALKAN';
        $pendaftaran_skripsi->jenis_usulan = 'Seminar Proposal';
        $pendaftaran_skripsi->keterangan = 'Seminar Proposal Dijadwalkan';
        $pendaftaran_skripsi->tgl_disetujui_jadwalsempro = Carbon::now();
        $pendaftaran_skripsi->update();

        // return redirect('/form')->with('message', 'Jadwal Berhasil Diubah!');
        Alert::success('Berhasil!', 'Jadwal Berhasil Diubah!')->showConfirmButton('Ok', '#28a745');
        return back();
    }
    public function edit_koordinator($id)
    {
        $decrypted = Crypt::decryptString($id);
        $sempro = PenjadwalanSempro::findOrFail($decrypted);

        return view('penjadwalansempro.edit', [
            'sempro' => $sempro,
            'semprop' => PendaftaranSkripsi::where('mahasiswa_nim', $sempro->mahasiswa_nim)->latest('created_at')->first(),
            'prodis' => Prodi::all(),
            'mahasiswas' => Mahasiswa::all()->sortBy('nama'),
            'dosens' => Dosen::all()->sortBy('nama'),
            // 'ruangans' => Ruangan::all()->sortBy('nama_ruangan'),
            // 'jamsels' => JamSel::all()->sortBy('id'),
            // 'jamkams' => JamKam::all()->sortBy('id'),
        ]);
    }

    public function update_koordinator(Request $request, PenjadwalanSempro $penjadwalan_sempro, PendaftaranSkripsi $pendaftaranid)
    {
        $rules = [
            'mahasiswa_nim' => 'required',
            'pembimbingsatu_nip' => 'required',
            'pengujisatu_nip' => 'required',
            'pengujidua_nip' => 'required',
            'pengujitiga_nip' => 'required',
            'prodi_id' => 'required',
            'judul_proposal' => 'required',
        ];

        if ($request->pembimbingdua_nip) {
            if ($penjadwalan_sempro->pembimbingdua_nip != $request->pembimbingdua_nip) {
                $rules['pembimbingdua_nip'] = 'required';
            }
        }

        if ($request->pengujitiga_nip) {
            if ($penjadwalan_sempro->pengujitiga_nip != $request->pengujitiga_nip) {
                $rules['pengujitiga_nip'] = 'required';
            }
        }

        $validated = $request->validate($rules);
        $validated['dibuat_oleh'] = auth()->user()->nama;

        $edit = PenjadwalanSempro::find($penjadwalan_sempro->id);
        $edit->mahasiswa_nim = $validated['mahasiswa_nim'];
        $edit->pembimbingsatu_nip = $validated['pembimbingsatu_nip'];

        if ($request->pembimbingdua_nip) {
            if ($penjadwalan_sempro->pembimbingdua_nip != $request->pembimbingdua_nip) {
                if ($request->pembimbingdua_nip == 1) {
                    $edit->pembimbingdua_nip = null;
                } else {
                    $edit->pembimbingdua_nip = $validated['pembimbingdua_nip'];
                }
            }
        }

        $edit->pengujisatu_nip = $validated['pengujisatu_nip'];
        $edit->pengujidua_nip = $validated['pengujidua_nip'];
        $edit->pengujitiga_nip = $validated['pengujitiga_nip'];

        if ($request->waktu_selasa != null) {
            $request->waktu = $request->waktu_selasa;
        }
        if ($request->waktu_kamis != null) {
            $request->waktu = $request->waktu_kamis;
        }
        if (isset($request->lokasi)) {
            $edit->lokasi = $request->lokasi;
        }
        if (isset($request->tanggal)) {
            $edit->tanggal = $request->tanggal;
        }
        $edit->prodi_id = $validated['prodi_id'];
        $edit->judul_proposal = $validated['judul_proposal'];
        $edit->dibuat_oleh = $validated['dibuat_oleh'];
        $edit->waktu = $request->waktu;
        $edit->update();


        $pendaftaran_skripsi = PendaftaranSkripsi::where('mahasiswa_nim', $edit->mahasiswa_nim)->latest('created_at')->first();

        $pendaftaran_skripsi->status_skripsi = 'SEMPRO DIJADWALKAN';
        $pendaftaran_skripsi->jenis_usulan = 'Seminar Proposal';
        $pendaftaran_skripsi->keterangan = 'Seminar Proposal Dijadwalkan';
        $pendaftaran_skripsi->tgl_disetujui_jadwalsempro = Carbon::now();
        $pendaftaran_skripsi->update();

        // return redirect('/form')->with('message', 'Jadwal Berhasil Diubah!');
        Alert::success('Berhasil!', 'Jadwal Berhasil Diubah!')->showConfirmButton('Ok', '#28a745');
        return back();
    }

    public function destroy($id)
    {
        $decrypted = Crypt::decryptString($id);
        PenjadwalanSempro::destroy($decrypted);
        return redirect('/form')->with('message', 'Data Berhasil Dihapus!');
    }

    public function ceknilai($id)
    {

        $id = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanSempro::find($id);
        $pembimbing = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->get();
        $nilaipenguji1 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujisatu_nip)->first();

        $nilaipenguji2 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujidua_nip)->first();

        $nilaipenguji3 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujitiga_nip)->first();

        if ($pembimbing->count() > 1) {
            $pembimbingnilai = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->get();
        } else {
            $pembimbingnilai = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $penjadwalan->id)->first();
        }

        $nilaipembimbing1 = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingsatu_nip)->first();

        if ($penjadwalan->pembimbingdua_nip == null) {
            return view('penjadwalansempro.cek-nilai', [
                'pembimbing' => $pembimbing,
                'pembimbingnilai' => $pembimbingnilai,
                'penjadwalan' => $penjadwalan,
                'nilaipenguji1' => $nilaipenguji1,
                'nilaipenguji2' => $nilaipenguji2,
                'nilaipenguji3' => $nilaipenguji3,
                'nilaipembimbing1' => $nilaipembimbing1,
            ]);
        } else {
            $nilaipembimbing2 = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingdua_nip)->first();

            return view('penjadwalansempro.cek-nilai', [
                'pembimbing' => $pembimbing,
                'pembimbingnilai' => $pembimbingnilai,
                'penjadwalan' => $penjadwalan,
                'nilaipenguji1' => $nilaipenguji1,
                'nilaipenguji2' => $nilaipenguji2,
                'nilaipenguji3' => $nilaipenguji3,
                'nilaipembimbing1' => $nilaipembimbing1,
                'nilaipembimbing2' => $nilaipembimbing2,
            ]);
        }
    }

    public function approve($id, PendaftaranSkripsi $pendaftaranid)
    {
        $jadwal = PenjadwalanSempro::find($id);

        $jadwal->status_seminar = 1;
        $jadwal->save();

        // $pendaftaran_skripsi = PendaftaranSkripsi::whereNotNull('mahasiswa_nim',  PenjadwalanSempro::find($mahasiswa_nim) )->latest('created_at')->first();

        $pendaftaran_skripsi = PendaftaranSkripsi::where('mahasiswa_nim', $jadwal->mahasiswa_nim)->latest('created_at')->first();

        $pendaftaran_skripsi->status_skripsi = 'SEMPRO SELESAI';
        $pendaftaran_skripsi->keterangan = 'Seminar Proposal Selesai';
        $pendaftaran_skripsi->tgl_semproselesai = Carbon::now();
        $pendaftaran_skripsi->save();

        Alert::success('Berhasil!', 'Seminar Telah Selesai')->showConfirmButton('Ok', '#28a745');
        return back();
    }

    public function gagal($id, PendaftaranSkripsi $pendaftaranid)
    {
        $jadwal = PenjadwalanSempro::find($id);

        $jadwal->status_seminar = 1;
        $jadwal->save();

        // $pendaftaran_skripsi = PendaftaranSkripsi::whereNotNull('mahasiswa_nim',  PenjadwalanSempro::find($mahasiswa_nim) )->latest('created_at')->first();

        $pendaftaran_skripsi = PendaftaranSkripsi::where('mahasiswa_nim', $jadwal->mahasiswa_nim)->latest('created_at')->first();

        $pendaftaran_skripsi->status_skripsi = 'DAFTAR SEMPRO ULANG';
        $pendaftaran_skripsi->keterangan = 'Belum Lulus Seminar Proposal';
        $pendaftaran_skripsi->tgl_semproselesai = Carbon::now();
        $pendaftaran_skripsi->alasan = 'Anda belum lulus Seminar Proposal';
        $pendaftaran_skripsi->save();

        Alert::success('Berhasil!', 'Seminar telah selesai')->showConfirmButton('Ok', '#dc3545');
        return back();
    }


    public function approve_koordinator($id)
    {
        $jadwal = PenjadwalanSempro::find($id);
        $jadwal->status_seminar = 2;
        $jadwal->update();

        return redirect('/persetujuan-koordinator')->with('message', 'Berita Acara Disetujui!');
    }

    public function tolak_koordinator($id)
    {
        $jadwal = PenjadwalanSempro::find($id);
        $jadwal->status_seminar = 0;
        $jadwal->update();

        return redirect('/persetujuan-koordinator')->with('message', 'Berita Acara Ditolak!');
    }

    public function approve_kaprodi($id)
    {
        $jadwal = PenjadwalanSempro::find($id);
        $jadwal->status_seminar = 3;
        $jadwal->update();

        return redirect('/persetujuan-kaprodi')->with('message', 'Berita Acara Disetujui!');
    }

    public function tolak_kaprodi($id)
    {
        $jadwal = PenjadwalanSempro::find($id);
        $jadwal->status_seminar = 0;
        $jadwal->update();

        return redirect('/persetujuan-kaprodi')->with('message', 'Berita Acara Ditolak!');
    }

    public function riwayat()
    {
        return view('penjadwalansempro.riwayat-penjadwalan-sempro', [
            'penjadwalan_sempros' => PenjadwalanSempro::where('status_seminar', 1)->get(),
        ]);
    }

    public function nilaisempro($id)
    {
        $id = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanSempro::find($id);
        $cek = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', auth()->user()->nip)->count();

        if ($cek != 0) {
            $penilaianpembimbing = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', auth()->user()->nip)->first();

            $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            $pdf->loadView('penjadwalansempro.nilai-sempro-pembimbing', compact('penjadwalan', 'qrcode', 'penilaianpembimbing', 'pdf'));

            return $pdf->stream('STI/TE-6 Form Nilai Pembimbing Seminar Proposal Skripsi.pdf', array("Attachment" => false));
        } else {
            $penilaianpenguji = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', auth()->user()->nip)->first();

            $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            $pdf->loadView('penjadwalansempro.nilai-sempro-penguji', compact('penjadwalan', 'qrcode', 'penilaianpenguji', 'pdf'));

            return $pdf->stream('STI/TE-5 Form Nilai Penguji Seminar Proposal Skripsi.pdf', array("Attachment" => false));
        }
    }

    public function nilaipembimbing($id, $pembimbing)
    {
        $id = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanSempro::find($id);
        $penilaianpembimbing = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', $pembimbing)->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalansempro.nilai-sempro-pembimbing', compact('penjadwalan', 'qrcode', 'penilaianpembimbing', 'pdf'));

        return $pdf->stream('STI/TE-6 Form Nilai Pembimbing Seminar Proposal Skripsi.pdf', array("Attachment" => false));
    }

    public function nilaipenguji($id, $penguji)
    {
        $id = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanSempro::find($id);
        $penilaianpenguji = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penguji)->first();
        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalansempro.nilai-sempro-penguji', compact('penjadwalan', 'qrcode', 'penilaianpenguji', 'pdf'));

        return $pdf->stream('STI/TE-5 Form Nilai Penguji Seminar Proposal Skripsi.pdf', array("Attachment" => false));
    }

    public function perbaikan($id)
    {
        $id = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanSempro::find($id);
        $penilaianpenguji = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', auth()->user()->nip)->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalansempro.perbaikan-sempro', compact('penjadwalan', 'qrcode', 'penilaianpenguji', 'pdf'));

        return $pdf->stream('STI/TE-9 Lembar Kontrol Perbaikan Proposal Skripsi.pdf', array("Attachment" => false));
    }

    public function perbaikanpengujisempro($id, $penguji)
    {
        $id = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanSempro::find($id);
        $penilaianpenguji = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penguji)->first();

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalansempro.perbaikan-sempro', compact('penjadwalan', 'qrcode', 'penilaianpenguji', 'pdf'));

        return $pdf->stream('STI/TE-9 Lembar Kontrol Perbaikan Proposal Skripsi.pdf', array("Attachment" => false));
    }

    public function revisiproposal(Request $request, $id)
    {
        $penjadwalan_sempro = PenjadwalanSempro::find($id);
        $penjadwalan_sempro->revisi_proposal = $request->revisi_proposal;
        $penjadwalan_sempro->update();
        $cari_penguji = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', auth()->user()->nip)->count();
        if ($cari_penguji == 0) {
            Alert::success('Berhasil', 'Nilai Berhasil Diubah!')->showConfirmButton('Ok', '#28a745');
            return redirect('/penilaian-sempro/create/' . Crypt::encryptString($id));
        } else {

            // return redirect('/penilaian-sempro/edit/' . Crypt::encryptString($id))->with('message', 'Judul Berhasil Diubah!');
            Alert::success('Berhasil', 'Nilai Berhasil Diubah!')->showConfirmButton('Ok', '#28a745');
            return redirect('/penilaian-sempro/edit/' . Crypt::encryptString($id));
        }
    }

    public function catatansempro(Request $request, $id)
    {

        $penjadwalan_sempro = PenjadwalanSempro::find($id);
        $penjadwalan_sempro->catatan1 = $request->catatan1;
        $penjadwalan_sempro->catatan2 = $request->catatan2;
        $penjadwalan_sempro->catatan3 = $request->catatan3;
        $penjadwalan_sempro->update();
        $cari_penguji = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', auth()->user()->nip)->count();
        if ($cari_penguji == 0) {
            return redirect('/penilaian-sempro/create/' . Crypt::encryptString($id))->with('message', 'Catatan Berhasil ditambah!');
        } else {
            return redirect('/penilaian-sempro/edit/' . Crypt::encryptString($id))->with('message', 'Catatan Berhasil ditambah!');
        }
    }

    public function riwayatjudul($id)
    {
        $id = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanSempro::find($id);

        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $pdf->loadView('penjadwalansempro.riwayat-judul', compact('penjadwalan', 'qrcode', 'pdf'));

        return $pdf->stream('STI/TE-8 Lembar Penggantian Judul.pdf', array("Attachment" => false));
    }

    public function beritaacarasempro($id)
    {
        $id = Crypt::decryptString($id);
        $penjadwalan = PenjadwalanSempro::find($id);
        $pembimbing = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->get();
        $nilaipenguji1 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujisatu_nip)->first();

        $nilaipenguji2 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujidua_nip)->first();

        $nilaipenguji3 = PenilaianSemproPenguji::where('penjadwalan_sempro_id', $id)->where('penguji_nip', $penjadwalan->pengujitiga_nip)->first();

        if ($pembimbing->count() > 1) {
            $pembimbingnilai = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->get();
        } else {
            $pembimbingnilai = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $penjadwalan->id)->first();
        }

        $nilaipembimbing1 = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingsatu_nip)->first();

        if ($penjadwalan->pembimbingdua_nip == null) {

            $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            $pdf->loadView('penjadwalansempro.beritaacara-sempro', compact('penjadwalan', 'qrcode', 'pdf', 'pembimbing', 'pembimbingnilai', 'nilaipenguji1', 'nilaipenguji2', 'nilaipenguji3', 'nilaipembimbing1'));

            return $pdf->stream('STI/TE-7 Berita Acara Seminar Proposal Skripsi.pdf', array("Attachment" => false));
        } else {
            $nilaipembimbing2 = PenilaianSemproPembimbing::where('penjadwalan_sempro_id', $id)->where('pembimbing_nip', $penjadwalan->pembimbingdua_nip)->first();

            $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/detail-sempro') . '/' . $penjadwalan->id));
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            $pdf->loadView('penjadwalansempro.beritaacara-sempro', compact('penjadwalan', 'qrcode', 'pdf', 'pembimbing', 'pembimbingnilai', 'nilaipenguji1', 'nilaipenguji2', 'nilaipenguji3', 'nilaipembimbing1', 'nilaipembimbing2'));

            return $pdf->stream('STI/TE-7 Berita Acara Seminar Proposal Skripsi.pdf', array("Attachment" => false));
        }
    }
}
