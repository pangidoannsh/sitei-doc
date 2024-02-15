<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\DokumenMention;
use App\Models\DistribusiDokumen\PenerimaSertifikat;
use App\Models\DistribusiDokumen\Pengumuman;
use App\Models\DistribusiDokumen\PengumumanMention;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\DistribusiDokumen\Surat;
use App\Models\DistribusiDokumen\SuratCuti;
use App\Models\DistribusiSurat\Semester;
use App\Models\Mahasiswa;
use App\Services\PengumumanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistribusiDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;

        $dokumens = collect([]);
        if ($jenis_user != "mahasiswa") {
            // get data surat cuti
            $dokumens = $dokumens->merge(SuratCuti::getInProgresStatus($user_id, optional(Auth::guard("dosen")->user())->role_id));
            if ((Auth::guard("web")->user()->role_id ?? -1) == 1 || $jenis_user == "dosen") {
                if ($jenis_user == "dosen" && Auth::guard("dosen")->user()->role_id  == 5) {
                    $dokumens = $dokumens->merge(Sertifikat::getOnKajurAcc());
                } else {
                    $dokumens = $dokumens->merge(Sertifikat::getOnProgressByUserOrAdmin($user_id, $jenis_user));
                }
            }
        }
        // get data dokumen
        $dokumens = $dokumens->merge(Dokumen::getLatestByUser($user_id));

        // get data dokumen dari mentions
        $dokumenMentioned = DokumenMention::where('user_mentioned', $user_id)->where("accepted", false)->get();
        foreach ($dokumenMentioned as $mention) {
            $dokumens = $dokumens->merge([$mention->dokumen]);
        }

        //get data pengumuman
        $dokumens = $dokumens->merge(Pengumuman::getForUser($jenis_user, $user_id));

        // Get Data Pengumuman Dari Mentions
        $pengumumanMentioned = PengumumanService::getFromMentions($user_id, $jenis_user);

        foreach ($pengumumanMentioned as $mention) {
            $dokumens = $dokumens->merge([$mention->dokumen]);
        }

        // Get data pengajuan surat
        $ajuanSurat = Surat::where("status", "!=", "selesai")->whereDate("created_at", ">=", Carbon::today()->subDays(3));
        if ($jenis_user == "admin") {
            if (Auth::guard("web")->user()->role_id == 1) {
                $ajuanSurat->where("status", "staf_jurusan");
            } else {
                $ajuanSurat->where("role_handler", Auth::guard("web")->user()->role_id)->where("status", "!=", "ditolak");
            }
        } elseif ($jenis_user == "dosen") {
            if (Auth::guard("dosen")->user()->role_id === 5) {
                $ajuanSurat->where("status", "kajur");
            } else {
                $role = Auth::guard("dosen")->user()->role_id;
                $prodi = Auth::guard("dosen")->user()->prodi_id;
                $ajuanSurat->where("status", "kaprodi")->where("prodi_user", $prodi);
            }
        } else {
            $ajuanSurat->where("user_created", $user_id);
        }
        $dokumens = $dokumens->merge($ajuanSurat->get());
        $semesters = Semester::all();
        return view('doc.index', compact('dokumens', 'jenis_user', 'user_id', 'semesters'));
    }


    public function arsip(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;

        $dokumens = collect([]);
        if ($jenis_user != "mahasiswa") {
            // get data surat cuti
            $dokumens = $dokumens->merge(SuratCuti::getArchive($user_id));
            $dokumens = $dokumens->merge(Sertifikat::getOnDoneByUserOrAdmin($user_id, $jenis_user));
        } else {
            $sertifikats = PenerimaSertifikat::getMahasiswaSertifikat($user_id);
            foreach ($sertifikats as $value) {
                $dokumens = $dokumens->merge([$value->sertifikat->setAttribute('slug', $value->slug)]);
            }
        }
        // get data dokumen
        $dokumens = $dokumens->merge(Dokumen::getArchive($user_id));

        // get data dokumen dari mentions
        $dokumenMentioned = DokumenMention::where('user_mentioned', $user_id)->where("accepted", true)->get();
        foreach ($dokumenMentioned as $mention) {
            $dokumens = $dokumens->merge([$mention->dokumen]);
        }

        //get data pengumuman
        $dokumens = $dokumens->merge(Pengumuman::getArchiveForUser($jenis_user, $user_id));

        // get data pengumuman dari mentions 
        $pengumumanMentioned = PengumumanService::archiveFromMentions($user_id, $jenis_user);

        foreach ($pengumumanMentioned as $mention) {
            $dokumens = $dokumens->merge([$mention->dokumen]);
        }
        // Get data pengajuan surat
        $ajuanSurat = Surat::where(function ($query) {
            $query->where("status", "!=", "proses")->orWhereDate("created_at", "<", Carbon::today()->subDays(3));
        });
        if ($jenis_user != "admin") {
            $ajuanSurat->where("user_created", $user_id);
        }
        $dokumens = $dokumens->merge($ajuanSurat->get());

        return view('doc.arsip', compact('dokumens', 'jenis_user', 'user_id'));
    }
}
