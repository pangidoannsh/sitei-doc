<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\Pengumuman;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\DistribusiDokumen\Surat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengelolaController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;
        $role = Auth::guard('dosen')->user()->role_id;
        switch ($role) {
            case 5:
                list($dokumens, $countArsip) = self::kajur();
                break;
            case 6:
                list($dokumens, $countArsip) = self::kaprodi(1);
                break;
            case 7:
                list($dokumens, $countArsip) = self::kaprodi(2);
                break;
            case 8:
                list($dokumens, $countArsip) = self::kaprodi(3);
                break;
            default:
                abort(403);
                break;
        }
        return view('doc.pengelola.index', compact('dokumens', 'countArsip'));
    }

    public function arsip(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;
        $role = Auth::guard('dosen')->user()->role_id;
        switch ($role) {
            case 5:
                list($dokumens, $countUsulan) = self::kajurArsip();
                break;
            case 6:
                list($dokumens, $countUsulan) = self::kaprodiArsip(1);
                break;
            case 7:
                list($dokumens, $countUsulan) = self::kaprodiArsip(2);
                break;
            case 8:
                list($dokumens, $countUsulan) = self::kaprodiArsip(3);
                break;
            default:
                abort(403);
                break;
        }
        return view('doc.pengelola.arsip', compact('dokumens', 'countUsulan'));
    }

    static function kajur()
    {
        $dokumens = collect([]);
        $countArsip = 0;

        // DOKUMEN
        $dokumens = $dokumens->merge(Dokumen::getAllLatestDokumen());
        $countArsip += Dokumen::countAllArchive();
        // ===========

        // SERTIFIKAT
        $dokumens = $dokumens->merge(Sertifikat::getAllOnProgress());
        $countArsip += Sertifikat::countAllOnDone();
        // ===========

        // SURAT
        $dokumens = $dokumens->merge(Surat::where("status", "!=", "selesai")->where("status", "!=", "ditolak")->get());
        $countArsip += Surat::where("status", "selesai")->orWhere("status", "ditolak")->count();

        return [$dokumens, $countArsip];
    }

    static function kaprodi($prodi)
    {
        $dokumens = collect([]);
        $countArsip = 0;

        // DOKUMEN
        $dokumens = $dokumens->merge(Dokumen::where(function ($query) use ($prodi) {
            $query->whereHas("dosen", function ($has) use ($prodi) {
                $has->where("prodi_id", $prodi);
            });
        })->whereDate("created_at", ">=", Carbon::today()->subDays(5))->get());

        $countArsip += Dokumen::where(function ($query) use ($prodi) {
            $query->whereHas("dosen", function ($has) use ($prodi) {
                $has->where("prodi_id", $prodi);
            });
        })->whereDate("created_at", "<", Carbon::today()->subDays(5))->count();
        // ==================

        // SERTIFIKAT
        $dokumens = $dokumens->merge(
            Sertifikat::with("penerimas")->where("status", "!=", "selesai")->where("status", "!=", "ditolak")
                ->whereHas("dosen", function ($has) use ($prodi) {
                    $has->where("prodi_id", $prodi);
                })->get()
        );
        $countArsip += Sertifikat::with("penerimas")
            ->where(function ($query) {
                $query->where("status", "selesai")->orWhere("status", "ditolak");
            })
            ->whereHas("dosen", function ($has) use ($prodi) {
                $has->where("prodi_id", $prodi);
            })
            ->count();
        // ==================

        // SURAT
        $ajuanSurat = Surat::where('prodi_user', $prodi)->where("status", "!=", "selesai")->where("status", "!=", "ditolak");

        $countArsip += Surat::where('prodi_user', $prodi)->where("status", "selesai")->orWhere("status", "ditolak")->count();

        $dokumens = $dokumens->merge($ajuanSurat->get());
        return [$dokumens, $countArsip];
    }

    static function kajurArsip()
    {
        $dokumens = collect([]);
        $countUsulan = 0;

        // get data dokumen
        $dokumens = $dokumens->merge(Dokumen::getAllArchive());
        $countUsulan += Dokumen::countAllLatestDokumen();

        // Get data pengajuan surat
        $ajuanSurat = Surat::where("status", "selesai")->orWhere("status", "ditolak");
        $countUsulan += Surat::where("status", "!=", "selesai")->where("status", "!=", "ditolak")->count();

        $dokumens = $dokumens->merge($ajuanSurat->get());
        return [$dokumens, $countUsulan];
    }

    static function kaprodiArsip($prodi)
    {
        $dokumens = collect([]);
        $countUsulan = 0;

        // DOKUMEN
        $dokumens = $dokumens->merge(Dokumen::where(function ($query) use ($prodi) {
            $query->whereHas("dosen", function ($has) use ($prodi) {
                $has->where("prodi_id", $prodi);
            });
        })->whereDate("created_at", "<", Carbon::today()->subDays(5))->get());

        $countUsulan += Dokumen::where(function ($query) use ($prodi) {
            $query->whereHas("dosen", function ($has) use ($prodi) {
                $has->where("prodi_id", $prodi);
            });
        })->whereDate("created_at", ">=", Carbon::today()->subDays(5))->count();
        // ==================

        // SERTIFIKAT
        $dokumens = $dokumens->merge(
            Sertifikat::with("penerimas")
                ->where(function ($query) {
                    $query->where("status", "selesai")->orWhere("status", "ditolak");
                })
                ->whereHas("dosen", function ($has) use ($prodi) {
                    $has->where("prodi_id", $prodi);
                })
                ->get()
        );

        $countUsulan += Sertifikat::with("penerimas")->where("status", "!=", "selesai")->where("status", "!=", "ditolak")
            ->whereHas("dosen", function ($has) use ($prodi) {
                $has->where("prodi_id", $prodi);
            })->count();
        // ==================

        // SURAT
        $dokumens =  $dokumens->merge(Surat::where('prodi_user', $prodi)->where(function ($query) {
            $query->where("status", "selesai")->orWhere("status", "ditolak");
        })->get());

        $countUsulan += Surat::where('prodi_user', $prodi)->where("status", "!=", "selesai")->where("status", "!=", "ditolak")->count();
        return [$dokumens, $countUsulan];
    }
}
