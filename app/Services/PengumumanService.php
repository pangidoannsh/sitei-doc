<?php

namespace App\Services;

use App\Models\DistribusiDokumen\Pengumuman;
use App\Models\DistribusiDokumen\PengumumanMention;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanService
{
    public static function getFromMentions($userId, $jenisUser)
    {
        // get data pengumuman dari mentions
        $pengumumanMentioned = PengumumanMention::whereHas('dokumen', function ($query) {
            $query->whereDate('tgl_batas_pengumuman', '>=', Carbon::today());
        });

        if ($jenisUser == "mahasiswa") {
            $prodiId = Auth::guard("mahasiswa")->user()->prodi_id;
            $angkatan = Auth::guard("mahasiswa")->user()->angkatan;
            switch ($prodiId) {
                case 1:
                    $userMentionId = "d3te_all";
                    $fromAngkatan = "d3te_$angkatan";
                    break;
                case 2:
                    $userMentionId = "s1te_all";
                    $fromAngkatan = "s1te_$angkatan";
                    break;
                case 3:
                    $userMentionId = "s1ti_all";
                    $fromAngkatan = "s1ti_$angkatan";
                    break;

                default:
                    $userMentionId = "";
                    $fromAngkatan = "";
                    break;
            }
            $pengumumanMentioned->where(function ($query) use ($userId, $userMentionId, $fromAngkatan) {
                $query->where('user_mentioned', $userId)->orWhere("user_mentioned", $userMentionId)->orWhere("user_mentioned", $fromAngkatan);
            });
        } else {
            $pengumumanMentioned->where('user_mentioned', $userId);
        }

        return $pengumumanMentioned->get();
    }
    public static function getCountFromMentions($userId, $jenisUser)
    {
        // get data pengumuman dari mentions
        $pengumumanMentioned = PengumumanMention::whereHas('dokumen', function ($query) {
            $query->whereDate('tgl_batas_pengumuman', '>=', Carbon::today());
        });

        if ($jenisUser == "mahasiswa") {
            $prodiId = Auth::guard("mahasiswa")->user()->prodi_id;
            $angkatan = Auth::guard("mahasiswa")->user()->angkatan;
            switch ($prodiId) {
                case 1:
                    $userMentionId = "d3te_all";
                    $fromAngkatan = "d3te_$angkatan";
                    break;
                case 2:
                    $userMentionId = "s1te_all";
                    $fromAngkatan = "s1te_$angkatan";
                    break;
                case 3:
                    $userMentionId = "s1ti_all";
                    $fromAngkatan = "s1ti_$angkatan";
                    break;

                default:
                    $userMentionId = "";
                    $fromAngkatan = "";
                    break;
            }
            $pengumumanMentioned->where(function ($query) use ($userId, $userMentionId, $fromAngkatan) {
                $query->where('user_mentioned', $userId)->orWhere("user_mentioned", $userMentionId)->orWhere("user_mentioned", $fromAngkatan);
            });
        } else {
            $pengumumanMentioned->where('user_mentioned', $userId);
        }

        return $pengumumanMentioned->count();
    }
    public static function archiveFromMentions($userId, $jenisUser)
    {
        // get data pengumuman dari mentions
        $pengumumanMentioned = PengumumanMention::whereHas('dokumen', function ($query) {
            $query->whereDate('tgl_batas_pengumuman', '<', Carbon::today());
        });

        if ($jenisUser == "mahasiswa") {
            $prodiId = Auth::guard("mahasiswa")->user()->prodi_id;
            $angkatan = Auth::guard("mahasiswa")->user()->angkatan;
            switch ($prodiId) {
                case 1:
                    $userMentionId = "d3te_all";
                    $fromAngkatan = "d3te_$angkatan";
                    break;
                case 2:
                    $userMentionId = "s1te_all";
                    $fromAngkatan = "s1te_$angkatan";
                    break;
                case 3:
                    $userMentionId = "s1ti_all";
                    $fromAngkatan = "s1ti_$angkatan";
                    break;

                default:
                    $userMentionId = "";
                    $fromAngkatan = "";
                    break;
            }
            $pengumumanMentioned->where(function ($query) use ($userId, $userMentionId, $fromAngkatan) {
                $query->where('user_mentioned', $userId)->orWhere("user_mentioned", $userMentionId)->orWhere("user_mentioned", $fromAngkatan);
            });
        } else {
            $pengumumanMentioned->where('user_mentioned', $userId);
        }

        return $pengumumanMentioned->get();
    }
    public static function getCountArchiveFromMentions($userId, $jenisUser)
    {
        // get data pengumuman dari mentions
        $pengumumanMentioned = PengumumanMention::whereHas('dokumen', function ($query) {
            $query->whereDate('tgl_batas_pengumuman', '<', Carbon::today());
        });

        if ($jenisUser == "mahasiswa") {
            $prodiId = Auth::guard("mahasiswa")->user()->prodi_id;
            $angkatan = Auth::guard("mahasiswa")->user()->angkatan;
            switch ($prodiId) {
                case 1:
                    $userMentionId = "d3te_all";
                    $fromAngkatan = "d3te_$angkatan";
                    break;
                case 2:
                    $userMentionId = "s1te_all";
                    $fromAngkatan = "s1te_$angkatan";
                    break;
                case 3:
                    $userMentionId = "s1ti_all";
                    $fromAngkatan = "s1ti_$angkatan";
                    break;

                default:
                    $userMentionId = "";
                    $fromAngkatan = "";
                    break;
            }
            $pengumumanMentioned->where(function ($query) use ($userId, $userMentionId, $fromAngkatan) {
                $query->where('user_mentioned', $userId)->orWhere("user_mentioned", $userMentionId)->orWhere("user_mentioned", $fromAngkatan);
            });
        } else {
            $pengumumanMentioned->where('user_mentioned', $userId);
        }

        return $pengumumanMentioned->count();
    }

    public static function saveMentions(Request $request, $pengumumanId)
    {
        if (!$request->has("select_all_dosen")) {
            $dosenMentions = $request->input("dosen");
            // Create Data Mention
            if (is_array($dosenMentions)) {
                foreach ($dosenMentions as $mention) {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumumanId,
                        'user_mentioned' => $mention,
                        'jenis_user' => 'dosen'
                    ]);
                }
            }
        }

        if (!$request->has("select_all_staf")) {
            $stafMentions = $request->input("staf");
            // Create Data Mention
            if (is_array($stafMentions)) {
                foreach ($stafMentions as $mention) {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumumanId,
                        'user_mentioned' => $mention,
                        'jenis_user' => 'admin'
                    ]);
                }
            }
        }

        if (!$request->has("select_all_mahasiswa")) {
            // D3 Teknik Elektro
            if ($request->has("d3te")) {
                if (!$request->has("select_all_d3te")) {
                    if ($request->has("d3te_angkatan")) {
                        foreach ($request->d3te_angkatan as $angkatan) {
                            PengumumanMention::create([
                                'pengumuman_id' => $pengumumanId,
                                'user_mentioned' => "d3te_" . $angkatan,
                                'jenis_user' => 'angkatan'
                            ]);
                        }
                    }
                    foreach ($request->d3te as $angkatan => $mahasiswas) {
                        if (!in_array($angkatan, $request->d3te_angkatan ?? [])) {
                            foreach ($mahasiswas as $nim) {
                                PengumumanMention::create([
                                    'pengumuman_id' => $pengumumanId,
                                    'user_mentioned' => $nim,
                                    'jenis_user' => 'mahasiswa'
                                ]);
                            }
                        }
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumumanId,
                        'user_mentioned' => "d3te_all",
                        'jenis_user' => 'angkatan'
                    ]);
                }
            }
            // S1 Teknik Elektro
            if ($request->has("s1te")) {
                if (!$request->has("select_all_s1te")) {
                    if ($request->has("s1te_angkatan")) {
                        foreach ($request->s1te_angkatan as $angkatan) {
                            PengumumanMention::create([
                                'pengumuman_id' => $pengumumanId,
                                'user_mentioned' => "s1te_" . $angkatan,
                                'jenis_user' => 'angkatan'
                            ]);
                        }
                    }
                    foreach ($request->s1te as $angkatan => $mahasiswas) {
                        foreach ($mahasiswas as $nim) {
                            if (!in_array($angkatan, $request->s1te_angkatan ?? [])) {
                                PengumumanMention::create([
                                    'pengumuman_id' => $pengumumanId,
                                    'user_mentioned' => $nim,
                                    'jenis_user' => 'mahasiswa'
                                ]);
                            }
                        }
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumumanId,
                        'user_mentioned' => "s1te_all",
                        'jenis_user' => 'angkatan'
                    ]);
                }
            }
            // S1 Teknik Informatika
            if ($request->has("s1ti")) {
                if (!$request->has("select_all_s1ti")) {
                    if ($request->has("s1ti_angkatan")) {
                        foreach ($request->s1ti_angkatan as $angkatan) {
                            PengumumanMention::create([
                                'pengumuman_id' => $pengumumanId,
                                'user_mentioned' => "s1ti_" . $angkatan,
                                'jenis_user' => 'angkatan'
                            ]);
                        }
                    }
                    foreach ($request->s1ti as $angkatan => $mahasiswas) {
                        foreach ($mahasiswas as $nim) {
                            if (!in_array($angkatan, $request->s1ti_angkatan ?? [])) {
                                PengumumanMention::create([
                                    'pengumuman_id' => $pengumumanId,
                                    'user_mentioned' => $nim,
                                    'jenis_user' => 'mahasiswa'
                                ]);
                            }
                        }
                    }
                } else {
                    PengumumanMention::create([
                        'pengumuman_id' => $pengumumanId,
                        'user_mentioned' => "s1ti_all",
                        'jenis_user' => 'angkatan'
                    ]);
                }
            }
        }
    }

    static function queryCurrentByProdi($prodi)
    {
        return Pengumuman::whereDate('tgl_batas_pengumuman', '>=', Carbon::today())
            ->where(function ($query) use ($prodi) {
                $query->whereHas("dosen", function ($has) use ($prodi) {
                    $has->where("prodi_id", $prodi);
                })->orWhereHas("admin", function ($has) use ($prodi) {
                    $has->where("role_id", ($prodi + 1));
                });
            });
    }

    public static function getCurrentByProdi($prodi)
    {
        return self::queryCurrentByProdi($prodi)->get();
    }
    public static function countCurrentByProdi($prodi)
    {
        return self::queryCurrentByProdi($prodi)->count();
    }

    static function queryArchiveByProdi($prodi)
    {
        return Pengumuman::whereDate('tgl_batas_pengumuman', '<', Carbon::today())
            ->where(function ($query) use ($prodi) {
                $query->whereHas("dosen", function ($has) use ($prodi) {
                    $has->where("prodi_id", $prodi);
                })->orWhereHas("admin", function ($has) use ($prodi) {
                    $has->where("role_id", ($prodi + 1));
                });
            });
    }

    public static function getArchiveByProdi($prodi)
    {
        return self::queryArchiveByProdi($prodi)->get();
    }
    public static function countArchiveByProdi($prodi)
    {
        return self::queryArchiveByProdi($prodi)->count();
    }
}
