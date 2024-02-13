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
    protected $with = ['dosen', 'admin'];
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

    public function admin()
    {
        return $this->belongsTo(User::class, "user_created", "username");
    }
    public function penerimas()
    {
        return $this->hasMany(PenerimaSertifikat::class, "sertifikat_id", "id");
    }

    public static function getOnProgressByUserOrAdmin($userId, $jenisUser)
    {
        $query = self::with("penerimas")->where("is_done", false);
        if ($jenisUser != "admin") {
            $query->where("user_created", $userId);
        }
        return $query->get();
    }
    public static function getOnDoneByUserOrAdmin($userId, $jenisUser)
    {
        $query = self::with("penerimas")->where("is_done", true);
        if ($jenisUser != "admin") {
            $query->where("user_created", $userId);
        }
        return $query->get();
    }
}
