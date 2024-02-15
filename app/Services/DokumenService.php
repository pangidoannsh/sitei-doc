<?php

namespace App\Services;

use App\Models\DistribusiDokumen\DokumenMention;
use Illuminate\Http\Request;

class DokumenService
{
    public static function saveMentions(Request $request, $dokumenId)
    {
        //  Dosen
        if (is_array($request->dosen)) {
            foreach ($request->dosen as $mention) {
                DokumenMention::create([
                    'dokumen_id' => $dokumenId,
                    'user_mentioned' => $mention,
                    'jenis_user' => 'dosen'
                ]);
            }
        }
        //  Staf
        if (is_array($request->staf)) {
            foreach ($request->staf as $mention) {
                DokumenMention::create([
                    'dokumen_id' => $dokumenId,
                    'user_mentioned' => $mention,
                    'jenis_user' => 'admin'
                ]);
            }
        }

        // Mahasiswa
        if (is_array($request->mahasiswa)) {
            foreach ($request->mahasiswa as $mentionId) {
                DokumenMention::create([
                    'dokumen_id' => $dokumenId,
                    'user_mentioned' => $mentionId,
                    'jenis_user' => 'mahasiswa'
                ]);
            }
        }
    }
}
