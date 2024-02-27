<?php

namespace App\Services;

use App\Models\Dosen;

class DosenService
{
    public static function getWithOriginalName()
    {
        return Dosen::select("nip", "nama", 'role_id')
            ->selectRaw('nip, nama, TRIM(BOTH "Dr. " FROM TRIM(BOTH "Ir. " FROM TRIM(BOTH "Prof. " FROM nama))) as nama_original')
            ->orderBy("nama_original")
            ->get();
    }
}
