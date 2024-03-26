<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Semester extends Model
{
    use HasFactory;
    protected $table = "semester";
    protected $guarded = ['id'];

    public static function getSimpleSemester()
    {
        return self::select('id', DB::raw("CONCAT(semester, ' ', tahun_ajaran) AS nama"))->get();
    }
}
