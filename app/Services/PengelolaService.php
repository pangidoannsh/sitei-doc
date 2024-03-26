<?php

namespace App\Services;

use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\DistribusiDokumen\Surat;
use App\Models\DistribusiDokumen\SuratCuti;
use Carbon\Carbon;

class PengelolaService
{
    public static function allArchive()
    {
        $archives = collect([]);

        // DOKUMEN
        $archives = $archives->merge(Dokumen::all());
        // SERTIFIKAT
        $archives = $archives->merge(Sertifikat::getAllOnDone());
        // SURAT
        $archives = $archives->merge(SuratService::getAllDone());
        // SURAT CUTI
        $archives = $archives->merge(SuratCuti::getAllArchive());

        return $archives;
    }
    public static function countAllArchive()
    {
        $archives = 0;

        // DOKUMEN
        $archives += Dokumen::count();
        // SERTIFIKAT
        $archives += Sertifikat::countAllOnDone();
        // SURAT
        $archives += SuratService::countAllDone();
        // SURAT CUTI
        $archives += SuratCuti::countAllArchive();

        return $archives;
    }

    public static function archiveByProdi($prodiId)
    {
        $archives = collect([]);

        // DOKUMEN
        $archives = $archives->merge(self::queryArhiveDokumenByProdi($prodiId)->get());
        // SERTIFIKAT
        $archives = $archives->merge(Sertifikat::onDoneByProdi($prodiId));
        // SURAT
        $archives = $archives->merge(SuratService::onDoneByProdi($prodiId));

        // SURAT CUTI
        $archives = $archives->merge(SuratCuti::getAllArchiveByProdi($prodiId));

        return $archives;
    }

    public static function countArhciveByProdi($prodiId)
    {
        $countArchives = 0;
        $countArchives += self::queryArhiveDokumenByProdi($prodiId)->count();
        $countArchives += Sertifikat::countOnDoneByProdi($prodiId);
        $countArchives += SuratService::countOnDoneByProdi($prodiId);
        $countArchives += SuratCuti::countAllArchiveByProdi($prodiId);

        return $countArchives;
    }

    static function queryArhiveDokumenByProdi($prodiId)
    {
        return Dokumen::where(function ($query) use ($prodiId) {
            $query->whereHas("dosen", function ($has) use ($prodiId) {
                $has->where("prodi_id", $prodiId);
            })->orWhereHas("admin", function ($has) use ($prodiId) {
                $has->where("role_id", ($prodiId + 1));
            });
        });
    }
}
