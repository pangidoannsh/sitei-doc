<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Surat;
use App\Models\Semester;
use App\Models\Dosen;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function create(Request $request)
    {
        $jenisUser = $request->jenis_user;
        $prodiId = Auth::guard($jenisUser == "admin" || $jenisUser == "plp"  ? "web" : $request->jenis_user)->user()->prodi_id;

        $dosens = Dosen::where("role_id", 5);
        switch ($prodiId) {
            case 1:
                $dosens->orWhere("role_id", 6);
                break;
            case 2:
                $dosens->orWhere("role_id", 7);
                break;
            case 3:
                $dosens->orWhere("role_id", 8);
                break;

            default:
        }
        $dosens = $dosens->get();
        $semester = Semester::getSimpleSemester()->last();
        return view("doc.surat.create", compact("dosens", "semester"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "nama" => "required",
            "tujuan_surat" => "required"
        ], [
            "nama.required" => "Nama Surat harus diisi",
            "tujuan_surat.required" => "Pilih akan ditujuan ke siapa surat yang diajukan"
        ]);
        $jenisUser = $request->jenis_user;
        $user = Auth::guard($jenisUser == "admin" || $jenisUser == "plp" ? 'web' : $jenisUser)->user();

        $prodiUser = $user->prodi_id ?? 0;
        switch ($prodiUser) {
            case 1: // D3 TE
                $rolehandler = 2;
                $status = "staf_prodi";
                break;
            case 2: // S1 TE
                $rolehandler = 3;
                $status = "staf_prodi";
                break;

            case 3: // S1 TI
                $rolehandler = 4;
                $status = "staf_prodi";
                break;
            default: // Staf
                $rolehandler = 1;
                $status = "staf_jurusan";
                break;
        }

        $lampiran = $request->file("dokumen");

        if ($lampiran) {
            Surat::create([
                "nama" => $request->nama,
                "keterangan" => $request->keterangan,
                "status" => $status,
                "keterangan_status" => $rolehandler == 1 ? "Menunggu Persetujuan Staf Administrasi Jurusan" : "Surat Sedang Diproses Staf Administrasi Prodi",
                "url_lampiran_lokal" => str_replace('public/', '', $lampiran->store('public/surat')),
                "url_lampiran" => $request->url_lampiran,
                "role_tujuan" => $request->tujuan_surat,
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                "prodi_user" => $prodiUser,
                "role_handler" => $rolehandler,
                "semester" => $request->semester,
            ]);
        } else {
            Surat::create([
                "nama" => $request->nama,
                "keterangan" => $request->keterangan,
                "status" => $status,
                "keterangan_status" => $rolehandler == 1 ? "Menunggu Persetujuan Staf Administrasi Jurusan" : "Surat Sedang Diproses Staf Administrasi Prodi",
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                "url_lampiran" => $request->url_lampiran,
                "role_tujuan" => $request->tujuan_surat,
                "semester" => $request->semester,
                "prodi_user" => $prodiUser,
                "role_handler" => $rolehandler
            ]);
        }

        return redirect()->route("doc.index");
    }

    public function detailForPublic($encryptId, Request $request)
    {
        $id = decrypt($encryptId);
        $surat = Surat::findOrFail($id);
        $userId = null;
        $jenisUser = "guest";
        $dosens = [];
        $isKaprodi = false;

        return view("doc.surat.detail", compact('surat', 'userId', 'jenisUser',   'dosens', 'isKaprodi'));
    }

    public function detail($id, Request $request)
    {
        $surat = Surat::find($id);
        if (!$surat) abort(404);
        $userId = $request->user_id;

        $jenisUser = $request->jenis_user;
        $dosens = [];
        if ($jenisUser == "admin") {
            $dosenQuery = Dosen::where("role_id", 5);
            switch ($surat->prodi_user) {
                case 1:
                    $dosenQuery->orWhere("role_id", 6);
                    break;
                case 2:
                    $dosenQuery->orWhere("role_id", 7);
                    break;
                case 3:
                    $dosenQuery->orWhere("role_id", 8);
                    break;

                default:
            }
            $dosens  = $dosenQuery->get();
        }
        $isKaprodi = false;
        if ($jenisUser == "dosen") {
            switch ($surat->prodi_user) {
                case 1:
                    $kaprodi = 6;
                    break;
                case 2:
                    $kaprodi = 7;
                    break;
                case 3:
                    $kaprodi = 8;
                    break;

                default:
                    $kaprodi = 0;
            }
            $isKaprodi = $kaprodi == Auth::guard("dosen")->user()->role_id;
        }
        return view("doc.surat.detail", compact('surat', 'userId', 'jenisUser',   'dosens', 'isKaprodi'));
    }

    public function edit($id, Request $request)
    {
        $jenisUser = $request->jenis_user;
        $prodiId = Auth::guard(in_array($jenisUser, ["plp", "admin"]) ? "web" : $jenisUser)->user()->prodi_id;
        $surat = Surat::find($id);
        if (!$surat) abort(404);
        $userId = $request->user_id;
        $dosens = Dosen::where("role_id", 5);
        switch ($prodiId) {
            case 1:
                $dosens->orWhere("role_id", 6);
                break;
            case 2:
                $dosens->orWhere("role_id", 7);
                break;
            case 3:
                $dosens->orWhere("role_id", 8);
                break;

            default:
        }
        $dosens = $dosens->get();
        return view("doc.surat.edit", compact('surat', 'userId', 'dosens'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            "nama" => "required",
            "tujuan_surat" => "required"
        ], [
            "nama.required" => "Nama Surat harus diisi",
            "tujuan_surat.required" => "required"
        ]);

        $surat = Surat::find($id);
        if (!$surat) abort(404);
        $surat->nama = $request->nama;
        $surat->keterangan = $request->keterangan;

        $prodiUser = 0;
        if ($request->jenis_user == "mahasiswa") {
            $prodiUser = Auth::guard("mahasiswa")->user()->prodi_id;
        } elseif ($request->jenis_user == "dosen") {
            $prodiUser = Auth::guard("dosen")->user()->prodi_id;
        }

        switch ($prodiUser) {
            case 1: // D3 TE
                $rolehandler = 2;
                break;
            case 2: // S1 TE
                $rolehandler = 3;
                break;
            case 3: // S1 TI
                $rolehandler = 4;
                break;
            default: // Staf
                $rolehandler = 1;
                break;
        }

        $surat->role_tujuan = $request->tujuan_surat;
        $surat->role_handler = $rolehandler;

        $lampiran = $request->file("dokumen");
        if ($lampiran) {
            // Hapus file dari storage
            Storage::delete("public/" . $surat->url_lampiran_lokal);
            $surat->url_lampiran_lokal = str_replace('public/', '', $lampiran->store('public/surat'));
        }
        $surat->url_lampiran = $request->url_lampiran;

        $surat->update();

        return redirect()->route('surat.detail', $surat->id);
    }

    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);
        // Hapus file dari storage
        Storage::delete("public/" . $surat->url_lampiran_lokal);
        $surat->delete();
        return redirect()->route("doc.index");
    }

    public function accStafProdi($id)
    {
        $surat = Surat::find($id);
        $surat->status = "kaprodi";
        $surat->keterangan_status = "Menunggu Persetujuan Ketua Prodi";
        $surat->update();

        return back();
    }

    public function accKaprodi($id)
    {
        $surat = Surat::find($id);
        $surat->status = "staf_jurusan";
        $surat->keterangan_status = "Menunggu Persetujuan Staf Administrasi Jurusan";
        $surat->update();

        return back();
    }
    public function accStafJurusan($id)
    {
        $surat = Surat::find($id);
        $surat->status = "kajur";
        $surat->keterangan_status = "Menunggu Persetujuan Ketua Jurusan";
        $surat->update();

        return back();
    }
    public function accept($id)
    {
        $surat = Surat::find($id);
        $surat->status = "diterima";
        $surat->keterangan_status = "Surat Dalam Penyelesaian";
        $surat->update();

        return back();
    }

    public function ubahTujuan($id, Request $request)
    {
        $request->validate([
            'tujuan_surat' => "required"
        ], [
            'tujuan_surat.required' => "Tujuan harus disi"
        ]);
        $surat = Surat::find($id);
        $surat->role_tujuan = $request->tujuan_surat;
        $surat->update();
        return back();
    }

    public function done($id, Request $request)
    {
        $request->validate([
            "nomor_surat" => "required",
            "surat" => "required|file"
        ], [
            "nomor_surat.required" => "Masukkan nomor surat",
            "surat.required" => "Masukkan Hasil Surat"
        ]);

        $surat = Surat::find($id);
        $surat->status = "selesai";
        $surat->keterangan_status = "Surat Sudah Bisa Diambil";
        $surat->nomor_surat = $request->nomor_surat;
        $suratJadi = $request->file("surat");
        $surat->url_surat_jadi = str_replace('public/', '', $suratJadi->store('public/surat'));
        $surat->update();

        return back();
    }

    public function reject($id, Request $request)
    {
        $request->validate([
            "alasan" => "required"
        ], [
            "alasan.required" => "Berikan alasan penolakan pengajuan"
        ]);
        $role = Auth::guard($request->jenis_user === "admin" ? "web" : "dosen")->user()->role_id;
        if (!$role) abort(403);
        $surat = Surat::find($id);
        $surat->alasan_ditolak = $request->alasan;
        $surat->role_rejected = $role;
        $surat->status = "ditolak";
        $surat->keterangan_status = "Pengajuan Ditolak";
        $surat->update();

        return redirect()->route("doc.index");
    }
}
