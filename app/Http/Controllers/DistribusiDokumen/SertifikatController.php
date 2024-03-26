<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Logo;
use App\Models\DistribusiDokumen\PenerimaSertifikat;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\DistribusiDokumen\SertifikatLogo;
use App\Models\Dosen;
use App\Models\User;
use App\Services\DosenService;
use App\Services\MahasiswaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SertifikatController extends Controller
{
    public function create(Request $request)
    {
        $jenis_user  = $request->jenis_user;
        $user_id  = $request->user_id;
        // GET DOSEN
        $dosens = DosenService::getWithOriginalName();
        // GET STAF
        $queryStaf = User::select("username", "nama")->orderBy("nama");
        if ($jenis_user == "admin") {
            $queryStaf->where("username", "!=", $user_id);
        }
        $staffs = $queryStaf->get();

        $mahasiswas = MahasiswaService::groupByProdiAngkatan();
        $logos = Logo::all();
        return view("doc.sertifikat.create", compact('mahasiswas', 'dosens', 'staffs', 'logos'));
    }


    public function store(Request $request)
    {
        // Validasi Request
        $request->validate([
            "nama" => "required",
            'tanggal' => "required",
            'sign_by' => "required",
            'signer_role' => "required",
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tanggal.required' => 'Tanggal sertifikat harus diisi.',
            'sign_by.required' => "Pilih penandatangan sertifikat",
            'signer_role.required' => "Isi Jabatan Penandatangan Sertifikat",
        ]);

        $userId = $request->user_id;
        $logos = $request->logo;

        $sertif = Sertifikat::create([
            "nama" => $request->nama,
            "tanggal" => $request->tanggal,
            "user_created" => $userId,
            "sign_by" => $request->sign_by,
            "signer_role" => $request->signer_role,
            "isi" => $request->isi,
            "jenis_user" => $request->jenis_user,
        ]);

        // Save Logo
        foreach ($logos as  $logoId) {
            SertifikatLogo::create([
                "sertifikat_id" => $sertif->id,
                'logo_id' => $logoId
            ]);
        }

        // DOSEN
        if ($request->has("dosen")) {
            $dosens = $request->dosen;
            foreach ($dosens as  $penerima) {
                PenerimaSertifikat::create([
                    "user_penerima" => $penerima,
                    "sertifikat_id" => $sertif->id,
                    "jenis_penerima" => "dosen"
                ]);
            }
        }

        // STAF
        if ($request->has("staf")) {
            $stafs = $request->staf;
            foreach ($stafs as  $penerima) {
                PenerimaSertifikat::create([
                    "user_penerima" => $penerima,
                    "sertifikat_id" => $sertif->id,
                    "jenis_penerima" => "staf"
                ]);
            }
        }

        // MAHASISWA
        if ($request->has("mahasiswa")) {
            $mahasiswas = $request->mahasiswa;
            foreach ($mahasiswas as  $penerima) {
                PenerimaSertifikat::create([
                    "user_penerima" => $penerima,
                    "sertifikat_id" => $sertif->id,
                    "jenis_penerima" => "mahasiswa"
                ]);
            }
        }
        // DILUAR PENGGUNA SITEI
        if ($request->has("penerima")) {
            $penerimas = $request->penerima;
            foreach ($penerimas as $penerima) {
                if ($penerima) {
                    PenerimaSertifikat::create([
                        "nama_penerima" => $penerima,
                        "sertifikat_id" => $sertif->id
                    ]);
                }
            }
        }
        Alert::success('Berhasil!', 'Berhasil membuat sertifikat baru')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("doc.index");
    }


    public function detail($id, Request $request)
    {
        $userId = $request->user_id;
        $jenisUser = $request->jenis_user;
        $data = Sertifikat::findOrFail($id);
        return view("doc.sertifikat.detail", compact("data", "userId", "jenisUser"));
    }


    public function edit($id, Request $request)
    {
        $data = Sertifikat::findOrFail($id);
        if ($data->status == 'selesai' || $data->status != "staf_jurusan") abort(404);

        $jenis_user  = $request->jenis_user;
        $user_id  = $request->user_id;
        // GET DOSEN
        $dosens = DosenService::getWithOriginalName();
        if ($jenis_user == "dosen") {
            $dosens = $dosens->filter(function ($dosen) use ($user_id) {
                return $dosen->nip != $user_id;
            });
        }
        // GET STAF
        $queryStaf = User::select("username", "nama")->orderBy("nama");
        if ($jenis_user == "admin") {
            $queryStaf->where("username", "!=", $user_id);
        }
        $staffs = $queryStaf->get();

        return view("doc.sertifikat.edit", [
            "data" => $data,
            "mahasiswas" => MahasiswaService::groupByProdiAngkatan(),
            "dosens" => $dosens,
            "staffs" => $staffs,
            "logos" => Logo::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi Request
        $request->validate([
            "nama" => "required",
            'tanggal' => "required",
            'sign_by' => 'required'
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tanggal.required' => 'Tanggal dokumen harus diisi.',
            'sign_by.required' => 'Pilih Penandatangan Sertifikat'
        ]);

        $sertif = Sertifikat::find($id);
        $sertif->nama = $request->nama;
        $sertif->isi = $request->isi;
        $sertif->tanggal = $request->tanggal;
        $sertif->sign_by = $request->sign_by;
        $sertif->signer_role = $request->signer_role;

        $logos = $request->logo;
        SertifikatLogo::where("sertifikat_id", $id)->delete();
        // Save Logo
        foreach ($logos as  $logoId) {
            SertifikatLogo::create([
                "sertifikat_id" => $sertif->id,
                'logo_id' => $logoId
            ]);
        }

        PenerimaSertifikat::where("sertifikat_id", $id)->delete();

        // DOSEN
        if ($request->has("dosen")) {
            $dosens = $request->dosen;
            foreach ($dosens as  $penerima) {
                PenerimaSertifikat::create([
                    "user_penerima" => $penerima,
                    "sertifikat_id" => $sertif->id,
                    "jenis_penerima" => "dosen"
                ]);
            }
        }

        // STAF
        if ($request->has("staf")) {
            $stafs = $request->staf;
            foreach ($stafs as  $penerima) {
                PenerimaSertifikat::create([
                    "user_penerima" => $penerima,
                    "sertifikat_id" => $sertif->id,
                    "jenis_penerima" => "staf"
                ]);
            }
        }

        // MAHASISWA
        if ($request->has("mahasiswa")) {
            $mahasiswas = $request->mahasiswa;
            foreach ($mahasiswas as  $penerima) {
                PenerimaSertifikat::create([
                    "user_penerima" => $penerima,
                    "sertifikat_id" => $sertif->id,
                    "jenis_penerima" => "mahasiswa"
                ]);
            }
        }
        // DILUAR PENGGUNA SITEI
        if ($request->has("penerima")) {
            $penerimas = $request->penerima;
            foreach ($penerimas as $penerima) {
                if ($penerima) {
                    PenerimaSertifikat::create([
                        "nama_penerima" => $penerima,
                        "sertifikat_id" => $sertif->id
                    ]);
                }
            }
        }
        $sertif->update();
        Alert::success('Berhasil!', 'Berhasil membuat sertifikat baru')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("doc.index");
    }

    public function delete($id, Request $request)
    {
        $sertif = Sertifikat::find($id);
        if ($sertif->user_created == $request->user_id) {
            if ($sertif->status !== "selesai") {
                PenerimaSertifikat::where("sertifikat_id", $sertif->id)->delete();
                $sertif->delete();
                Alert::success('Berhasil!', 'Berhasil menghapus sertifikat')->showConfirmButton('Ok', '#28a745');
                return redirect()->route("doc.index");
            } else {
                Alert::error('Gagal!', 'Sertifikat telah dibagikan, tidak dapat dihapus')->showConfirmButton('Ok', '#F27474');
                return back();
            }
        } else {
            return abort(403);
        }
    }

    public function accAdmin($id, Request $request)
    {
        $jenisUser = $request->jenis_user;
        if ($jenisUser == "admin" && (Auth::guard("web")->user()->role_id) == 1) {
            $data = Sertifikat::find($id);
            $data->status = "kajur";
            $data->update();
            return back();
        } else return abort(403);
    }
    public function accKajur($id, Request $request)
    {
        if ((Auth::guard("dosen")->user()->role_id ?? -1) != 5) {
            return abort(403);
        }

        $data = Sertifikat::find($id);
        if ($data->sign_by == $request->user_id) {
            $data->status = "disetujui";
        } else {
            $data->status = "meminta_persetujuan";
        }
        $data->update();
        return back();
    }
    public function sign($id, Request $request)
    {
        $data = Sertifikat::find($id);
        if ($data->sign_by == $request->user_id) {
            $data->status = "disetujui";
        } else {
            return abort(403);
        }

        $data->update();
        return back();
    }
    public function reject($id, Request $request)
    {
        $jenisUser = $request->jenis_user;
        $data = Sertifikat::find($id);
        if ($jenisUser == 'dosen') {
            $rejectBy = Auth::guard("dosen")->user()->nip;
        } else {
            $rejectBy = Auth::guard("web")->user()->username;
        }


        $data->status = "ditolak";
        $data->alasan_ditolak = $request->alasan_ditolak;
        $data->rejected_by = $rejectBy;

        $data->update();
        return back();
    }
    public function make(Request $request, $id)
    {
        if ($request->jenis_user != "admin") return abort(403);
        return view("doc.sertifikat.completion", [
            "data" => Sertifikat::findOrFail($id),
        ]);
    }

    public function completion(Request $request, $id)
    {
        if ($request->jenis_user != "admin") return abort(403);
        $request->validate([
            "nomor_sertif" => "required",
            "nomor_sertif.*" => "required",
        ]);
        foreach ($request->nomor_sertif as $id_penerima => $nomor) {
            PenerimaSertifikat::where("id", $id_penerima)->update(["nomor_sertif" => $nomor]);
        }
        $sertif = Sertifikat::find($id);
        $sertif->status = "selesai";
        $sertif->isi = $request->isi;
        $sertif->update();

        Alert::success('Berhasil!', 'Berhasil membuat sertifikat')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("sertif.detail", $id);
    }
}
