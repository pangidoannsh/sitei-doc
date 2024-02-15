<?php

namespace App\Http\Controllers\DistribusiDokumen;

use App\Http\Controllers\Controller;
use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\Pengumuman;
use App\Models\DistribusiDokumen\Surat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengelolaController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $jenis_user = $request->jenis_user;
        $role = Auth::guard('dosen')->user()->role_id;
        switch ($role) {
            case 5:
                $dokumens = self::kajur();
                break;
            case 6:
                $dokumens = self::kaprodi(1);
                break;
            case 7:
                $dokumens = self::kaprodi(2);
                break;
            case 8:
                $dokumens = self::kaprodi(3);
                break;

            default:
                abort(403);
                break;
        }
        return view('doc.pengelola.index', compact('dokumens'));
    }

    static function kajur()
    {
        $dokumens = collect([]);
        // get data dokumen
        $dokumens = $dokumens->merge(Dokumen::getLatestDokumen());

        //get data pengumuman
        $dokumens = $dokumens->merge(Pengumuman::getLatestPengumuman());

        // Get data pengajuan surat
        $ajuanSurat = Surat::where("status", "!=", "selesai")->whereDate("created_at", ">=", Carbon::today()->subDays(3));
        $dokumens = $dokumens->merge($ajuanSurat->get());
        return $dokumens;
    }

    static function kaprodi($prodi)
    {
        $dokumens = collect([]);

        // Get data pengajuan surat
        $ajuanSurat = Surat::where('prodi_user', $prodi)->where("status", "!=", "selesai")->whereDate("created_at", ">=", Carbon::today()->subDays(3));
        $dokumens = $dokumens->merge($ajuanSurat->get());
        return $dokumens;
    }
}
