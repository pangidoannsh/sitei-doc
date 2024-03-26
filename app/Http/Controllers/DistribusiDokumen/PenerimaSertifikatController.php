<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\PenerimaSertifikat;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PenerimaSertifikatController extends Controller
{
    public function show($slug)
    {
        $data = PenerimaSertifikat::with(['sertifikat', 'sertifikat.logos'])->where("slug", $slug)->first();
        if (!$data) abort(404);
        return view("doc.sertifikat.penerima", compact("data"));
    }

    public function download($slug)
    {
        $data = PenerimaSertifikat::with(['sertifikat', 'sertifikat.logos'])->where("slug", $slug)->first();
        $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate(URL::to('/sertifikat') . '/' . $slug));
        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper("a4", 'landscape');
        $pdf->loadView("doc.pdf.sertifikat", compact('data', 'qrcode'));
        return $pdf->download($data->sertifikat->nama . "_" . $data->slug . ".pdf");
        // return $pdf->stream($data->sertifikat->nama . "_" . $data->slug . '.pdf', array("Attachment" => false));
    }
}
