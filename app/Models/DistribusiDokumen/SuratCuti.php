<?php

namespace App\Models\DistribusiDokumen;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratCuti extends Model
{
    use HasFactory;
    protected $table = "doc_surat_cuti";
    protected $guarded = [];
    protected $with = ['dosen', 'admin'];
    protected $appends = ['jenisDokumen'];

    // Aksesor untuk jenisDokumen
    public function getJenisDokumenAttribute()
    {
        return "surat_cuti";
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_created', 'nip');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, "user_created", "username");
    }

    public static function getInProgresStatus($userId, $roleId)
    {
        if ($roleId == 5) {
            $query = self::where("status", "proses");
        } else {
            $query = self::where(function ($statusQuery) {
                $statusQuery->where("status", "proses")->orWhere("status", "staf_jurusan");
            })->where("user_created", $userId);
        }
        return $query->get();
    }
    public static function countInProgresStatus($userId, $roleId)
    {
        if ($roleId == 5) {
            $query = self::where("status", "proses");
        } else {
            $query = self::where(function ($statusQuery) {
                $statusQuery->where("status", "proses")->orWhere("status", "staf_jurusan");
            })->where("user_created", $userId);
        }
        return $query->count();
    }

    public static function getArchive($userId)
    {
        return self::where("user_created", $userId)->where(function ($status) {
            $status->where("status", "diterima")->orWhere("status", "ditolak");
        })->get();
    }

    public static function getAllArchive()
    {
        return self::where("status", "diterima")->orWhere("status", "ditolak")->get();
    }
    public static function countAllArchive()
    {
        return self::where("status", "diterima")->orWhere("status", "ditolak")->count();
    }

    static function queryAllArchiveByProdi($prodiId)
    {
        return self::where(function ($query) {
            $query->where("status", "diterima")->orWhere("status", "ditolak");
        })->where(function ($query) use ($prodiId) {
            $query->whereHas("dosen", function ($has) use ($prodiId) {
                $has->where("prodi_id", $prodiId);
            })->orWhereHas("admin", function ($has) use ($prodiId) {
                $has->where("role_id", ($prodiId + 1));
            });
        });
    }

    public static function getAllArchiveByProdi($prodiId)
    {
        return self::queryAllArchiveByProdi($prodiId)->get();
    }
    public static function countAllArchiveByProdi($prodiId)
    {
        return self::queryAllArchiveByProdi($prodiId)->count();
    }
    public static function countArchive($userId)
    {
        return self::where("user_created", $userId)->where(function ($status) {
            $status->where("status", "diterima")->orWhere("status", "ditolak");
        })->count();
    }
}
