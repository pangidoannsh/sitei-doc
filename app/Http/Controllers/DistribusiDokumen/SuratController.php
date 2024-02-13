<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Surat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function create()
    {
        return view("doc.surat.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "nama" => "required"
        ], [
            "nama.required" => "Nama Surat harus diisi"
        ]);

        $lampiran = $request->file("dokumen");
        if ($lampiran) {
            Surat::create([
                "nama" => $request->nama,
                "keterangan" => $request->keterangan,
                "keterangan_status" => "Surat Sedang Diproses",
                "url_lampiran" => str_replace('public/', '', $lampiran->store('public/file')),
                "is_local_file" => true,
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
            ]);
        } else {
            Surat::create([
                "nama" => $request->nama,
                "keterangan" => $request->keterangan,
                "keterangan_status" => "Surat Sedang Diproses",
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                "url_lampiran" => $request->url_lampiran,
                "is_local_file" => false,
            ]);
        }

        return redirect()->route("doc.index");
    }

    public function detail($id, Request $request)
    {
        $surat = Surat::find($id);
        if (!$surat) abort(404);
        $userId = $request->user_id;
        // Ketika yang mengakses bukan pengaju surat atau admin
        if (!($surat->user_created == $userId || $request->jenis_user == "admin")) {
            abort(403);
        }
        $jenisUser = $request->jenis_user;
        return view("doc.surat.detail", compact('surat', 'userId', 'jenisUser'));
    }

    public function edit($id, Request $request)
    {
        $surat = Surat::find($id);
        if (!$surat) abort(404);
        $userId = $request->user_id;
        return view("doc.surat.edit", compact('surat', 'userId'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            "nama" => "required"
        ], [
            "nama.required" => "Nama Surat harus diisi"
        ]);

        $surat = Surat::find($id);
        if (!$surat) abort(404);
        $surat->nama = $request->nama;
        $surat->keterangan = $request->keterangan;

        $lampiran = $request->file("dokumen");
        if ($lampiran) {
            $surat->url_lampiran = str_replace('public/', '', $lampiran->store('public/file'));
            $surat->is_local_file = true;
        } else {
            $surat->url_lampiran = $request->url_lampiran;
            $surat->is_local_file = false;
        }

        $surat->update();

        return back();
    }

    public function destroy($id)
    {
        Surat::find($id)->delete();
        return back();
    }
    public function done($id, Request $request)
    {
        $request->validate([
            "nomor_surat" => "required",
            "surat" => "required"
        ], [
            "nomor_surat.required" => "Masukkan nomor surat",
            "surat.required" => "Masukkan Hasil Surat"
        ]);

        $surat = Surat::find($id);
        $surat->status = "diterima";
        $surat->keterangan_status = "Surat Sudah Bisa Diambil";
        $surat->nomor_surat = $request->nomor_surat;
        $suratJadi = $request->file("surat");
        $surat->url_surat_jadi = str_replace('public/', '', $suratJadi->store('public/file'));
        $surat->user_handler = $request->user_id;
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

        $surat = Surat::find($id);
        $surat->alasan_ditolak = $request->alasan;
        $surat->status = "ditolak";
        $surat->keterangan_status = "Pengajuan Ditolak";
        $surat->user_handler = $request->user_id;
        $surat->update();

        return redirect()->route("doc.index");
    }
}
