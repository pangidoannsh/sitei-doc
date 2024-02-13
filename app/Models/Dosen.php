<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\PendaftaranKP;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PendaftaranSkripsi;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Dosen extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $table = 'dosen';
    protected $guarded = [];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function pendaftaran_kp()
{
    return $this->hasMany(PendaftaranKP::class, 'dosen_pembimbing_nip', 'nip');
}

    public function pendaftarankp()
{
    return $this->belongsTo(PendaftaranKP::class, 'nip', 'dosen_pembimbing_nip');
}
    public function pendaftaran_skripsi1()
{
    return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_1_nip', 'nip');
}
    public function pendaftaran_skripsi2()
{
    return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_2_nip', 'nip');
}

public function mahasiswas()
{
    return $this->hasMany(Mahasiswa::class, 'dosen_pembimbing_nip');
}
public function mahasiswa()
{
    return $this->hasMany(Mahasiswa::class, 'mahasiswa_nim', 'nim');
}


// public function pendaftaranKP()
//     {
//         return $this->hasOne(PendaftaranKP::class, 'dosen_pembimbing_nip', 'nip');
//     }

    public function pendaftaranSkripsi1()
    {
        return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_1_nip', 'nip');
    }
    public function pendaftaranSkripsi2()
    {
        return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_2_nip', 'nip');
    }

    public function pendaftaranSkripsiPembimbing1()
    {
        return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_1_nip', 'nip');
    }

    public function pendaftaranSkripsiPembimbing2()
    {
        return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_2_nip', 'nip');
    }

public function pembimbingkp()
{
    return $this->hasMany(PendaftaranKP::class, 'dosen_pembimbing_nip', 'nip')
        ->where('status_kp', '<>', 'USULAN KP DITOLAK')
        ->where('status_kp', '<>', 'USULKAN KP ULANG')
        ->where('keterangan', '<>', 'Nilai KP Telah Keluar');
}

public function pembimbingkp_lulus()
{
    return $this->hasMany(PendaftaranKP::class, 'dosen_pembimbing_nip', 'nip')
        ->where('status_kp','KP SELESAI');
}

public function pembimbing1skripsi()
{
    return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_1_nip', 'nip')
       ->where('status_skripsi','<>', 'USULAN JUDUL DITOLAK')
       ->where('status_skripsi','<>', 'LULUS')
       ->where('status_skripsi','<>', 'USULKAN JUDUL ULANG')
       ->orderBy('status_skripsi', 'desc');
}
public function pembimbing2skripsi()
{
    return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_2_nip', 'nip')
       ->where('status_skripsi','<>', 'USULAN JUDUL DITOLAK')
       ->where('status_skripsi','<>', 'LULUS')
       ->where('status_skripsi','<>', 'USULKAN JUDUL ULANG')
       ->orderBy('status_skripsi', 'desc');
}

public function pembimbing1skripsi_lulus()
{
    return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_1_nip', 'nip')
       ->where('status_skripsi','LULUS');
}
public function pembimbing2skripsi_lulus()
{
    return $this->hasMany(PendaftaranSkripsi::class, 'pembimbing_2_nip', 'nip')
       ->where('status_skripsi','LULUS');
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
}
