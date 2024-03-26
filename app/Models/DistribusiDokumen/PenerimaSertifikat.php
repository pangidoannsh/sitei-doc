<?php

namespace App\Models\DistribusiDokumen;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaSertifikat extends Model
{
    use HasFactory;
    protected $table = "doc_penerima_sertifikat";
    protected $guarded = [];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_penerima', 'nip');
    }
    public function staf()
    {
        return $this->belongsTo(User::class, 'user_penerima', 'username');
    }
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'user_penerima', 'nim');
    }
    public function sertifikat()
    {
        return $this->belongsTo(Sertifikat::class, 'sertifikat_id');
    }

    public static function getDoneSertifikat($userId)
    {
        return self::where("user_penerima", $userId)->whereHas("sertifikat", function ($query) {
            $query->where("status", "selesai");
        })->get();
    }

    public static function countDoneSertifikat($userId)
    {
        return self::where("user_penerima", $userId)->whereHas("sertifikat", function ($query) {
            $query->where("status", "selesai");
        })->count();
    }
}
