<?php

namespace App\Services;

use App\Models\DistribusiDokumen\PenerimaSertifikat;
use App\Models\DistribusiDokumen\Sertifikat;
use App\Models\DistribusiDokumen\Surat;
use App\Models\DistribusiDokumen\SuratCuti;

class DistribusiDokumenService
{
    public static function getCountUsulan($userId, $jenisUser, $roleId, $prodiId)
    {
        $count = 0;
        if ($jenisUser != "mahasiswa") {
            $count += SuratCuti::countInProgresStatus($userId, $roleId);
            if ($jenisUser == "dosen" && $roleId  == 5) {
                $count += Sertifikat::countOnKajurAcc();
            } else {
                $count += Sertifikat::countOnProgressByUserOrAdmin($userId, $jenisUser);
            }
        }
        $count += DokumenService::countLatestByUser($userId);
        $count += DokumenService::countDokumenMention($userId);

        $ajuanSurat = Surat::where("status", "!=", "selesai");
        if ($jenisUser == "admin") {
            if ($roleId == 1) {
                $ajuanSurat->where("status", "staf_jurusan");
            } else {
                $ajuanSurat->where("role_handler", $roleId)->where("status", "!=", "ditolak");
            }
        } elseif ($jenisUser == "dosen") {
            $roleUser = $roleId;
            if ($roleUser === 5) {
                $ajuanSurat->where("status", "kajur");
            } elseif (in_array($roleUser, [6, 7, 8])) {
                $ajuanSurat->where(function ($query) use ($prodiId) {
                    $query->where("status", "kaprodi")->where("prodi_user", $prodiId);
                })->orWhere("user_created", $userId);
            } else {
                $ajuanSurat->where("user_created", $userId);
            }
        } else {
            $ajuanSurat->where("user_created", $userId);
        }
        $count += $ajuanSurat->count();
        return $count;
    }

    public static function getCountArsip($userId, $jenisUser)
    {
        $count = 0;
        if ($jenisUser != "mahasiswa") {
            $count += SuratCuti::countArchive($userId);
            // dd($count);
            $count += Sertifikat::countOnDoneByUserOrAdmin($userId, $jenisUser);
        }
        $count += PenerimaSertifikat::countDoneSertifikat($userId);
        $count += DokumenService::countAchive($userId);
        $count += DokumenService::countDokumenMentionArchive($userId);

        $ajuanSurat = Surat::where(function ($query) {
            $query->where(function ($query) {
                $query->where("status", "selesai")->orWhere("status", "ditolak");
            });
        });
        if ($jenisUser != "admin") {
            $ajuanSurat->where("user_created", $userId);
        }
        $count += $ajuanSurat->count();
        return $count;
    }
}
