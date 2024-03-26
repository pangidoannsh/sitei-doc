<?php

namespace App\Services;

use App\Models\DistribusiDokumen\Surat;

class SuratService
{
    public static function getOnProgres($jenis_user, $user_id, $role_id, $prodi_id)
    {
        $ajuanSurat = Surat::where("status", "!=", "selesai");

        if ($jenis_user == "admin") {
            if ($role_id == 1) {
                $ajuanSurat->where("status", "staf_jurusan")->orWhere(function ($query) {
                    $query->where("status", "diterima")->where("jenis_user", "admin");
                });
            } else {
                $ajuanSurat->where(function ($query) use ($user_id, $role_id) {
                    $query->where("role_handler", $role_id)->orWhere("user_created", $user_id);
                })->where("status", "!=", "ditolak");
            }
        } elseif ($jenis_user == "dosen") {
            $roleUser = $role_id;
            if ($roleUser === 5) {
                $ajuanSurat->where("status", "kajur");
            } elseif (in_array($roleUser, [6, 7, 8])) {
                $prodi = $prodi_id;
                $ajuanSurat->where(function ($query) use ($prodi) {
                    $query->where("status", "kaprodi")->where("prodi_user", $prodi);
                })->orWhere("user_created", $user_id);
            } else {
                $ajuanSurat->where("user_created", $user_id);
            }
        } else {
            $ajuanSurat->where("user_created", $user_id);
        }

        return $ajuanSurat;
    }
    public static function getAllDone()
    {
        return Surat::where("status", "selesai")->orWhere("status", "ditolak")->get();
    }
    public static function countAllDone()
    {
        return Surat::where("status", "selesai")->orWhere("status", "ditolak")->count();
    }
    private static function queryOnDoneByProdi($prodiId)
    {
        return Surat::where(function ($query) {
            $query->where("status", "selesai")->orWhere("status", "ditolak");
        })->where(function ($query) use ($prodiId) {
            $query->whereHas("dosen", function ($has) use ($prodiId) {
                $has->where("prodi_id", $prodiId);
            })->orWhereHas("admin", function ($has) use ($prodiId) {
                $has->where("role_id", ($prodiId + 1));
            });
        });
    }

    public static function onDoneByProdi($prodiId)
    {
        return self::queryOnDoneByProdi($prodiId)->get();
    }

    public static function countOnDoneByProdi($prodiId)
    {
        return self::queryOnDoneByProdi($prodiId)->count();
    }
}
