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
use App\Models\Mahasiswa;
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
        if ($jenis_user == "mahasiswa") {
            $sertifikats = PenerimaSertifikat::getMahasiswaSertifikat($user_id);
            foreach ($sertifikats as $value) {
                $dokumens = $dokumens->merge([$value->sertifikat->setAttribute('slug', $value->slug)]);
            }
        } else {
            // get data surat cuti
            $dokumens = $dokumens->merge(SuratCuti::getInProgresStatus($user_id, optional(Auth::guard("dosen")->user())->role_id));
            $dokumens = $dokumens->merge(Sertifikat::getOnProgressByUserOrAdmin($user_id, $jenis_user));
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
            $pengumumanMentioned->where(function ($query) use ($user_id, $userMentionId) {
                $query->where('user_mentioned', $user_id)->orWhere("user_mentioned", $userMentionId);
            });
        } else {
            $pengumumanMentioned->where('user_mentioned', $user_id);
        }

        foreach ($pengumumanMentioned->get() as $mention) {
            $dokumens = $dokumens->merge([$mention->dokumen]);
        }

        // Get data pengajuan surat
        $ajuanSurat = Surat::where("status", "proses")->whereDate("created_at", ">=", Carbon::today()->subDays(3));
        if ($jenis_user != "admin") {
            $ajuanSurat->where("user_created", $user_id);
        }
        $dokumens = $dokumens->merge($ajuanSurat->get());

        return view('doc.index', compact('dokumens', 'jenis_user', 'dokumenMentioned', 'user_id'));
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
        $pengumumanMentioned = PengumumanMention::whereHas('dokumen', function ($query) {
            $query->whereDate('tgl_batas_pengumuman', '<', Carbon::today());
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
            $pengumumanMentioned->where(function ($query) use ($user_id, $userMentionId) {
                $query->where('user_mentioned', $user_id)->orWhere("user_mentioned", $userMentionId);
            });
        } else {
            $pengumumanMentioned->where('user_mentioned', $user_id);
        }

        foreach ($pengumumanMentioned->get() as $mention) {
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

    public function mahasiswa(Request $request)
    {
        $prodiId = $request->prodi;

        // Selecting specific columns from the Mahasiswa model
        $data = Mahasiswa::select("nim", "nama", "angkatan");

        // Applying a condition to filter data based on prodiId
        if ($prodiId && $prodiId != "all") {
            $data->where("prodi_id", $prodiId);
        }

        // Grouping the data by 'angkatan' and retrieving the results
        $groupedData = $data->orderBy("angkatan", "desc")->get()->groupBy('angkatan')->take(4);
        // Returning the grouped data as a JSON response
        return response()->json([
            'data' => $groupedData
        ]);
    }
}
