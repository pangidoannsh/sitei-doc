<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Pengumuman;
use App\Models\DistribusiDokumen\PengumumanMention;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $pengumumanMentioned = PengumumanMention::whereHas('dokumen', function ($query) {
            $query->whereDate('tgl_batas_pengumuman', '>=', Carbon::today());
        });

        if ($jenis_user == "mahasiswa") {
            $prodiId = Auth::guard("mahasiswa")->user()->prodi_id;
            switch ($prodiId) {
                case 1:
                    $userMentionId = "d3te_all";
                    break;
                case 2:
                    $userMentionId = "s1te_all";
                    break;
                case 3:
                    $userMentionId = "s1ti_all";
                    break;

                default:
                    $userMentionId = "";
                    break;
            }
            $pengumumanMentioned->where(function ($query) use ($userId, $userMentionId) {
                $query->where('user_mentioned', $userId)->orWhere("user_mentioned", $userMentionId);
            });
        } else {
            $pengumumanMentioned->where('user_mentioned', $userId);
        }

        foreach ($pengumumanMentioned->get() as $mention) {
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
        $mahasiswas = Mahasiswa::getMahasiswaByAngkatan();

        // Dosen dan Staff dengan Jabatan
        if ($role) {
            $queryDosen = Dosen::select("nip", "nama");
            if ($jenis_user == "dosen") {
                $queryDosen->where("nip", "!=", $user_id);
            }
            $dosens = $queryDosen->orderBy("nama")->get();

            $queryStaff = User::select("username", "nama");
            if ($jenis_user == "admin") {
                $queryStaff->where("username", "!=", $user_id);
            }
            $staffs = $queryStaff->orderBy("nama")->get();

            return view("doc.pengumuman.create", compact('dosens', 'staffs', 'kategoris', 'prodis', 'mahasiswas'));
        }
        // Untuk Dosen Tanpa Jabatan
        else {
            return view("doc.pengumuman.create-no-role", compact('kategoris', 'prodis', 'mahasiswas'));
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

        if (!$request->has("select_all_dosen")) {
            $dosenMentions = $request->input("dosen");
            // Create Data Mention
            if (is_array($dosenMentions)) {
                foreach ($dosenMentions as $mention) {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => $mention,
                        'jenis_user' => 'dosen'
                    ]);
                }
            }
        }

        if (!$request->has("select_all_staf")) {
            $stafMentions = $request->input("staf");
            // Create Data Mention
            if (is_array($stafMentions)) {
                foreach ($stafMentions as $mention) {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => $mention,
                        'jenis_user' => 'admin'
                    ]);
                }
            }
        }

        if (!$request->has("select_all_mahasiswa")) {
            // D3 Teknik Elektro
            if ($request->has("d3te")) {
                if (!$request->has("select_all_d3te")) {
                    foreach ($request->d3te as $nim) {
                        PengumumanMention::create([
                            'pengumuman_id' => $pengumuman->id,
                            'user_mentioned' => $nim,
                            'jenis_user' => 'mahasiswa'
                        ]);
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => "d3te_all",
                        'jenis_user' => 'mahasiswa'
                    ]);
                }
            }
            // S1 Teknik Elektro
            if ($request->has("s1te")) {
                if (!$request->has("select_all_s1te")) {
                    foreach ($request->s1te as $nim) {
                        PengumumanMention::create([
                            'pengumuman_id' => $pengumuman->id,
                            'user_mentioned' => $nim,
                            'jenis_user' => 'mahasiswa'
                        ]);
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => "s1te_all",
                        'jenis_user' => 'mahasiswa'
                    ]);
                }
            }
            // S1 Teknik Informatika
            if ($request->has("s1ti")) {
                if (!$request->has("select_all_s1ti")) {
                    foreach ($request->s1ti as $nim) {
                        PengumumanMention::create([
                            'pengumuman_id' => $pengumuman->id,
                            'user_mentioned' => $nim,
                            'jenis_user' => 'mahasiswa'
                        ]);
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => "s1ti_all",
                        'jenis_user' => 'mahasiswa'
                    ]);
                }
            }
        }
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
        $mahasiswas = Mahasiswa::getMahasiswaByAngkatan();
        // Dosen dan Staff dengan Jabatan
        if ($role) {
            $queryDosen = Dosen::select("nip", "nama");
            if ($jenis_user == "dosen") {
                $queryDosen->where("nip", "!=", $user_id);
            }
            $dosens = $queryDosen->orderBy("nama")->get();

            $queryStaff = User::select("username", "nama");
            if ($jenis_user == "admin") {
                $queryStaff->where("username", "!=", $user_id);
            }
            $staffs = $queryStaff->orderBy("nama")->get();

            return view("doc.pengumuman.edit", compact('data', 'dosens', 'staffs', 'kategoris', 'mahasiswas'));
        }
        // Untuk Dosen Tanpa Jabatan
        else {
            return view("doc.pengumuman.edit-no-role", compact('data', 'kategoris', 'mahasiswas'));
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

        // Add mention untuk dosen
        if (!$request->has("select_all_dosen")) {
            $dosenMentions = $request->input("dosen");
            // Create Data Mention
            if (is_array($dosenMentions)) {
                foreach ($dosenMentions as $mention) {
                    PengumumanMention::create([
                        'pengumuman_id' => $id,
                        'user_mentioned' => $mention,
                        'jenis_user' => 'dosen'
                    ]);
                }
            }
        }

        // Add mention untuk staf
        if (!$request->has("select_all_staf")) {
            $stafMentions = $request->input("staf");
            // Create Data Mention
            if (is_array($stafMentions)) {
                foreach ($stafMentions as $mention) {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => $mention,
                        'jenis_user' => 'admin'
                    ]);
                }
            }
        }

        if (!$request->has("select_all_mahasiswa")) {
            // D3 Teknik Elektro
            if ($request->has("d3te")) {
                if (!$request->has("select_all_d3te")) {
                    foreach ($request->d3te as $nim) {
                        PengumumanMention::create([
                            'pengumuman_id' => $pengumuman->id,
                            'user_mentioned' => $nim,
                            'jenis_user' => 'mahasiswa'
                        ]);
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => "d3te_all",
                        'jenis_user' => 'mahasiswa'
                    ]);
                }
            }
            // S1 Teknik Elektro
            if ($request->has("s1te")) {
                if (!$request->has("select_all_s1te")) {
                    foreach ($request->s1te as $nim) {
                        PengumumanMention::create([
                            'pengumuman_id' => $pengumuman->id,
                            'user_mentioned' => $nim,
                            'jenis_user' => 'mahasiswa'
                        ]);
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => "s1te_all",
                        'jenis_user' => 'mahasiswa'
                    ]);
                }
            }
            // S1 Teknik Informatika
            if ($request->has("s1ti")) {
                if (!$request->has("select_all_s1ti")) {
                    foreach ($request->s1ti as $nim) {
                        PengumumanMention::create([
                            'pengumuman_id' => $pengumuman->id,
                            'user_mentioned' => $nim,
                            'jenis_user' => 'mahasiswa'
                        ]);
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumuman->id,
                        'user_mentioned' => "s1ti_all",
                        'jenis_user' => 'mahasiswa'
                    ]);
                }
            }
        }
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
