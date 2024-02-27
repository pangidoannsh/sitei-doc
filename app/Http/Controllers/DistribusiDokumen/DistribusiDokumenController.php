<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\DokumenMention;
use App\Models\DistribusiDokumen\PenerimaSertifikat;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\DistribusiDokumen\Surat;
use App\Models\DistribusiDokumen\SuratCuti;
use App\Models\DistribusiSurat\Semester;
use App\Services\DistribusiDokumenService;
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

        // Get data pengajuan surat
        $ajuanSurat = Surat::where("status", "!=", "selesai");

        if ($jenis_user == "admin") {
            if (Auth::guard("web")->user()->role_id == 1) {
                $ajuanSurat->where("status", "staf_jurusan");
            } else {
                $ajuanSurat->where("role_handler", Auth::guard("web")->user()->role_id)->where("status", "!=", "ditolak");
            }
        } elseif ($jenis_user == "dosen") {
            $roleUser = Auth::guard("dosen")->user()->role_id;
            if ($roleUser === 5) {
                $ajuanSurat->where("status", "kajur");
            } elseif (in_array($roleUser, [6, 7, 8])) {
                $prodi = Auth::guard("dosen")->user()->prodi_id;
                $ajuanSurat->where(function ($query) use ($prodi) {
                    $query->where("status", "kaprodi")->where("prodi_user", $prodi);
                })->orWhere("user_created", $user_id);
            }
        } else {
            $ajuanSurat->where("user_created", $user_id);
        }
        $dokumens = $dokumens->merge($ajuanSurat->get());
        $semesters = Semester::select("nama")->get();
        $countArsip = DistribusiDokumenService::getCounArsip($user_id, $jenis_user);
        return view('doc.index', compact('dokumens', 'jenis_user', 'user_id', 'semesters', 'countArsip'));
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

        // Get data pengajuan surat
        $ajuanSurat = Surat::where(function ($query) {
            $query->where("status", "selesai")->orWhere("status", "ditolak");
        });
        if ($jenis_user != "admin") {
            $ajuanSurat->where("user_created", $user_id);
        }
        $dokumens = $dokumens->merge($ajuanSurat->get());

        $semesters = Semester::select("nama")->get();
        $roleUser = 0;
        $prodiuser = 0;
        if ($jenis_user == "admin") {
            $roleUser = Auth::guard("web")->user()->role_id;
        } else {
            $roleUser = Auth::guard("dosen")->user()->role_id;
            $prodiUser = Auth::guard("dosen")->user()->prodi_id;
        }

        $countUsulan = DistribusiDokumenService::getCountUsulan($user_id, $jenis_user, $roleUser, $prodiUser);
        return view('doc.arsip', compact('dokumens', 'jenis_user', 'user_id', 'semesters', 'countUsulan'));
    }
}
