<?php

namespace App\Models\DistribusiDokumen;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatLogo extends Model
{
    use HasFactory;
    protected $table = "doc_sertif_logo";
    protected $guarded = [];

    public function sertif()
    {
        return $this->belongsTo(Sertifikat::class, "sertifikat_id", "id");
    }
    public function logo()
    {
        return $this->belongsTo(Logo::class, "logo_id", "id");
    }
}
