<?php

namespace App\Services;

use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\DokumenMention;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DokumenService
{
    public static function countLatestByUser($userId)
    {
        return Dokumen::where('user_created', $userId)
            ->whereDate("created_at", ">=", Carbon::today()->subDays(5))->count();
    }
    public static function countAchive($userId)
    {
        return Dokumen::where('user_created', $userId)
            ->whereDate("created_at", "<", Carbon::today()->subDays(5))->count();
    }
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

    public static function countDokumenMention($userId)
    {
        return DokumenMention::where('user_mentioned', $userId)->where("accepted", false)->count();
    }
    public static function countDokumenMentionArchive($userId)
    {
        return DokumenMention::where('user_mentioned', $userId)->where("accepted", true)->count();
    }
}
