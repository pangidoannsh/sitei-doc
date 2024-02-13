<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendaftaranKP extends Model
{
    protected $table = 'pendaftaran_kp';
    protected $guarded = [];

    // public function pendaftaran($nip, $id)
    // {
    //     $dosen_pembimbing = PendaftaranKP::where('dosen_pembimbing', $nip)->where('id', $id)->count();

    //     if ($dosen_pembimbing == 0) {
    //     //     $penilaian_penguji = PenilaianKPPenguji::where('penguji_nip', $nip)->where('penjadwalan_kp_id', $id)->count();

    //     //     if ($penilaian_penguji > 0) {
    //     //         return true;
    //     //     } else {
    //     //         return false;
    //     //     }
    //     // } else {
    //     //     return true;
    //     }
    // }


    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }
    public function dosen_pembimbingkp()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_nip', 'nip');
    }
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'id');
    }
    public function konsentrasi()
    {
        return $this->belongsTo(Konsentrasi::class, 'konsentrasi_id', 'id');
    
    }
    public function pembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_nip', 'nip');
    }
    
  


}
