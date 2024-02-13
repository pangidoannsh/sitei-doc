<?php

namespace App\Models\DistribusiDokumen;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumumanMention extends Model
{
    use HasFactory;
    protected $table = "doc_pengumuman_mentions";
    protected $guarded = [];
    protected $with = ['dosen', 'mahasiswa', 'admin'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_mentioned', 'nip');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'user_mentioned', 'nim');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_mentioned', 'username');
    }

    public function dokumen()
    {
        return $this->belongsTo(Pengumuman::class, "pengumuman_id");
    }
}
