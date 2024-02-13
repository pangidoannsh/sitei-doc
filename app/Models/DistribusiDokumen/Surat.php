<?php

namespace App\Models\DistribusiDokumen;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;
    protected $table = "doc_surat";
    protected $guarded = [];
    protected $with = ["dosen", "mahasiswa"];
    protected $appends = ['jenisDokumen'];

    // Aksesor untuk jenisDokumen
    public function getJenisDokumenAttribute()
    {
        return "surat";
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_created', 'nip');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'user_created', 'nim');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'user_handler', 'username');
    }
}
