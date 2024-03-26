<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\DokumenMention;
use App\Models\Semester;
use App\Models\Dosen;
use App\Models\User;
use App\Services\DokumenService;
use App\Services\DosenService;
use App\Services\MahasiswaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class DokumenController extends Controller
{
    private $kategoris = ['pendidikan', 'penelitian', 'pengabdian', 'penunjang', 'KP/Skripsi', 'Pribadi', 'Sertifikat', 'lainnya'];

    public function create(Request $request)
    {
        $jenis_user = $request->jenis_user;
        $user_id = $request->user_id;
        $dosens = DosenService::getWithOriginalName();
        if ($jenis_user == "dosen") {
            $dosens = $dosens->filter(function ($dosen) use ($user_id) {
                return $dosen->nip != $user_id;
            });
        }

        $queryStaf = User::select("username", "nama")->orderBy("nama");
        if ($jenis_user == "admin" || $jenis_user == "plp") {
            $queryStaf->where("username", "!=", $user_id);
        }
        $staffs = $queryStaf->get();
        $mahasiswas = MahasiswaService::groupByProdiAngkatan();
        $kategoris = $this->kategoris;
        $semesters = Semester::getSimpleSemester();
        return view("doc.dokumen.create", compact('dosens', 'kategoris', 'staffs', 'semesters', 'mahasiswas'));
    }

    public function store(Request $request)
    {
        // Validasi Request
        $request->validate([
            "nama" => "required",
            'dokumen' => 'file',
            'kategori' => 'required',
            'tgl_dokumen' => "required",
            'semester' => 'required',
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tgl_dokumen.required' => 'Tanggal dokumen harus diisi.',
            'kategori.required' => 'Pilih kategori dokumen terlebih dahulu',
            'semester.required' => 'Pilih semester terlebih dahulu',
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
                'nomor_dokumen' => $request->nomor_dokumen,
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

        // Simpan Mention
        DokumenService::saveMentions($request, $dokumen->id);

        Alert::success('Berhasil!', 'Berhasil membuat usulan baru')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }
    public function detailForPublic($encryptId)
    {
        $id = decrypt($encryptId);
        $dokumen = Dokumen::findOrFail($id);
        $mentioned = [];
        $userId = null;
        return view('doc.dokumen.detail', compact('dokumen', 'userId', 'mentioned'));
    }
    public function detail(Request $request, $id)
    {
        $userId = $request->user_id;
        $dokumen = Dokumen::findOrFail($id);
        $mentioned = DokumenMention::where("dokumen_id", $id)->where("user_mentioned", $userId)->first();
        return view('doc.dokumen.detail', compact('dokumen', 'userId', 'mentioned'));
    }

    public function edit(Request $request, $id)
    {
        $jenis_user = $request->jenis_user;
        $user_id = $request->user_id;
        $dokumen = Dokumen::findOrFail($id);
        if ($dokumen->user_created != $request->user_id) {
            abort(403);
        }
        $dosens = DosenService::getWithOriginalName();
        if ($jenis_user == "dosen") {
            $dosens = $dosens->filter(function ($dosen) use ($user_id) {
                return $dosen->nip != $user_id;
            });
        }

        $queryStaf = User::select("username", "nama")->orderBy("nama");
        if ($jenis_user == "admin") {
            $queryStaf->where("username", "!=", $user_id);
        }
        $staffs = $queryStaf->get();
        $mahasiswas = MahasiswaService::groupByProdiAngkatan();
        $kategoris = $this->kategoris;
        $semesters = Semester::getSimpleSemester();
        return view('doc.dokumen.edit', compact('dosens', 'dokumen', 'kategoris', 'staffs', 'mahasiswas', 'semesters'));
    }

    public function update(Request $request, $id)
    {
        // Validiasi
        $request->validate([
            "nama" => "required",
            'dokumen' => 'file|max:10240',
            'kategori' => 'required',
            'tgl_dokumen' => "required",
            'semester' => "required"
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tgl_dokumen.required' => 'Tanggal dokumen harus diisi.',
            'kategori' => 'Pilih kategori dokumen terlebih dahulu',
            'dokumen.max' => 'Ukuran dokumen melebihi 10MB',
            'semester.required' => "Semester harus diisi"
        ]);

        $dokumenUpload = $request->file('dokumen');
        $dokumen = Dokumen::find($id);
        $dokumen->nomor_dokumen = $request->nomor_dokumen;
        $dokumen->nama = $request->nama;
        $dokumen->keterangan = $request->keterangan;
        $dokumen->kategori = $request->kategori;
        $dokumen->semester = $request->semester;
        $dokumen->tgl_dokumen = $request->tgl_dokumen;

        if ($dokumenUpload) {
            Storage::delete("public/" . $dokumen->url_dokumen_lokal);
            $dokumen->url_dokumen_lokal = str_replace('public/', '', $dokumenUpload->store('public/dokumen'));
        }
        $dokumen->url_dokumen = $request->url_dokumen;
        $dokumen->update();

        //clear data mention
        DokumenMention::where("dokumen_id", $id)->delete();

        // Simpan Mention
        DokumenService::saveMentions($request, $dokumen->id);

        Alert::success('Berhasil!', 'Berhasil membuat mengubah usulan')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("doc.index");
    }

    public function destroy($id)
    {
        //Hapus Mentions
        DokumenMention::where("dokumen_id", $id)->delete();
        // Hapus Data Usulan
        $dokumen = Dokumen::findOrFail($id);
        // Hapus file dari storage
        Storage::delete("public/" . $dokumen->url_dokumen_lokal);
        $dokumen->delete();
        Alert::success('Berhasil!', 'Berhasil menghapus usulan')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }
}
