<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\PenerimaSertifikat;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SertifikatController extends Controller
{
    // public function index(Request $request)
    // {
    //     dd(Sertifikat::first()->penerimas);
    // }
    public function create()
    {
        $mahasiswas = Mahasiswa::getMahasiswaByAngkatan();
        return view("doc.sertifikat.create", compact('mahasiswas'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi Request
        $request->validate([
            "nama" => "required",
            'jenis' => 'required',
            'tanggal' => "required",
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tanggal.required' => 'Tanggal dokumen harus diisi.',
            'jenis.required' => 'Pilih jenis sertifikat terlebih dahulu',
        ]);

        $userId = $request->user_id;

        $sertif = Sertifikat::create([
            "nama" => $request->nama,
            "jenis" => $request->jenis,
            "tanggal" => $request->tanggal,
            "user_created" => $userId
        ]);

        if ($request->has("mahasiswa")) {
            $mahasiswas = $request->mahasiswa;
            foreach ($mahasiswas as  $penerima) {
                PenerimaSertifikat::create([
                    "user_penerima" => $penerima,
                    "sertifikat_id" => $sertif->id
                ]);
            }
        }

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
        if ($jenisUser == "admin" || $userId == $data->user_created) {
            return view("doc.sertifikat.detail", compact("data", "userId", "jenisUser"));
        }
        return abort(403);
    }


    public function edit($id)
    {
        return view("doc.sertifikat.edit", [
            "data" => Sertifikat::findOrFail($id),
            "mahasiswas" => Mahasiswa::getMahasiswaByAngkatan()
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi Request
        $request->validate([
            "nama" => "required",
            'jenis' => 'required',
            'tanggal' => "required",
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tanggal.required' => 'Tanggal dokumen harus diisi.',
            'jenis.required' => 'Pilih jenis sertifikat terlebih dahulu',
        ]);

        $sertif = Sertifikat::find($id);
        $sertif->nama = $request->nama;
        $sertif->jenis = $request->jenis;
        $sertif->isi = $request->isi;
        $sertif->tanggal = $request->tanggal;

        PenerimaSertifikat::where("sertifikat_id", $id)->delete();

        if ($request->has("mahasiswa")) {
            $mahasiswas = $request->mahasiswa;
            foreach ($mahasiswas as  $penerima) {
                PenerimaSertifikat::create([
                    "user_penerima" => $penerima,
                    "sertifikat_id" => $sertif->id
                ]);
            }
        }

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
            PenerimaSertifikat::where("sertifikat_id", $sertif->id)->delete();
            $sertif->delete();
            Alert::success('Berhasil!', 'Berhasil membuat sertifikat baru')->showConfirmButton('Ok', '#28a745');
            return redirect()->route("doc.index");
        } else {
            return abort(403);
        }
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
        // dd($request->all());
        if ($request->jenis_user != "admin") return abort(403);
        $request->validate([
            "nomor_sertif" => "required",
            "nomor_sertif.*" => "required",
        ]);
        foreach ($request->nomor_sertif as $id_penerima => $nomor) {
            PenerimaSertifikat::where("id", $id_penerima)->update(["nomor_sertif" => $nomor]);
        }
        $sertif = Sertifikat::find($id);
        $sertif->is_done = true;
        $sertif->update();

        Alert::success('Berhasil!', 'Berhasil membuat sertifikat')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("sertif.detail", $id);
    }
}
