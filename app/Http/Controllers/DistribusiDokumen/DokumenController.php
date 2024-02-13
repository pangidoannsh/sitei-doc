<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\DokumenMention;
use App\Models\DistribusiSurat\Semester;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DokumenController extends Controller
{
    private $kategoris = ['pendidikan', 'penelitian', 'pengabdian', 'penunjang', 'KP/Skripsi', 'lainnya'];

    public function create(Request $request)
    {
        $jenis_user = $request->jenis_user;
        $user_id = $request->user_id;
        $queryDosen = Dosen::select("nip", "nama")->orderBy("nama");
        if ($jenis_user == "dosen") {
            $queryDosen->where("nip", "!=", Auth::guard('dosen')->user()->nip);
        }
        $dosens = $queryDosen->get();

        $queryStaf = User::select("username", "nama")->orderBy("nama");
        if ($jenis_user == "admin") {
            $queryStaf->where("username", "!=", $user_id);
        }
        $staffs = $queryStaf->get();

        $kategoris = $this->kategoris;
        $semesters = Semester::all();
        return view("doc.dokumen.create", compact('dosens', 'kategoris', 'staffs', 'semesters'));
    }

    public function store(Request $request)
    {
        // Validasi Request
        $request->validate([
            "nama" => "required",
            'dokumen' => 'file',
            'kategori' => 'required',
            'tgl_dokumen' => "required",
            'semester' => 'required'
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tgl_dokumen.required' => 'Tanggal dokumen harus diisi.',
            'kategori' => 'Pilih kategori dokumen terlebih dahulu',
            'semester' => 'Pilih semester terlebih dahulu'
        ]);

        // Cek apakah user mengirimkan file dan membuat Usulan Baru
        $dokumenUpload = $request->file('dokumen');
        if ($dokumenUpload) {
            $dokumen = Dokumen::create([
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'url_dokumen_lokal' => str_replace('public/', '', $dokumenUpload->store('public/dokumen')),
                'url_dokumen' => $request->url_dokumen,
                'tgl_dokumen' => $request->tgl_dokumen,
                'kategori' => $request->kategori,
                'semester' => $request->semester,
                'nomor_dokumen' => $request->nomor_dokumen
            ]);
        } else {
            $dokumen = Dokumen::create([
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'url_dokumen' => $request->url_dokumen,
                'tgl_dokumen' => $request->tgl_dokumen,
                'kategori' => $request->kategori,
                'semester' => $request->semester,
                'nomor_dokumen' => $request->nomor_dokumen,
            ]);
        }

        $dosenMentions = $request->input("dosen");
        // Create Data Mention
        if (is_array($dosenMentions)) {
            foreach ($dosenMentions as $mention) {
                DokumenMention::create([
                    'dokumen_id' => $dokumen->id,
                    'user_mentioned' => $mention,
                    'jenis_user' => 'dosen'
                ]);
            }
        }

        Alert::success('Berhasil!', 'Berhasil membuat usulan baru')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }

    public function detail(Request $request, $id)
    {
        $userId = $request->user_id;
        $dokumen = Dokumen::findOrFail($id);
        $mentioned = DokumenMention::where("dokumen_id", $id)->where("user_mentioned", $userId)->first();
        if (!($dokumen->user_created == $userId || $mentioned)) abort(403);
        return view('doc.dokumen.detail', compact('dokumen', 'userId', 'mentioned'));
    }

    public function edit(Request $request, $id)
    {
        $jenis_user = $request->jenis_user;
        $queryDosen = Dosen::select("nip", "nama");
        if ($jenis_user == "dosen") {
            $queryDosen->where("nip", "!=", Auth::guard('dosen')->user()->nip);
        }
        $dosens = $queryDosen->orderBy("nama")->get();
        $dokumen = Dokumen::findOrFail($id);
        if ($dokumen->user_created != $request->user_id) {
            abort(403);
        }
        $kategoris = $this->kategoris;
        return view('doc.dokumen.edit', compact('dosens', 'dokumen', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        // Validiasi
        $request->validate([
            "nama" => "required",
            'dokumen' => 'file|max:10240',
            'kategori' => 'required',
            'tgl_dokumen' => "required"
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tgl_dokumen.required' => 'Tanggal dokumen harus diisi.',
            'kategori' => 'Pilih kategori dokumen terlebih dahulu',
            'dokumen.max' => 'Ukuran dokumen melebihi 10MB'
        ]);

        $dokumenUpload = $request->file('dokumen');
        $dokumen = Dokumen::find($id);
        $dokumen->nama = $request->nama;
        $dokumen->keterangan = $request->keterangan;
        $dokumen->kategori = $request->kategori;
        $dokumen->semester = $request->semester;

        if ($dokumenUpload) {
            $dokumen->url_dokumen_lokal = str_replace('public/', '', $dokumenUpload->store('public/dokumen'));
        }
        $dokumen->url_dokumen = $request->url_dokumen;
        $dokumen->update();

        // Store data user yang ter-mentions
        $userMentioned =  explode("--", $request->user_mentioned);
        //clear data mention
        DokumenMention::where("dokumen_id", $id)->delete();
        //create new data mention
        foreach ($userMentioned as $userIdMention) {
            if ($userIdMention) {
                DokumenMention::create([
                    'dokumen_id' => $id,
                    'user_mentioned' => $userIdMention,
                    'jenis_user' => 'dosen'
                ]);
            }
        }

        Alert::success('Berhasil!', 'Berhasil membuat mengubah usulan')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("doc.index");
    }

    public function destroy($id)
    {
        //Hapus Mentions
        DokumenMention::where("dokumen_id", $id)->delete();
        // Hapus Data Usulan
        Dokumen::where("id", $id)->delete();
        Alert::success('Berhasil!', 'Berhasil menghapus usulan')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }
}
