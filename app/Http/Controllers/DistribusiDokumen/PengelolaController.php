<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\Pengumuman;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\DistribusiDokumen\Surat;
use App\Models\Semester;
use App\Services\PengelolaService;
use App\Services\PengumumanService;
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
                $countArsip = PengelolaService::countAllArchive() + Pengumuman::whereDate('tgl_batas_pengumuman', '<', Carbon::today())->count();;
                list($dokumens, $countPengumuman) = self::kajur();
                break;
            case 6:
                list($dokumens, $countArsip, $countPengumuman) = self::kaprodi(1);
                break;
            case 7:
                list($dokumens, $countArsip, $countPengumuman) = self::kaprodi(2);
                break;
            case 8:
                list($dokumens, $countArsip, $countPengumuman) = self::kaprodi(3);
                break;
            default:
                abort(403);
                break;
        }
        $semesters = Semester::getSimpleSemester();
        return view('doc.pengelola.index', compact('dokumens', 'countArsip', 'countPengumuman', 'semesters'));
    }
    public function pengumuman(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;
        $role = Auth::guard('dosen')->user()->role_id;
        switch ($role) {
            case 5:
                $prodi = null;
                break;
            case 6:
                $prodi = 1;
                break;
            case 7:
                $prodi = 2;
                break;
            case 8:
                $prodi = 3;
                break;
            default:
                abort(403);
                break;
        }
        $countArsip = 0;
        $countUsulan = 0;

        if ($prodi) {
            $pengumumans = PengumumanService::getCurrentByProdi($prodi);

            $countArsip = PengelolaService::countArhciveByProdi($prodi, $user_id) + PengumumanService::countArchiveByProdi($prodi);

            $countUsulan += Dokumen::where(function ($query) use ($prodi) {
                $query->whereHas("dosen", function ($has) use ($prodi) {
                    $has->where("prodi_id", $prodi);
                });
            })->whereDate("created_at", ">=", Carbon::today()->subDays(5))->count();
            //=============


            $countUsulan += Sertifikat::where("status", "!=", "selesai")->where("status", "!=", "ditolak")
                ->whereHas("dosen", function ($has) use ($prodi) {
                    $has->where("prodi_id", $prodi);
                })->count();
            // =============

            // SURAT
            $countUsulan += Surat::where('prodi_user', $prodi)->where("status", "!=", "selesai")->where("status", "!=", "ditolak")->count();
            //==============
        } else {
            $pengumumans = Pengumuman::whereDate('tgl_batas_pengumuman', '>=', Carbon::today())->get();
            $countArsip = PengelolaService::countAllArchive();
            $countArsip += Pengumuman::whereDate('tgl_batas_pengumuman', '<', Carbon::today())->count();
            // get data dokumen
            $countUsulan += Dokumen::countAllLatestDokumen();

            // Sertifikat
            $countUsulan +=  Sertifikat::where("status", "!=", "selesai")->where("status", "!=", "ditolak")->count();

            // Get data pengajuan surat
            $countUsulan += Surat::where("status", "!=", "selesai")->where("status", "!=", "ditolak")->count();
        }
        $semesters = Semester::getSimpleSemester();

        return view('doc.pengelola.pengumuman', compact('pengumumans', 'countUsulan', 'countArsip', 'semesters'));
    }
    public function arsip(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;
        $role = Auth::guard('dosen')->user()->role_id;

        switch ($role) {
            case 5:
                $dokumens = PengelolaService::allArchive();
                list($countUsulan, $pengumumans, $countPengumuman) = self::kajurArsip();
                break;
            case 6:
                list($dokumens, $countUsulan, $pengumumans, $countPengumuman) = self::kaprodiArsip(1);
                break;
            case 7:
                list($dokumens, $countUsulan, $pengumumans, $countPengumuman) = self::kaprodiArsip(2);
                break;
            case 8:
                list($dokumens, $countUsulan, $pengumumans, $countPengumuman) = self::kaprodiArsip(3);
                break;
            default:
                abort(403);
                break;
        }
        $semesters = Semester::getSimpleSemester();

        return view('doc.pengelola.arsip', compact('dokumens', 'countUsulan', 'pengumumans', 'countPengumuman', 'semesters'));
    }

    public function indexPengumuman(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;
        $role = Auth::guard('dosen')->user()->role_id;
        switch ($role) {
            case 5:
                $prodi = null;
                break;
            case 6:
                $prodi = 1;
                break;
            case 7:
                $prodi = 2;
                break;
            case 8:
                $prodi = 3;
                break;
            default:
                abort(403);
                break;
        }

        if ($prodi) {
            $pengumumans = PengumumanService::getCurrentByProdi($prodi);
            $countArsip =  PengumumanService::countArchiveByProdi($prodi);
        } else {
            $pengumumans = Pengumuman::whereDate('tgl_batas_pengumuman', '>=', Carbon::today())->get();
            $countArsip = Pengumuman::whereDate('tgl_batas_pengumuman', '<', Carbon::today())->count();
        }
        $semesters = Semester::getSimpleSemester();

        return view('doc.pengumuman.pengelola.index', compact('pengumumans', 'countArsip', 'semesters'));
    }
    public function arsipPengumuman(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;
        $role = Auth::guard('dosen')->user()->role_id;
        switch ($role) {
            case 5:
                $prodi = null;
                break;
            case 6:
                $prodi = 1;
                break;
            case 7:
                $prodi = 2;
                break;
            case 8:
                $prodi = 3;
                break;
            default:
                abort(403);
                break;
        }

        if ($prodi) {
            $pengumumans = PengumumanService::getArchiveByProdi($prodi);
            $countPengumuman =  PengumumanService::countCurrentByProdi($prodi);
        } else {
            $pengumumans = Pengumuman::whereDate('tgl_batas_pengumuman', '<', Carbon::today())->get();
            $countPengumuman = Pengumuman::whereDate('tgl_batas_pengumuman', '>=', Carbon::today())->count();
        }
        $semesters = Semester::getSimpleSemester();

        return view('doc.pengumuman.pengelola.arsip', compact('pengumumans', 'countPengumuman', 'semesters'));
    }

    static function kajur()
    {
        $dokumens = collect([]);

        // DOKUMEN
        $dokumens = $dokumens->merge(Dokumen::getAllLatestDokumen());
        // ===========

        // SERTIFIKAT
        $dokumens = $dokumens->merge(Sertifikat::getAllOnProgress());
        // ===========

        // SURAT
        $dokumens = $dokumens->merge(Surat::where("status", "!=", "selesai")->where("status", "!=", "ditolak")->get());

        $countPengumuman = Pengumuman::whereDate('tgl_batas_pengumuman', '>=', Carbon::today())->count();
        return [$dokumens, $countPengumuman];
    }

    static function kaprodi($prodi)
    {
        $countArsip = PengelolaService::countArhciveByProdi($prodi) + PengumumanService::countArchiveByProdi($prodi);
        $dokumens = collect([]);

        // ==================
        $countPengumuman = PengumumanService::countCurrentByProdi($prodi);
        // SERTIFIKAT
        $dokumens = $dokumens->merge(
            Sertifikat::with("penerimas")->where("status", "!=", "selesai")->where("status", "!=", "ditolak")
                ->whereHas("dosen", function ($has) use ($prodi) {
                    $has->where("prodi_id", $prodi);
                })->get()
        );
        // ==================

        // SURAT
        $ajuanSurat = Surat::where('prodi_user', $prodi)->where("status", "!=", "selesai")->where("status", "!=", "ditolak");


        $dokumens = $dokumens->merge($ajuanSurat->get());
        return [$dokumens, $countArsip, $countPengumuman];
    }

    static function kajurArsip()
    {
        $countUsulan = 0;
        $countUsulan += Dokumen::countAllLatestDokumen();

        $pengumumans = Pengumuman::whereDate('tgl_batas_pengumuman', '<', Carbon::today())->get();

        $countPengumuman = Pengumuman::whereDate('tgl_batas_pengumuman', '>=', Carbon::today())->count();
        $countUsulan += Sertifikat::countAllOnProgress();
        $countUsulan += Surat::where("status", "!=", "selesai")->where("status", "!=", "ditolak")->count();
        return [$countUsulan, $pengumumans, $countPengumuman];
    }

    static function kaprodiArsip($prodi)
    {
        $dokumens = PengelolaService::archiveByProdi($prodi);
        $countUsulan = 0;
        // ==================

        $pengumumans = PengumumanService::getArchiveByProdi($prodi);
        $countPengumuman = PengumumanService::countCurrentByProdi($prodi);

        $countUsulan += Sertifikat::with("penerimas")->where("status", "!=", "selesai")->where("status", "!=", "ditolak")
            ->whereHas("dosen", function ($has) use ($prodi) {
                $has->where("prodi_id", $prodi);
            })->count();
        // ==================

        // SURAT
        $countUsulan += Surat::where('prodi_user', $prodi)->where("status", "!=", "selesai")->where("status", "!=", "ditolak")->count();

        return [$dokumens, $countUsulan, $pengumumans, $countPengumuman];
    }
}
