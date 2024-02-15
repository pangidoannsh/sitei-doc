<?php

namespace App\Models\DistribusiDokumen;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaSertifikat extends Model
{
    use HasFactory;
    protected $table = "doc_penerima_sertifikat";
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'user_penerima', 'nim');
    }
    public function sertifikat()
    {
        return $this->belongsTo(Sertifikat::class, 'sertifikat_id');
    }

    public static function getMahasiswaSertifikat($nim)
    {
        return self::where("user_penerima", $nim)->whereHas("sertifikat", function ($query) {
            $query->where("status", "selesai");
        })->get();
    }
}
