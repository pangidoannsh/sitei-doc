<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $guarded = [];

    public static function getRolePengelola()
    {
        return Role::where("role.id", "<", 9)
            ->leftJoin("dosen", "dosen.role_id", "role.id")
            ->leftJoin("users", "users.role_id", "role.id")
            ->select(
                "role.id as id",
                "role.role_akses as akses",
                "dosen.nama as nama_dosen",
                "users.nama as nama_admin",
            )
            ->get();
    }
}
