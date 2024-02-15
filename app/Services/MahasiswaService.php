<?php

namespace App\Services;

use App\Models\Mahasiswa;

class MahasiswaService
{
    public static function groupByProdiAngkatan()
    {
        return Mahasiswa::select("nim", "nama", "prodi_id", "angkatan")
            ->orderBy("angkatan", "desc")
            ->get()
            ->groupBy('prodi_id')
            ->map(function ($groupedAngkatan) {
                return $groupedAngkatan->groupBy('angkatan')->take(7);
            });
    }
}
