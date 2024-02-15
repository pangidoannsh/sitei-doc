<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Surat;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
{
    public function create()
    {
        $roles = Role::getRolePengelola();
        return view("doc.surat.create", compact("roles"));
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
        $prodiUser = 0;
        if ($request->jenis_user == "mahasiswa") {
            $prodiUser = Auth::guard("mahasiswa")->user()->prodi_id;
        } else {
            $prodiUser = Auth::guard("dosen")->user()->prodi_id;
        }

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
            default: // Dosen/Staf
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
                "keterangan_status" => "Surat Sedang Diproses Staf Administrasi Prodi",
                "url_lampiran_lokal" => str_replace('public/', '', $lampiran->store('public/surat')),
                "url_lampiran" => $request->url_lampiran,
                "role_tujuan" => $request->tujuan_surat,
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                "prodi_user" => $prodiUser,
                "role_handler" => $rolehandler,
            ]);
        } else {
            Surat::create([
                "nama" => $request->nama,
                "keterangan" => $request->keterangan,
                "status" => $status,
                "keterangan_status" => "Surat Sedang Diproses Staf Administrasi Prodi",
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                "url_lampiran" => $request->url_lampiran,
                "role_tujuan" => $request->tujuan_surat,
                "prodi_user" => $prodiUser,
                "role_handler" => $rolehandler
            ]);
        }

        return redirect()->route("doc.index");
    }

    public function detail($id, Request $request)
    {
        $surat = Surat::find($id);
        if (!$surat) abort(404);
        $userId = $request->user_id;

        $jenisUser = $request->jenis_user;
        $roles = [];
        if ($jenisUser == "admin") {
            $roles = Role::where("role.id", "<", 12)
                ->leftJoin("dosen", "dosen.role_id", "role.id")
                ->leftJoin("users", "users.role_id", "role.id")
                ->select(
                    "role.id as id",
                    "role.role_akses as akses",
                    "dosen.nama as nama_dosen",
                    "users.nama as nama_admin",
                )
                ->get();
        }

        if ($jenisUser != "mahasiswa") {
            $role = Auth::guard($jenisUser == "admin" ? "web" : "dosen")->user()->role_id;
            switch ($role) {
                case 2:
                case 6:
                    $userProdi = 1;
                    break;
                case 3:
                case 7;
                    $userProdi = 2;
                    break;
                case 4:
                case 8;
                    $userProdi = 3;
                    break;

                default:
                    $userProdi = 0;
                    break;
            }
        } else {
            $userProdi = Auth::guard("mahasiswa")->user()->prodi_id;
        }
        return view("doc.surat.detail", compact('surat', 'userId', 'jenisUser', 'roles', 'userProdi'));
    }

    public function edit($id, Request $request)
    {
        $surat = Surat::find($id);
        if (!$surat) abort(404);
        $userId = $request->user_id;
        $roles = Role::getRolePengelola();

        return view("doc.surat.edit", compact('surat', 'userId', 'roles'));
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
        } else {
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
            default: // Dosen/Staf
                $rolehandler = 1;
                break;
        }

        $surat->role_tujuan = $request->tujuan_surat;
        $surat->role_handler = $rolehandler;

        $lampiran = $request->file("dokumen");
        if ($lampiran) {
            $surat->url_lampiran_lokal = str_replace('public/', '', $lampiran->store('public/surat'));
        }
        $surat->url_lampiran = $request->url_lampiran;

        $surat->update();

        return redirect()->route('surat.detail', $surat->id);
    }

    public function destroy($id)
    {
        Surat::find($id)->delete();
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
