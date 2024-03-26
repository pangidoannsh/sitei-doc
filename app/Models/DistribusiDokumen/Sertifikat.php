<?php

namespace App\Models\DistribusiDokumen;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;
    protected $table = "doc_sertifikat";
    protected $guarded = [];
    protected $with = ['dosen', 'admin', 'signed', 'rejectByDosen', 'rejectByAdmin'];
    protected $appends = ['jenisDokumen'];

    // Aksesor untuk jenisDokumen
    public function getJenisDokumenAttribute()
    {
        return "sertifikat";
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_created', 'nip');
    }

    public function signed()
    {
        return $this->belongsTo(Dosen::class, "sign_by", 'nip');
    }

    public function rejectByDosen()
    {
        return $this->belongsTo(Dosen::class, "rejected_by", "nip");
    }

    public function rejectByAdmin()
    {
        return $this->belongsTo(User::class, "rejected_by", "username");
    }

    public function admin()
    {
        return $this->belongsTo(User::class, "user_created", "username");
    }

    public function penerimas()
    {
        return $this->hasMany(PenerimaSertifikat::class, "sertifikat_id", "id");
    }

    public function logos()
    {
        return $this->hasMany(SertifikatLogo::class, "sertifikat_id", "id");
    }

    public static function getAllOnProgress()
    {
        return self::with("penerimas")->where("status", "!=", "selesai")->where("status", "!=", "ditolak")->get();
    }

    public static function countAllOnProgress()
    {
        return self::with("penerimas")->where("status", "!=", "selesai")->where("status", "!=", "ditolak")->count();
    }

    public static function getOnProgressByUserOrAdmin($userId, $jenisUser)
    {
        $sertifQuery = self::with("penerimas")->where("status", "!=", "selesai")->where("status", "!=", "ditolak");
        if ($jenisUser != "admin") {
            $sertifQuery->where(function ($query) use ($userId) {
                $query->where("user_created", $userId)->orWhere("sign_by", $userId);
            });
        }
        return $sertifQuery->get();
    }
    public static function countOnProgressByUserOrAdmin($userId, $jenisUser)
    {
        $sertifQuery = self::with("penerimas")->where("status", "!=", "selesai")->where("status", "!=", "ditolak");
        if ($jenisUser != "admin") {
            $sertifQuery->where(function ($query) use ($userId) {
                $query->where("user_created", $userId)->orWhere("sign_by", $userId);
            });
        }
        return $sertifQuery->count();
    }
    private static function queryAllDone()
    {
        return self::with("penerimas")->where("status", "selesai")->orWhere("status", "ditolak");
    }
    public static function getAllOnDone()
    {
        return self::queryAllDone()->get();
    }
    public static function countAllOnDone()
    {
        return self::queryAllDone()->count();
    }

    private static function queryDoneByProdi($prodiId)
    {
        return self::with("penerimas")->where(function ($query) {
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
        return self::queryDoneByProdi($prodiId)->get();
    }
    public static function countOnDoneByProdi($prodiId)
    {
        return self::queryDoneByProdi($prodiId)->count();
    }
    public static function getOnDoneByUserOrAdmin($userId, $jenisUser)
    {
        $query = self::with("penerimas")->where(function ($queryStatus) {
            $queryStatus->where("status", "selesai")->orWhere("status", "ditolak");
        });
        if ($jenisUser != "admin") {
            $query->where("user_created", $userId);
        }
        return $query->get();
    }

    public static function countOnDoneByUserOrAdmin($userId, $jenisUser)
    {
        $query = self::with("penerimas")->where(function ($query) {
            $query->where("status", "selesai")->orWhere("status", "ditolak");
        });
        if ($jenisUser != "admin") {
            $query->where("user_created", $userId);
        }
        return $query->count();
    }

    public static function getOnKajurAcc()
    {
        return self::with("penerimas")->where("status", "kajur")->get();
    }
    public static function countOnKajurAcc()
    {
        return self::with("penerimas")->where("status", "kajur")->count();
    }
}
