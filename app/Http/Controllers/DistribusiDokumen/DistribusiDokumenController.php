<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\DokumenMention;
use App\Models\DistribusiDokumen\PenerimaSertifikat;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\DistribusiDokumen\Surat;
use App\Models\DistribusiDokumen\SuratCuti;
use App\Models\Semester;
use App\Services\DistribusiDokumenService;
use App\Services\PengelolaService;
use App\Services\SuratService;
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
        $user = Auth::guard($jenis_user == "admin" || $jenis_user == "plp" ? "web" : $jenis_user)->user();

        $dokumens = collect([]);
        // get data surat cuti
        if ($jenis_user != "mahasiswa") {
            if ($jenis_user == "dosen") {
                $dokumens = $dokumens->merge(SuratCuti::getInProgresStatus($user_id, $user->role_id));
            } else {
                if ($user->role_id == 1) {
                    $dokumens = $dokumens->merge(
                        SuratCuti::where("status", "staf_jurusan")
                            ->orWhere("user_created", $user_id)
                            ->get()
                    );
                } else {
                    $dokumens = $dokumens->merge(
                        SuratCuti::where(function ($query) {
                            $query->where("status", "staf_jurusan")->orWhere("status", "proses");
                        })->where("user_created", $user_id)->get()
                    );
                }
            }
            if ($jenis_user == "dosen" && $user->role_id  == 5) {
                $dokumens = $dokumens->merge(Sertifikat::getOnKajurAcc());
            } else {
                $dokumens = $dokumens->merge(Sertifikat::getOnProgressByUserOrAdmin($user_id, $jenis_user));
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
        $ajuanSurat = SuratService::getOnProgres(
            $jenis_user,
            $user_id,
            $user->role_id,
            $user->prodi_id ?? 0,
        );

        $dokumens = $dokumens->merge($ajuanSurat->get());
        $semesters = Semester::getSimpleSemester();

        $roleUser = $user->role_id ?? 0;
        $prodiUser = 0;

        if ($jenis_user == "admin") {
            // Role Id 2-4 adalah Staff Admin Prodi
            if (in_array($roleUser, range(2, 4))) {
                //Saat ini prodi_id dan role_id hanya beda 1 angka, contoh D3 TE id-nya 1 dan Staff Admin D3 TE itu id-nya 2
                $prodiUser = $roleUser - 1;
            }
        } elseif ($jenis_user == "dosen") {
            $prodiUser = $user->prodi_id;
        } else {
            $prodiUser = $user->prodi_id;
        }

        if (in_array($roleUser, range(1, 4))) {
            if ($roleUser == 1) {
                $countArsip = PengelolaService::allArchive()->count();
            } else {
                $countArsip = PengelolaService::countArhciveByProdi($prodiUser, $user_id);
            }
        } else {
            $countArsip = DistribusiDokumenService::getCountArsip($user_id, $jenis_user);
        }
        return view('doc.index', compact('dokumens', 'jenis_user', 'user_id', 'semesters', 'countArsip'));
    }


    public function arsip(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;
        $user = Auth::guard($jenis_user == "admin" || $jenis_user == "plp" ? "web" : $jenis_user)->user();
        $roleUser = $user->role_id ?? 0;
        $prodiUser = 0;

        if ($jenis_user == "admin") {
            // Role Id 2-4 adalah Staff Admin Prodi
            if (in_array($roleUser, range(2, 4))) {
                //Saat ini prodi_id dan role_id hanya beda 1 angka, contoh D3 TE id-nya 1 dan Staff Admin D3 TE itu id-nya 2
                $prodiUser = $roleUser - 1;
            }
        } elseif ($jenis_user == "dosen") {
            $prodiUser = $user->prodi_id;
        } else {
            $prodiUser = $user->prodi_id;
        }

        $dokumens = collect([]);
        if ($jenis_user == "admin" && in_array($user->role_id, range(1, 4))) {
            if ($user->role_id == 1) {
                $dokumens = $dokumens->merge(PengelolaService::allArchive());
            } else {
                $dokumens = $dokumens->merge(PengelolaService::archiveByProdi($prodiUser, $user_id));
            }
        } else {
            if ($jenis_user != "mahasiswa") {
                // get data surat cuti
                $dokumens = $dokumens->merge(SuratCuti::getArchive($user_id));
                $dokumens = $dokumens->merge(Sertifikat::getOnDoneByUserOrAdmin($user_id, $jenis_user));
            }
            $sertifikats = PenerimaSertifikat::getDoneSertifikat($user_id);
            foreach ($sertifikats as $value) {
                $dokumens = $dokumens->merge([$value->sertifikat->setAttribute('slug', $value->slug)->setAttribute('penerima_id', $value->user_penerima)]);
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
        }

        $semesters = Semester::getSimpleSemester();

        $countUsulan = DistribusiDokumenService::getCountUsulan($user_id, $jenis_user, $roleUser, $prodiUser);
        return view('doc.arsip', compact('dokumens', 'jenis_user', 'user_id', 'semesters', 'countUsulan'));
    }
}
