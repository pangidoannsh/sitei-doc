<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\PenerimaSertifikat;
use Illuminate\Http\Request;

class PenerimaSertifikatController extends Controller
{
    public function show($slug)
    {
        $data = PenerimaSertifikat::with("sertifikat")->where("slug", $slug)->first();
        return view("doc.sertifikat.penerima", compact("data"));
    }
}
