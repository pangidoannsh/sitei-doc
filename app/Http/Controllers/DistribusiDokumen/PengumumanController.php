<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Pengumuman;
use App\Models\DistribusiDokumen\PengumumanMention;
use App\Models\DistribusiSurat\Semester;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MahasiswaService;
use App\Services\DosenService;
use App\Services\PengumumanService;
use RealRashid\SweetAlert\Facades\Alert;

class PengumumanController extends Controller
{
    private $kategoris = ['pendidikan', 'penelitian', 'pengabdian', 'penunjang', 'KP/Skripsi', 'lainnya'];

    public function index(Request $request)
    {
        $userId = $request->user_id;
        $jenis_user = $request->jenis_user;

        //get data pengumuman
        $pengumumans = Pengumuman::getForUser($jenis_user, $userId);

        // get data pengumuman dari mentions
        $pengumumanMentioned = PengumumanService::getFromMentions($userId, $jenis_user);
        foreach ($pengumumanMentioned as $mention) {
            $pengumumans = $pengumumans->merge([$mention->dokumen]);
        }

        return view('doc.pengumuman.index', compact('pengumumans', 'userId', 'jenis_user'));
    }

    public function create(Request $request)
    {
        $kategoris = $this->kategoris;
        $jenis_user = $request->jenis_user;
        $user_id = $request->user_id;
        $role = Auth::guard($jenis_user == "admin" ? "web" : $jenis_user)->user()->role_id;
        $prodis = Prodi::select("id", "nama_prodi as nama")->get();
        $mahasiswas = MahasiswaService::groupByProdiAngkatan();
        $semesters = Semester::select("nama")->get();
        // Dosen dan Staff dengan Jabatan
        if ($role) {
            $dosens = DosenService::getWithOriginalName();
            if ($jenis_user == "dosen") {
                $dosens = $dosens->filter(function ($dosen) use ($user_id) {
                    return $dosen->nip != $user_id;
                });
            }
            $queryStaff = User::select("username", "nama");
            if ($jenis_user == "admin") {
                $queryStaff->where("username", "!=", $user_id);
            }
            $staffs = $queryStaff->orderBy("nama")->get();

            return view("doc.pengumuman.create", compact('dosens', 'staffs', 'kategoris', 'prodis', 'mahasiswas', 'semesters'));
        }
        // Untuk Dosen Tanpa Jabatan
        else {
            return view("doc.pengumuman.create-no-role", compact('kategoris', 'prodis', 'mahasiswas', 'semesters'));
        }
    }

