<?php

namespace App\Models\DistribusiDokumen;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;
    protected $table = "doc_logo";
    protected $guarded = [];
}
