<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Prodi;
use App\Models\Konsentrasi;
use App\Models\PendaftaranKP;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PendaftaranSkripsi;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Mahasiswa extends Authenticatable
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
    protected $table = 'mahasiswa';
    protected $guarded = [];
    protected $appends = ['jenisUser'];

    public function getJenisUserAttribute()
    {
        return "mahasiswa";
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function konsentrasi()
    {
        return $this->belongsTo(Konsentrasi::class);
    }

    public function pendaftarankp()
    {
        return $this->belongsTo(PendaftaranKP::class);
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
