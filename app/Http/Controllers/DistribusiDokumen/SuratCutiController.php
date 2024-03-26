<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\SuratCuti;
use App\Models\Dosen;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SuratCutiController extends Controller
{
    private $jenis_cuti = ['tahunan', 'besar', 'sakit', 'melahirkan', 'kepentingan', 'diluar tanggungan negara'];

    public function create(Request $request)
    {
        $jenis_user = $request->jenis_user;
        $jenis_cuti = $this->jenis_cuti;
        $jenis_user = $request->jenis_user;

        $jabatan = null;
        $role = Role::where('id', Auth::guard($jenis_user == 'admin' || $jenis_user == "plp"  ? 'web' : $request->jenis_user)->user()->role_id)->first();
        if ($role) {
            $jabatan = $role->role_akses;
        }
        $kajur = Role::where("role.id", 5)
            ->rightJoin("dosen", "role.id", "=", "dosen.role_id")
            ->select(
                "dosen.nama as nama",
                "dosen.nip as nip"
            )->first();
        return view("doc.suratcuti.create", compact('jenis_cuti', 'jenis_user', 'jabatan', 'kajur'));
    }

    public function store(Request $request)
    {
        // Validiasi
        $request->validate([
            "jenis_cuti" => "required",
            "alasan_cuti" => "required",
            "mulai_cuti" => "required|date",
            "selesai_cuti" => "required|date|after:mulai_cuti",
            "alamat_cuti" => "required",
            "lampiran" => "file|max:5120",
            "tanda_tangan" => "required|image|mimes:png|max:256",
        ], [
            'jenis_cuti.required' => 'Jenis cuti harus diisi.',
            'alasan_cuti.required' => 'Alasan cuti harus diisi.',
            'mulai_cuti.required' => 'Tanggal mulai cuti harus diisi.',
            'selesai_cuti.required' => 'Tanggal selesai cuti harus diisi.',
            'selesai_cuti.after' => 'Tanggal selesai cuti harus melebihi tanggal mulai cuti',
            'alamat_cuti.required' => 'Alamat selama cuti harus diisi.',
            'lampiran.max' => 'Lampiran tidak dapat lebih dari 5MB',
            'tanda_tangan.max' => "Ukuran gambar terlalu besar, max: 256KB",
            "tanda_tangan.mimes" => "Gambar harus PNG",
            "tanda_tangan.max" => "Maksimal 256KB",
        ]);

        $mulai_cuti = Carbon::parse($request->mulai_cuti);
        $selesai_cuti = Carbon::parse($request->selesai_cuti);

        $lampiran = $request->file("lampiran");
        $tanda_tangan = $request->file("tanda_tangan");

        if ($lampiran) {
            SuratCuti::create([
                'jenis_user' => $request->jenis_user,
                'user_created' => $request->user_id,
                "jenis_cuti" => $request->jenis_cuti,
                "alasan_cuti" => $request->alasan_cuti,
                "nomor_telepon" => $request->nomor_telepon,
                "mulai_cuti" => $mulai_cuti,
                "selesai_cuti" => $selesai_cuti,
                "alamat_cuti" => $request->alamat_cuti,
                "lama_cuti" => $mulai_cuti->diffInDays($selesai_cuti),
                "url_lampiran" => $request->url_lampiran,
                "url_lampiran_lokal" => str_replace('public/', '', $lampiran->store('public/suratcuti')),
                "tanda_tangan" => str_replace('public/', '', $tanda_tangan->store('public/ttd')),
            ]);
        } else {
            SuratCuti::create([
                'jenis_user' => $request->jenis_user,
                'user_created' => $request->user_id,
                "jenis_cuti" => $request->jenis_cuti,
                "alasan_cuti" => $request->alasan_cuti,
                "nomor_telepon" => $request->nomor_telepon,
                "mulai_cuti" => $mulai_cuti,
                "selesai_cuti" => $selesai_cuti,
                "alamat_cuti" => $request->alamat_cuti,
                "lama_cuti" => $mulai_cuti->diffInDays($selesai_cuti),
                "url_lampiran" => $request->url_lampiran,
                "tanda_tangan" => str_replace('public/', '', $tanda_tangan->store('public/ttd')),
            ]);
        }
        Alert::success('Berhasil!', 'Berhasil mengajukan Surat Cuti')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("doc.index");
    }
    public function detailForPublic($encryptId)
    {
        $id = decrypt($encryptId);
        $data = SuratCuti::findOrFail($id);
        $userRole = null;
        $userId = null;
        return view('doc.suratcuti.detail', compact('data', 'userRole', 'userId'));
    }

    public function detail($id, Request $request)
    {
        $jenisUser = $request->jenis_user;
        $userId = $request->user_id;
        if ($jenisUser == "mahasiswa") abort(403);

        if ($jenisUser == "dosen") {
            $userRole = optional(Dosen::where("nip", $userId)->first())->role_id;
        } else {
            $userRole = User::where("username", $userId)->first()->role_id;
        }

        $data = SuratCuti::find($id);
        return view('doc.suratcuti.detail', compact('data', 'userRole', 'userId'));
    }

    public function edit($id, Request $request)
    {
        $jenis_cuti = $this->jenis_cuti;
        $jenis_user = $request->jenis_user;
        $suratCuti = SuratCuti::find($id);
        $jabatan = $request->jenis_user;
        $role = Role::where('id', Auth::guard($request->jenis_user == 'admin' ? 'web' : $request->jenis_user)->user()->role_id)->first();
        if ($role) {
            $jabatan = $role->role_akses;
        }
        return view('doc.suratcuti.edit', compact('suratCuti', 'jenis_user', 'jenis_cuti', 'jabatan'));
    }

    public function update($id, Request $request)
    {
        // Validiasi
        $request->validate([
            "jenis_cuti" => "required",
            "alasan_cuti" => "required",
            "mulai_cuti" => "required|date",
            "selesai_cuti" => "required|date|after:mulai_cuti",
            "alamat_cuti" => "required",
            "lampiran" => "file|max:5120",
            "tanda_tangan" => "image|mimes:png|max:256"
        ], [
            'jenis_cuti.required' => 'Jenis cuti harus diisi.',
            'alasan_cuti.required' => 'Alasan cuti harus diisi.',
            'mulai_cuti.required' => 'Tanggal mulai cuti harus diisi.',
            'selesai_cuti.required' => 'Tanggal selesai cuti harus diisi.',
            'selesai_cuti.after' => 'Tanggal selesai cuti harus melebihi tanggal mulai cuti',
            'alamat_cuti.required' => 'Alamat selama cuti harus diisi.',
            'lampiran.max' => 'Lampiran tidak dapat lebih dari 5MB',
            "tanda_tangan.mimes" => "Gambar harus PNG",
            "tanda_tangan.max" => "Maksimal 256KB",
        ]);
        $mulai_cuti = Carbon::parse($request->mulai_cuti);
        $selesai_cuti = Carbon::parse($request->selesai_cuti);

        $suratCuti = SuratCuti::find($id);
        $suratCuti->jenis_cuti = $request->jenis_cuti;
        $suratCuti->alasan_cuti = $request->alasan_cuti;
        $suratCuti->mulai_cuti = $mulai_cuti;
        $suratCuti->selesai_cuti = $selesai_cuti;
        $suratCuti->alamat_cuti = $request->alamat_cuti;
        $suratCuti->url_lampiran = $request->url_lampiran;
        $suratCuti->nomor_telepon = $request->nomor_telepon;
        $suratCuti->lama_cuti = $mulai_cuti->diffInDays($selesai_cuti);
        $tanda_tangan = $request->file("tanda_tangan");
        if ($tanda_tangan) {
            Storage::delete("public/" . $suratCuti->tanda_tangan);
            $suratCuti->tanda_tangan = str_replace('public/', '', $tanda_tangan->store('public/ttd'));
        }

        $lampiran = $request->file("lampiran");
        if ($lampiran) {
            Storage::delete("public/" . $suratCuti->url_lampiran_lokal);
            $suratCuti->url_lampiran_lokal = str_replace('public/', '', $lampiran->store('public/suratcuti'));
        }
        $suratCuti->update();
        Alert::success('Berhasil!', 'Berhasil mengubah data pengajuan Surat Cuti')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }

    public function destroy($id)
    {
        // Hapus Data Surat Cuti
        $data = SuratCuti::findOrFail($id);
        // Hapus file dari storage
        Storage::delete("public/" . $data->url_lampiran_lokal);
        $data->delete();
        Alert::success('Berhasil!', 'Berhasil menghapus Surat Cuti')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.arsip');
    }

    public function accAdmin($id, Request $request)
    {
        $suratCuti = SuratCuti::where("id", $id)->first();
        $suratCuti->status = "proses";
        $suratCuti->nama_penandatangan_akhir =  $request->nama_penandatangan_akhir;
        $suratCuti->jabatan_penandatangan_akhir =  $request->jabatan_penandatangan_akhir;
        $suratCuti->nip_penandatangan_akhir =  $request->nip_penandatangan_akhir;

        $suratCuti->update();
        Alert::success('Berhasil!', 'Surat Cuti Diteruskan Ke Ketua Jurusan')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }
    public function approve($id)
    {
        $suratCuti = SuratCuti::where("id", $id)->first();
        $suratCuti->status = "diterima";
        $suratCuti->update();
        Alert::success('Berhasil!', 'Surat Cuti Disetujui')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }
    public function reject($id, Request $request)
    {
        $suratCuti = SuratCuti::findOrFail($id);
        $user = Auth::guard($request->jenis_user == "admin" ? "web" : "dosen")->user();

        $suratCuti->status = "ditolak";
        $suratCuti->alasan_ditolak = $request->alasan_ditolak;
        $suratCuti->role_rejected = $user->role_id;
        $suratCuti->update();
        Alert::success('Berhasil!', 'Surat Cuti Ditolak')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }

    public function download($id, Request $request)
    {
        $data = SuratCuti::with("dosen.prodi")->where("id", $id)->where("status", "diterima")->first();
        $masa_kerja = "";
        if ($request->jenis_user == "dosen") {
            $masa_kerja = $this->generateDuration($data->dosen->nip);
        }

        if (!$data) return abort(404);
        $kajur = Role::where("role.id", 5)
            ->rightJoin("dosen", "role.id", "=", "dosen.role_id")
            ->select(
                "dosen.nama as nama",
                "dosen.nip as nip"
            )->first();
        // $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/sertifikat') . '/' . $slug));
        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper("a4", 'potrait');
        $pdf->loadView("doc.pdf.suratcuti", compact('data', 'kajur', 'masa_kerja'));
        return $pdf->download("Surat Cuti " . data_get($data, $data->jenis_user . ".nama") . '.pdf');
    }

    function generateDuration($nip)
    {
        $startYear = substr($nip, 8, 4);
        $startMonth = substr($nip, 12, 2);
        $startDate = Carbon::createFromDate($startYear, $startMonth, 1);
        $now = Carbon::now();

        $interval = $startDate->diff($now);

        $result = $interval->format('%y tahun %m bulan');

        return $result;
    }
}
