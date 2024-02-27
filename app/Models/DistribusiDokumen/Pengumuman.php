<?php

namespace App\Models\DistribusiDokumen;

use App\Models\Dosen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    protected $table = "doc_pengumuman";
    protected $guarded = [];
    protected $with = ['mentions', 'dosen', 'admin'];
    protected $appends = ['jenisDokumen'];

    // Aksesor untuk jenisDokumen
    public function getJenisDokumenAttribute()
    {
        return "pengumuman";
    }

    public function mentions()
    {
        return $this->hasMany(PengumumanMention::class, 'pengumuman_id', 'id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'user_created', 'nip');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, "user_created", "username");
    }

    private static function getTargetByUserType($jenisUser)
    {
        switch ($jenisUser) {
            case 'dosen':
                return "for_all_dosen";
                break;
            case 'admin':
                return "for_all_staf";
                break;
            case 'mahasiswa':
                return "for_all_mahasiswa";
                break;
            default:
                return "";
        }
    }
    public static function getLatestPengumuman()
    {
        return self::whereDate('tgl_batas_pengumuman', '>=', Carbon::today())->get();
    }
    public static function getForUser($jenisUser, $userId)
    {
        $for = self::getTargetByUserType($jenisUser);
        return self::where(function ($query) use ($userId, $for) {
            $query->where('user_created', $userId);
            if (!empty($for)) {
                $query->orWhere($for, true); //ketika user adalah pembuat ATAU user adalah bagian dari for
            }
        })->whereDate('tgl_batas_pengumuman', '>=', Carbon::today())->get();
    }

    public static function getArchiveForUser($jenisUser, $userId)
    {
        $for = self::getTargetByUserType($jenisUser);
        return self::where(function ($query) use ($userId, $for) {
            $query->where('user_created', $userId);
            if (!empty($for)) {
                $query->orWhere($for, true); //ketika user adalah pembuat ATAU user adalah bagian dari for
            }
        })->whereDate('tgl_batas_pengumuman', '<', Carbon::today())->get();
    }

    public static function getCountLatest($jenisUser, $userId)
    {
        $for = self::getTargetByUserType($jenisUser);
        return self::where(function ($query) use ($userId, $for) {
            $query->where('user_created', $userId);
            if (!empty($for)) {
                $query->orWhere($for, true);
            }
        })->whereDate('tgl_batas_pengumuman', '>=', now())->count();
    }
    public static function getCountArchive($jenisUser, $userId)
    {
        $for = self::getTargetByUserType($jenisUser);
        return self::where(function ($query) use ($userId, $for) {
            $query->where('user_created', $userId);
            if (!empty($for)) {
                $query->orWhere($for, true);
            }
        })->whereDate('tgl_batas_pengumuman', '<', now())->count();
    }
}