    public function store(Request $request)
    {
        // Validiasi
        $request->validate([
            "nama" => "required",
            'dokumen' => 'file',
            'kategori' => 'required',
            'tgl_batas_pengumuman' => "required",
            'isi' => "required",
            'semester' => 'required'
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tgl_batas_pengumuman.required' => 'Tanggal dokumen harus diisi.',
            'kategori' => 'Pilih kategori dokumen terlebih dahulu',
            'isi' => "isi pengumuman harus diisi",
            'semester' => 'Pilih semester terlebih dahulu'
        ]);

        // Cek apakah user mengirimkan file dan membuat Usulan Baru
        $dokumenUpload = $request->file('dokumen');
        if ($dokumenUpload) {
            $pengumuman = Pengumuman::create([
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                'nama' => $request->nama,
                'isi' => $request->isi,
                'url_dokumen_lokal' => str_replace('public/', '', $dokumenUpload->store('public/pengumuman')),
                'url_dokumen' => $request->url_dokumen,
                'tgl_batas_pengumuman' => $request->tgl_batas_pengumuman,
                'kategori' => $request->kategori,
                'for_all_dosen' => $request->has("select_all_dosen"),
                'for_all_staf' => $request->has("select_all_staf"),
                'for_all_mahasiswa' => $request->has("select_all_mahasiswa"),
                'semester' => $request->semester
            ]);
        } else {
            $pengumuman = Pengumuman::create([
                "jenis_user" => $request->jenis_user,
                "user_created" => $request->user_id,
                'nama' => $request->nama,
                'isi' => $request->isi,
                'url_dokumen' => $request->url_dokumen,
                'tgl_batas_pengumuman' => $request->tgl_batas_pengumuman,
                'kategori' => $request->kategori,
                'for_all_dosen' => $request->has("select_all_dosen"),
                'for_all_staf' => $request->has("select_all_staf"),
                'for_all_mahasiswa' => $request->has("select_all_mahasiswa"),
                'semester' => $request->semester
            ]);
        }

        // Untuk Menyimpan Data Orang Yang Dikirim Pengumuman
        PengumumanService::saveMentions($request, $pengumuman->id);

        Alert::success('Berhasil!', 'Berhasil membuat pengumuman baru')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('pengumuman.index');
    }
    public function detail($id, Request $request)
    {
        return view("doc.pengumuman.detail", [
            "data" => Pengumuman::findOrFail($id),
            "userId" => $request->user_id
        ]);
    }
    public function edit($id, Request $request)
    {
        $kategoris = $this->kategoris;
        $jenis_user = $request->jenis_user;
        $user_id = $request->user_id;
        $role = Auth::guard($jenis_user == "admin" ? "web" : $jenis_user)->user()->role_id;
        $data = Pengumuman::findOrFail($id);
        $mahasiswas = MahasiswaService::groupByProdiAngkatan();
        $semesters = Semester::select("nama")->get();

        // Dosen dan Staff dengan Jabatan
        if ($role) {
            $dosens = DosenService::getWithOriginalName();
            if ($jenis_user == "dosen") {
                $dosens = $dosens->filter(function ($dosen) use ($user_id) {
                    return $dosen->nip != $user_id;
                });
            }

            $queryStaff = User::select("username", "nama");
            if ($jenis_user == "admin") {
                $queryStaff->where("username", "!=", $user_id);
            }
            $staffs = $queryStaff->orderBy("nama")->get();

            return view("doc.pengumuman.edit", compact('data', 'dosens', 'staffs', 'kategoris', 'mahasiswas', 'semesters'));
        }
        // Untuk Dosen Tanpa Jabatan
        else {
            return view("doc.pengumuman.edit-no-role", compact('data', 'kategoris', 'mahasiswas', 'semesters'));
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "nama" => "required",
            'dokumen' => 'file',
            'kategori' => 'required',
            'tgl_batas_pengumuman' => "required",
            'semester' => 'required'
        ], [
            'nama.required' => 'Nama dokumen harus diisi.',
            'tgl_batas_pengumuman.required' => 'Tanggal dokumen harus diisi.',
            'kategori' => 'Pilih kategori dokumen terlebih dahulu',
            'semester' => 'Pilih semester terlebih dahulu'
        ]);

        $dokumenUpload = $request->file('dokumen');
        $pengumuman = Pengumuman::find($id);
        $pengumuman->nama = $request->nama;
        $pengumuman->isi = $request->isi;
        $pengumuman->kategori = $request->kategori;
        $pengumuman->tgl_batas_pengumuman = $request->tgl_batas_pengumuman;
        $pengumuman->for_all_dosen = $request->has("select_all_dosen");
        $pengumuman->for_all_staf = $request->has("select_all_staf");
        $pengumuman->for_all_mahasiswa = $request->has("select_all_mahasiswa");
        $pengumuman->semester = $request->semester;

        if ($dokumenUpload) {
            $pengumuman->url_dokumen_lokal = str_replace('public/', '', $dokumenUpload->store('public/pengumuman'));
        }
        $pengumuman->url_dokumen = $request->url_dokumen;
        $pengumuman->update();

        //clear data mention
        PengumumanMention::where("pengumuman_id", $id)->delete();
        // Untuk Menyimpan Data Orang Yang Dikirim Pengumuman
        PengumumanService::saveMentions($request, $pengumuman->id);

        Alert::success('Berhasil!', 'Berhasil membuat mengubah pengumuman')->showConfirmButton('Ok', '#28a745');
        return redirect()->route("pengumuman.index");
    }
    public function destroy($id)
    {
        //Hapus Mentions
        PengumumanMention::where("pengumuman_id", $id)->delete();
        // Hapus Data Usulan
        Pengumuman::where("id", $id)->delete();
        Alert::success('Berhasil!', 'Berhasil menghapus Pengumuman')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('pengumuman.index');
    }
}
