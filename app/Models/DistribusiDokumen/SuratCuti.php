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
        $query = self::where("status", "proses");
        if ($roleId != 5) {
            $query->where("user_created", $userId);
        }
        return $query->get();
    }
    public static function countInProgresStatus($userId, $roleId)
    {
        $query = self::where("status", "proses");
        if ($roleId != 5) {
            $query->where("user_created", $userId);
        }
        return $query->count();
    }

    public static function getArchive($userId)
    {
        return self::where("user_created", $userId)->where("status", "!=", "proses")->get();
    }
    public static function countArchive($userId)
    {
        return self::where("user_created", $userId)->where("status", "!=", "proses")->count();
    }
}
