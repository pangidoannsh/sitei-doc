<?php

namespace App\Models\DistribusiDokumen;

use App\Models\Dosen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;
    protected $table = "doc_dokumen";
    protected $guarded = [];
    protected $with = ['mentions', 'dosen', 'admin'];
    protected $appends = ['jenisDokumen'];

    public function getJenisDokumenAttribute()
    {
        return "dokumen";
    }

    public function mentions()
    {
        return $this->hasMany(DokumenMention::class, 'dokumen_id', 'id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_created', 'nip');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, "user_created", "username");
    }

    public static function getAllLatestDokumen()
    {
        return self::whereDate("created_at", ">=", Carbon::today()->subDays(5))->get();
    }
    public static function countAllLatestDokumen()
    {
        return self::whereDate("created_at", ">=", Carbon::today()->subDays(5))->count();
    }

    public static function getAllArchive()
    {
        return self::whereDate("created_at", "<", Carbon::today()->subDays(5))->get();
    }
    public static function countAllArchive()
    {
        return self::whereDate("created_at", "<", Carbon::today()->subDays(5))->count();
    }

    public static function getLatestByUser($userId)
    {
        return self::where('user_created', $userId)
            ->whereDate("created_at", ">=", Carbon::today()->subDays(5))->get();
    }

    public static function getArchive($userId)
    {
        return self::where('user_created', $userId)
            ->whereDate("created_at", "<", Carbon::today()->subDays(5))->get();
    }
}
