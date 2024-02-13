<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\DokumenMention;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DokumenMentionController extends Controller
{
    public function accept($dokumen_id, $user_mentioned)
    {
        $dokumenMention = DokumenMention::where('dokumen_id', $dokumen_id)->where('user_mentioned', $user_mentioned)->first();
        $dokumenNama = $dokumenMention->dokumen->nama;
        $dokumenMention->accepted = true;
        $dokumenMention->update();
        Alert::success('Berhasil!', 'Dokumen "' . $dokumenNama . '" Dipindahkan ke Arsip')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }
    public function destroy($dokumen_id, $user_mentioned)
    {
        DokumenMention::where('dokumen_id', $dokumen_id)->where('user_mentioned', $user_mentioned)->delete();
        Alert::success('Berhasil!', 'Berhasil menolak dokumen yang dikirim')->showConfirmButton('Ok', '#28a745');
        return redirect()->route('doc.index');
    }
}
