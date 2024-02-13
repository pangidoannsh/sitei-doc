<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjadwalanKP;
use App\Models\PendaftaranKP;
use App\Models\PenjadwalanSempro;
use App\Models\PenjadwalanSkripsi;
use App\Models\PendaftaranSkripsi;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {

        // $dosen = PenjadwalanSempro::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 0)->
        // orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 0)->
        // orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 0)->
        // orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 0)->
        // orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 0)->get();

        // $dosens = PenjadwalanSkripsi::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 0)->
        // orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 0)->
        // orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 0)->s
        // orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 0)->
        // orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 0)->get();

        // $dosenss = PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->
        // orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get();


        if (auth()->user()->role_id == 5) {
            return view('penilaian.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('status_seminar', 0)->where('waktu', '<>', null)->get(),
                'penjadwalan_sempros' => PenjadwalanSempro::where('status_seminar', 0)->where('waktu', '<>', null)->get(),
                'penjadwalan_skripsis' => PenjadwalanSkripsi::where('status_seminar', 0)->where('waktu', '<>', null)->get(),

                //JUMLAH SEMINAR
                'jml_seminar_kp' => PenjadwalanKP::where('status_seminar', '0')->where('waktu', '<>', null)->orderBy('tanggal', 'ASC')->count(),
                'jml_sempro' => PenjadwalanSempro::where('status_seminar', 0)->where('waktu', '<>', null)->count(),
                'jml_sidang' => PenjadwalanSkripsi::where('status_seminar', 0)->where('waktu', '<>', null)->count(),


                //JUMLAH KP PRODI
                'jml_prodi_kp' => PendaftaranKP::where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('created_at', 'desc')->count(),

                //JUMLAH SKRIPSI PRODI
                'jml_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT KP 
                'jml_riwayat_prodi_kp' => PendaftaranKP::where('status_kp', 'KP SELESAI')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT SKRIPSI 
                'jml_riwayat_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', 'LULUS')->orderBy('created_at', 'desc')->count(),
                //JUMLAH RIWAYAT SEMINAR
                'jml_riwayat_seminar_kp' => PenjadwalanKP::where('status_seminar', '1')->count(),
                'jml_riwayat_sempro' => PenjadwalanSempro::where('status_seminar', '1')->count(),
                'jml_riwayat_skripsi' => PenjadwalanSkripsi::where('status_seminar', '3')->count(),
            ]);
        }
        if (auth()->user()->role_id == 6) {
            return view('penilaian.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('prodi_id', '1')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),
                'penjadwalan_sempros' => PenjadwalanSempro::where('prodi_id', '1')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),
                'penjadwalan_skripsis' => PenjadwalanSkripsi::where('prodi_id', '1')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),

                //JUMLAH SEMINAR
                'jml_seminar_kp' => PenjadwalanKP::where('status_seminar', '0')->where('waktu', '<>', null)->where('prodi_id', 1)->orderBy('tanggal', 'ASC')->count(),
                'jml_sempro' => PenjadwalanSempro::where('prodi_id', '1')->where('status_seminar', 0)->where('waktu', '<>', null)->count(),
                'jml_sidang' => PenjadwalanSkripsi::where('prodi_id', '1')->where('status_seminar', 0)->where('waktu', '<>', null)->count(),


                //JUMLAH KP PRODI
                'jml_prodi_kp' => PendaftaranKP::where('prodi_id', '1')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('created_at', 'desc')->count(),

                //JUMLAH SKRIPSI PRODI
                'jml_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('prodi_id', '1')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT KP 
                'jml_riwayat_prodi_kp' => PendaftaranKP::where('prodi_id', '1')->where('status_kp', 'KP SELESAI')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT SKRIPSI 
                'jml_riwayat_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', 'LULUS')->where('prodi_id', '1')->orderBy('created_at', 'desc')->count(),
                //JUMLAH RIWAYAT SEMINAR
                'jml_riwayat_seminar_kp' => PenjadwalanKP::where('status_seminar', '1')->where('prodi_id', 1)->count(),
                'jml_riwayat_sempro' => PenjadwalanSempro::where('status_seminar', '1')->where('prodi_id', 1)->count(),
                'jml_riwayat_skripsi' => PenjadwalanSkripsi::where('status_seminar', '3')->where('prodi_id', 1)->count(),
            ]);
        }
        if (auth()->user()->role_id == 7) {
            return view('penilaian.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('prodi_id', '2')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),
                'penjadwalan_sempros' => PenjadwalanSempro::where('prodi_id', '2')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),
                'penjadwalan_skripsis' => PenjadwalanSkripsi::where('prodi_id', '2')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),

                //JUMLAH SEMINAR
                'jml_seminar_kp' => PenjadwalanKP::where('status_seminar', '0')->where('waktu', '<>', null)->where('prodi_id', 2)->orderBy('tanggal', 'ASC')->count(),
                'jml_sempro' => PenjadwalanSempro::where('prodi_id', '2')->where('status_seminar', 0)->where('waktu', '<>', null)->count(),
                'jml_sidang' => PenjadwalanSkripsi::where('prodi_id', '2')->where('status_seminar', 0)->where('waktu', '<>', null)->count(),


                //JUMLAH KP PRODI
                'jml_prodi_kp' => PendaftaranKP::where('prodi_id', '2')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('created_at', 'desc')->count(),

                //JUMLAH SKRIPSI PRODI
                'jml_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('prodi_id', '2')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT KP 
                'jml_riwayat_prodi_kp' => PendaftaranKP::where('prodi_id', '2')->where('status_kp', 'KP SELESAI')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT SKRIPSI 
                'jml_riwayat_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', 'LULUS')->where('prodi_id', '2')->orderBy('created_at', 'desc')->count(),
                //JUMLAH RIWAYAT SEMINAR
                'jml_riwayat_seminar_kp' => PenjadwalanKP::where('status_seminar', '1')->where('prodi_id', 2)->count(),
                'jml_riwayat_sempro' => PenjadwalanSempro::where('status_seminar', '1')->where('prodi_id', 2)->count(),
                'jml_riwayat_skripsi' => PenjadwalanSkripsi::where('status_seminar', '3')->where('prodi_id', 2)->count(),
            ]);
        }
        if (auth()->user()->role_id == 8) {
            return view('penilaian.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('prodi_id', '3')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),
                'penjadwalan_sempros' => PenjadwalanSempro::where('prodi_id', '3')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),
                'penjadwalan_skripsis' => PenjadwalanSkripsi::where('prodi_id', '3')->where('status_seminar', 0)->where('waktu', '<>', null)->get(),

                //JUMLAH SEMINAR
                'jml_seminar_kp' => PenjadwalanKP::where('status_seminar', '0')->where('waktu', '<>', null)->where('prodi_id', 3)->orderBy('tanggal', 'ASC')->count(),
                'jml_sempro' => PenjadwalanSempro::where('prodi_id', '3')->where('status_seminar', 0)->where('waktu', '<>', null)->count(),
                'jml_sidang' => PenjadwalanSkripsi::where('prodi_id', '3')->where('status_seminar', 0)->where('waktu', '<>', null)->count(),


                //JUMLAH KP PRODI
                'jml_prodi_kp' => PendaftaranKP::where('prodi_id', '3')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('created_at', 'desc')->count(),

                //JUMLAH SKRIPSI PRODI
                'jml_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('prodi_id', '3')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT KP 
                'jml_riwayat_prodi_kp' => PendaftaranKP::where('prodi_id', '3')->where('status_kp', 'KP SELESAI')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT SKRIPSI 
                'jml_riwayat_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', 'LULUS')->where('prodi_id', '3')->orderBy('created_at', 'desc')->count(),
                //JUMLAH RIWAYAT SEMINAR
                'jml_riwayat_seminar_kp' => PenjadwalanKP::where('status_seminar', '1')->where('prodi_id', 3)->count(),
                'jml_riwayat_sempro' => PenjadwalanSempro::where('status_seminar', '1')->where('prodi_id', 3)->count(),
                'jml_riwayat_skripsi' => PenjadwalanSkripsi::where('status_seminar', '3')->where('prodi_id', 3)->count(),

            ]);
        }

        if (auth()->user()->role_id == 9) {
            return view('penilaian.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('prodi_id', '1')->where('status_seminar', 0)->get(),
                'penjadwalan_sempros' => PenjadwalanSempro::where('prodi_id', '1')->where('status_seminar', 0)->get(),
                'penjadwalan_skripsis' => PenjadwalanSkripsi::where('prodi_id', '1')->where('status_seminar', 0)->get(),

                //JUMLAH SEMINAR
                'jml_seminar_kp' => PenjadwalanKP::where('status_seminar', '0')->where('prodi_id', 1)->orderBy('tanggal', 'ASC')->count(),
                'jml_sempro' => PenjadwalanSempro::where('prodi_id', '1')->where('status_seminar', 0)->count(),
                'jml_sidang' => PenjadwalanSkripsi::where('prodi_id', '1')->where('status_seminar', 0)->count(),


                //JUMLAH KP PRODI
                'jml_prodi_kp' => PendaftaranKP::where('prodi_id', '1')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('created_at', 'desc')->count(),

                //JUMLAH SKRIPSI PRODI
                'jml_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('prodi_id', '1')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT KP 
                'jml_riwayat_prodi_kp' => PendaftaranKP::where('prodi_id', '1')->where('status_kp', 'KP SELESAI')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT SKRIPSI 
                'jml_riwayat_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', 'LULUS')->where('prodi_id', '1')->orderBy('created_at', 'desc')->count(),
                //JUMLAH RIWAYAT SEMINAR
                'jml_riwayat_seminar_kp' => PenjadwalanKP::where('status_seminar', '1')->where('prodi_id', 1)->count(),
                'jml_riwayat_sempro' => PenjadwalanSempro::where('status_seminar', '1')->where('prodi_id', 1)->count(),
                'jml_riwayat_skripsi' => PenjadwalanSkripsi::where('status_seminar', '3')->where('prodi_id', 1)->count(),

            ]);
        }
        if (auth()->user()->role_id == 10) {
            return view('penilaian.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('prodi_id', '2')->where('status_seminar', 0)->get(),
                'penjadwalan_sempros' => PenjadwalanSempro::where('prodi_id', '2')->where('status_seminar', 0)->get(),
                'penjadwalan_skripsis' => PenjadwalanSkripsi::where('prodi_id', '2')->where('status_seminar', 0)->get(),

                //JUMLAH SEMINAR
                'jml_seminar_kp' => PenjadwalanKP::where('status_seminar', '0')->where('prodi_id', 2)->orderBy('tanggal', 'ASC')->count(),
                'jml_sempro' => PenjadwalanSempro::where('prodi_id', '2')->where('status_seminar', 0)->count(),
                'jml_sidang' => PenjadwalanSkripsi::where('prodi_id', '2')->where('status_seminar', 0)->count(),


                //JUMLAH KP PRODI
                'jml_prodi_kp' => PendaftaranKP::where('prodi_id', '2')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('created_at', 'desc')->count(),

                //JUMLAH SKRIPSI PRODI
                'jml_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('prodi_id', '2')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT KP 
                'jml_riwayat_prodi_kp' => PendaftaranKP::where('prodi_id', '2')->where('status_kp', 'KP SELESAI')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT SKRIPSI 
                'jml_riwayat_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', 'LULUS')->where('prodi_id', '2')->orderBy('created_at', 'desc')->count(),
                //JUMLAH RIWAYAT SEMINAR
                'jml_riwayat_seminar_kp' => PenjadwalanKP::where('status_seminar', '1')->where('prodi_id', 2)->count(),
                'jml_riwayat_sempro' => PenjadwalanSempro::where('status_seminar', '1')->where('prodi_id', 2)->count(),
                'jml_riwayat_skripsi' => PenjadwalanSkripsi::where('status_seminar', '3')->where('prodi_id', 2)->count(),
            ]);
        }
        if (auth()->user()->role_id == 11) {
            return view('penilaian.index', [
                'penjadwalan_kps' => PenjadwalanKP::where('status_seminar', '0')->where('prodi_id', 3)->orderBy('tanggal', 'ASC')->get(),
                'penjadwalan_sempros' => PenjadwalanSempro::where('prodi_id', '3')->where('status_seminar', 0)->get(),
                'penjadwalan_skripsis' => PenjadwalanSkripsi::where('prodi_id', '3')->where('status_seminar', 0)->get(),

                //JUMLAH SEMINAR
                'jml_seminar_kp' => PenjadwalanKP::where('status_seminar', '0')->where('prodi_id', 3)->orderBy('tanggal', 'ASC')->count(),
                'jml_sempro' => PenjadwalanSempro::where('prodi_id', '3')->where('status_seminar', 0)->count(),
                'jml_sidang' => PenjadwalanSkripsi::where('prodi_id', '3')->where('status_seminar', 0)->count(),

                //JUMLAH KP PRODI
                'jml_prodi_kp' => PendaftaranKP::where('prodi_id', '3')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('created_at', 'desc')->count(),

                //JUMLAH SKRIPSI PRODI
                'jml_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('prodi_id', '3')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT KP 
                'jml_riwayat_prodi_kp' => PendaftaranKP::where('prodi_id', '3')->where('status_kp', 'KP SELESAI')->orderBy('created_at', 'desc')->count(),

                //JUMLAH RIWAYAT SKRIPSI 
                'jml_riwayat_prodi_skripsi' => PendaftaranSkripsi::where('status_skripsi', 'LULUS')->where('prodi_id', '3')->orderBy('created_at', 'desc')->count(),
                //JUMLAH RIWAYAT SEMINAR
                'jml_riwayat_seminar_kp' => PenjadwalanKP::where('status_seminar', '1')->where('prodi_id', 3)->count(),
                'jml_riwayat_sempro' => PenjadwalanSempro::where('status_seminar', '1')->where('prodi_id', 3)->count(),
                'jml_riwayat_skripsi' => PenjadwalanSkripsi::where('status_seminar', '3')->where('prodi_id', 3)->count(),


            ]);
        }
    }

    public function indexpembimbing(PenjadwalanSempro $sempro)
    {

        $dosen = PenjadwalanSempro::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->get();


        $dosens = PenjadwalanSkripsi::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->get();

        $dosenss = PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->get();

        $jml_seminar_kp = PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->count();

        $jml_sempro = PenjadwalanSempro::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->count();

        $jml_sidang = PenjadwalanSkripsi::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 0)->where('waktu', '<>', null)->count();

        //JUMLAH RIWAYAT SEMINAR
        $jml_riwayat_sempro = PenjadwalanSempro::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 1)->count();

        $jml_riwayat_sidang = PenjadwalanSkripsi::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 3)->count();

        $jml_riwayat_kp = PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 1)->count();

        // $sempro = PenjadwalanSempro::find($id);
        //KOORDINATOR KP SKRIPSI
        if (auth()->user()->role_id == 6) {
        return view('penilaian.index-pembimbing', [
            'penjadwalan_sempros' => $dosen,
            'penjadwalan_skripsis' => $dosens,
            'penjadwalan_kps' => $dosenss,
            'jml_seminar_kp' => $jml_seminar_kp,
            'jml_sempro' => $jml_sempro,
            'jml_sidang' => $jml_sidang,
            'jml_riwayat_seminar_kp' => $jml_riwayat_kp,
            'jml_riwayat_sempro' => $jml_riwayat_sempro,
            'jml_riwayat_sidang' => $jml_riwayat_sidang,

            //JUMLAH PERSETUJUAN KP
            'jml_persetujuan_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Program Studi')->where('prodi_id', 1)
                ->orderBy('status_kp', 'desc')->count(),
                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH PERSETUJUAN SKRIPSI
            'jml_persetujuan_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 1')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Program Studi')->where('prodi_id', 1)
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 2')
                ->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH PERSETUJUAN SEMINAR SKRIPSI
            'jml_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 2)->where('prodi_id', 1)->count(),
            //JUMLAH RIWAYAT PERSETUJUAN SEMINAR
            'jml_riwayat_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 3)->where('prodi_id', 1)->count(),
            //JUMLAH KP
            'jml_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH SKRIPSI
            'jml_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            'jml_bimbinganskripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
            ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH RIWAYAT KP
            'jml_riwayat_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', 'KP SELESAI')->orderBy('updated_at', 'desc')->count(),
            //JUMLAH RIWAYAT SKRIPSI
            'jml_riwayat_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')->orderBy('updated_at', 'desc')->count(),
        ]);
    }
        if (auth()->user()->role_id == 7) {
        return view('penilaian.index-pembimbing', [
            'penjadwalan_sempros' => $dosen,
            'penjadwalan_skripsis' => $dosens,
            'penjadwalan_kps' => $dosenss,
            'jml_seminar_kp' => $jml_seminar_kp,
            'jml_sempro' => $jml_sempro,
            'jml_sidang' => $jml_sidang,
            'jml_riwayat_seminar_kp' => $jml_riwayat_kp,
            'jml_riwayat_sempro' => $jml_riwayat_sempro,
            'jml_riwayat_sidang' => $jml_riwayat_sidang,

            //JUMLAH PERSETUJUAN KP
            'jml_persetujuan_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Program Studi')->where('prodi_id', 2)
                ->orderBy('status_kp', 'desc')->count(),
                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH PERSETUJUAN SKRIPSI
            'jml_persetujuan_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 1')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Program Studi')->where('prodi_id', 2)
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 2')
                ->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH PERSETUJUAN SEMINAR SKRIPSI
            'jml_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 2)->where('prodi_id', 2)->count(),
            //JUMLAH RIWAYAT PERSETUJUAN SEMINAR
            'jml_riwayat_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 3)->where('prodi_id', 2)->count(),
            //JUMLAH KP
            'jml_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH SKRIPSI
            'jml_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            'jml_bimbinganskripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
            ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH RIWAYAT KP
            'jml_riwayat_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', 'KP SELESAI')->orderBy('updated_at', 'desc')->count(),
            //JUMLAH RIWAYAT SKRIPSI
            'jml_riwayat_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')->orderBy('updated_at', 'desc')->count(),
        ]);
    }
        if (auth()->user()->role_id == 8) {
        return view('penilaian.index-pembimbing', [
            'penjadwalan_sempros' => $dosen,
            'penjadwalan_skripsis' => $dosens,
            'penjadwalan_kps' => $dosenss,
            'jml_seminar_kp' => $jml_seminar_kp,
            'jml_sempro' => $jml_sempro,
            'jml_sidang' => $jml_sidang,
            'jml_riwayat_seminar_kp' => $jml_riwayat_kp,
            'jml_riwayat_sempro' => $jml_riwayat_sempro,
            'jml_riwayat_sidang' => $jml_riwayat_sidang,

            //JUMLAH PERSETUJUAN KP
            'jml_persetujuan_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Program Studi')->where('prodi_id', 3)
                ->orderBy('status_kp', 'desc')->count(),
                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH PERSETUJUAN SKRIPSI
            'jml_persetujuan_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 1')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Program Studi')->where('prodi_id', 3)
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 2')
                ->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH PERSETUJUAN SEMINAR SKRIPSI
            'jml_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 2)->where('prodi_id', 3)->count(),
            //JUMLAH RIWAYAT PERSETUJUAN SEMINAR
            'jml_riwayat_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 3)->where('prodi_id', 3)->count(),
            //JUMLAH KP
            'jml_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH SKRIPSI
            'jml_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            'jml_bimbinganskripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
            ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH RIWAYAT KP
            'jml_riwayat_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', 'KP SELESAI')->orderBy('updated_at', 'desc')->count(),
            //JUMLAH RIWAYAT SKRIPSI
            'jml_riwayat_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')->orderBy('updated_at', 'desc')->count(),
        ]);
    }
        if (auth()->user()->role_id == 9) {
        return view('penilaian.index-pembimbing', [
            'penjadwalan_sempros' => $dosen,
            'penjadwalan_skripsis' => $dosens,
            'penjadwalan_kps' => $dosenss,
            'jml_seminar_kp' => $jml_seminar_kp,
            'jml_sempro' => $jml_sempro,
            'jml_sidang' => $jml_sidang,
            'jml_riwayat_seminar_kp' => $jml_riwayat_kp,
            'jml_riwayat_sempro' => $jml_riwayat_sempro,
            'jml_riwayat_sidang' => $jml_riwayat_sidang,

            //JUMLAH PERSETUJUAN KP
            'jml_persetujuan_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator KP')->where('prodi_id', 1)
                ->orderBy('status_kp', 'desc')->count(),
                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH PERSETUJUAN SKRIPSI
            'jml_persetujuan_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 1')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Skripsi')->where('prodi_id', 1)
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 2')
                ->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH PERSETUJUAN SEMINAR SKRIPSI
            'jml_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 1)->where('prodi_id', 1)->count(),
            //JUMLAH RIWAYAT PERSETUJUAN SEMINAR
            'jml_riwayat_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 3)->where('prodi_id', 1)->count(),
            //JUMLAH KP
            'jml_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH SKRIPSI
            'jml_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            'jml_bimbinganskripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
            ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH RIWAYAT KP
            'jml_riwayat_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', 'KP SELESAI')->orderBy('updated_at', 'desc')->count(),
            //JUMLAH RIWAYAT SKRIPSI
            'jml_riwayat_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')->orderBy('updated_at', 'desc')->count(),
        ]);
    }
        if (auth()->user()->role_id == 10) {
        return view('penilaian.index-pembimbing', [
            'penjadwalan_sempros' => $dosen,
            'penjadwalan_skripsis' => $dosens,
            'penjadwalan_kps' => $dosenss,
            'jml_seminar_kp' => $jml_seminar_kp,
            'jml_sempro' => $jml_sempro,
            'jml_sidang' => $jml_sidang,
            'jml_riwayat_seminar_kp' => $jml_riwayat_kp,
            'jml_riwayat_sempro' => $jml_riwayat_sempro,
            'jml_riwayat_sidang' => $jml_riwayat_sidang,

            //JUMLAH PERSETUJUAN KP
            'jml_persetujuan_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator KP')->where('prodi_id', 2)
                ->orderBy('status_kp', 'desc')->count(),
                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH PERSETUJUAN SKRIPSI
            'jml_persetujuan_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 1')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Skripsi')->where('prodi_id', 2)
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 2')
                ->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH PERSETUJUAN SEMINAR SKRIPSI
            'jml_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 1)->where('prodi_id', 2)->count(),
            //JUMLAH RIWAYAT PERSETUJUAN SEMINAR
            'jml_riwayat_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 3)->where('prodi_id', 2)->count(),
            //JUMLAH KP
            'jml_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH SKRIPSI
            'jml_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            'jml_bimbinganskripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
            ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH RIWAYAT KP
            'jml_riwayat_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', 'KP SELESAI')->orderBy('updated_at', 'desc')->count(),
            //JUMLAH RIWAYAT SKRIPSI
            'jml_riwayat_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')->orderBy('updated_at', 'desc')->count(),
        ]);
    }
        if (auth()->user()->role_id == 11) {
        return view('penilaian.index-pembimbing', [
            'penjadwalan_sempros' => $dosen,
            'penjadwalan_skripsis' => $dosens,
            'penjadwalan_kps' => $dosenss,
            'jml_seminar_kp' => $jml_seminar_kp,
            'jml_sempro' => $jml_sempro,
            'jml_sidang' => $jml_sidang,
            'jml_riwayat_seminar_kp' => $jml_riwayat_kp,
            'jml_riwayat_sempro' => $jml_riwayat_sempro,
            'jml_riwayat_sidang' => $jml_riwayat_sidang,

            //JUMLAH PERSETUJUAN KP
            'jml_persetujuan_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator KP')->where('prodi_id', 3)
                ->orderBy('status_kp', 'desc')->count(),
                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH PERSETUJUAN SKRIPSI
            'jml_persetujuan_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 1')
            ->orWhere('keterangan', 'Menunggu persetujuan Koordinator Skripsi')->where('prodi_id', 3)
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 2')
                ->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH PERSETUJUAN SEMINAR SKRIPSI
            'jml_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 1)->where('prodi_id', 3)->count(),
            //JUMLAH RIWAYAT PERSETUJUAN SEMINAR
            'jml_riwayat_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 3)->where('prodi_id', 3)->count(),
            //JUMLAH KP
            'jml_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH SKRIPSI
            'jml_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            'jml_bimbinganskripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
            ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH RIWAYAT KP
            'jml_riwayat_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', 'KP SELESAI')->orderBy('updated_at', 'desc')->count(),
            //JUMLAH RIWAYAT SKRIPSI
            'jml_riwayat_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')->orderBy('updated_at', 'desc')->count(),
        ]);
    }

        if (auth()->user()->nip > 0) {
        return view('penilaian.index-pembimbing', [
            'penjadwalan_sempros' => $dosen,
            'penjadwalan_skripsis' => $dosens,
            'penjadwalan_kps' => $dosenss,
            'jml_seminar_kp' => $jml_seminar_kp,
            'jml_sempro' => $jml_sempro,
            'jml_sidang' => $jml_sidang,
            'jml_riwayat_seminar_kp' => $jml_riwayat_kp,
            'jml_riwayat_sempro' => $jml_riwayat_sempro,
            'jml_riwayat_sidang' => $jml_riwayat_sidang,

            //JUMLAH PERSETUJUAN KP
            'jml_persetujuan_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                ->orderBy('status_kp', 'desc')->count(),
                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH PERSETUJUAN SKRIPSI
            'jml_persetujuan_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 1')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing 2')
                ->orderBy('status_skripsi', 'desc')->count(),
            //JUMLAH PERSETUJUAN SEMINAR SKRIPSI
            'jml_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 2)->where('prodi_id', 1)->count(),
            //JUMLAH RIWAYAT PERSETUJUAN SEMINAR
            'jml_riwayat_persetujuan_seminar' => PenjadwalanSkripsi::where('status_seminar', 3)->where('prodi_id', 1)->count(),
            //JUMLAH KP
            'jml_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->count(),
            //JUMLAH SKRIPSI
            'jml_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            'jml_bimbinganskripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'LULUS')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->orderBy('status_skripsi', 'desc')
            ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', '<>', 'USULAN JUDUL DITOLAK')->where('status_skripsi', '<>', 'USULKAN JUDUL ULANG')->where('status_skripsi', '<>', 'LULUS')->orderBy('status_skripsi', 'desc')->count(),

            //JUMLAH RIWAYAT KP
            'jml_riwayat_kp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', 'KP SELESAI')->orderBy('updated_at', 'desc')->count(),
            //JUMLAH RIWAYAT SKRIPSI
            'jml_riwayat_skripsi' => PendaftaranSkripsi::where('pembimbing_1_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')
                ->orWhere('pembimbing_2_nip', Auth::user()->nip)->where('status_skripsi', 'LULUS')->orderBy('updated_at', 'desc')->count(),
        ]);
    }

    }


    public function riwayat()
    {
        $riwayat = PenjadwalanSempro::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 1)->get();

        $riwayatt = PenjadwalanSkripsi::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 3)->get();

        $draf = PenjadwalanSkripsi::where('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 1)->get();

        $draff = PenjadwalanSkripsi::where('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 2)->get();

        $riwayattt = PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 1)->get();



        if (auth()->user()->role_id == 5) {
            return view('penilaian.riwayat-penilaian', [
                'jml_prodikp' => PendaftaranKP::where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('status_kp', 'desc')->get(),

                'jml_persetujuankp' =>  PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Seminar KP Dijadwalkan')
                    ->orderBy('status_kp', 'desc')->get(),

                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->get(),

                'jml_seminarkp' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get(),

                'penjadwalan_sempros' => $riwayat,
                'penjadwalan_skripsis' => $riwayatt,
                'penjadwalan_skripsis_draf' => $draf,
                'penjadwalan_skripsis_draff' => $draff,
                'penjadwalan_kps' => $riwayattt,
            ]);
        }
        if (auth()->user()->role_id == 6) {
            return view('penilaian.riwayat-penilaian', [
                'jml_prodikp' => PendaftaranKP::where('prodi_id', '1')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('status_kp', 'desc')->get(),

                'jml_persetujuankp' =>  PendaftaranKP::where('prodi_id', '1')->where('keterangan', 'Menunggu persetujuan Koordinator Program Studi')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Seminar KP Dijadwalkan')
                    ->orderBy('status_kp', 'desc')->get(),

                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->get(),

                'jml_seminarkp' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get(),

                'penjadwalan_sempros' => $riwayat,
                'penjadwalan_skripsis' => $riwayatt,
                'penjadwalan_skripsis_draf' => $draf,
                'penjadwalan_skripsis_draff' => $draff,
                'penjadwalan_kps' => $riwayattt,
            ]);
        }
        if (auth()->user()->role_id == 7) {
            return view('penilaian.riwayat-penilaian', [
                'jml_prodikp' => PendaftaranKP::where('prodi_id', '2')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('status_kp', 'desc')->get(),

                'jml_persetujuankp' =>  PendaftaranKP::where('prodi_id', '2')->where('keterangan', 'Menunggu persetujuan Koordinator Program Studi')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Seminar KP Dijadwalkan')
                    ->orderBy('status_kp', 'desc')->get(),

                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->get(),

                'jml_seminarkp' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get(),

                'penjadwalan_sempros' => $riwayat,
                'penjadwalan_skripsis' => $riwayatt,
                'penjadwalan_skripsis_draf' => $draf,
                'penjadwalan_skripsis_draff' => $draff,
                'penjadwalan_kps' => $riwayattt,
            ]);
        }
        if (auth()->user()->role_id == 8) {
            return view('penilaian.riwayat-penilaian', [
                'jml_prodikp' => PendaftaranKP::where('prodi_id', '3')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('status_kp', 'desc')->get(),

                'jml_persetujuankp' =>  PendaftaranKP::where('prodi_id', '3')->where('keterangan', 'Menunggu persetujuan Koordinator Program Studi')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Seminar KP Dijadwalkan')
                    ->orderBy('status_kp', 'desc')->get(),

                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->get(),

                'jml_seminarkp' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get(),

                'penjadwalan_sempros' => $riwayat,
                'penjadwalan_skripsis' => $riwayatt,
                'penjadwalan_skripsis_draf' => $draf,
                'penjadwalan_skripsis_draff' => $draff,
                'penjadwalan_kps' => $riwayattt,
            ]);
        }

        if (auth()->user()->role_id == 9) {
            return view('penilaian.riwayat-penilaian', [
                'jml_prodikp' => PendaftaranKP::where('prodi_id', '1')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('status_kp', 'desc')->get(),

                'jml_persetujuankp' => PendaftaranKP::where('prodi_id', '1')->where('keterangan', 'Menunggu persetujuan Koordinator KP')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Seminar KP Dijadwalkan')
                    ->orWhere('prodi_id', '1')->where('keterangan', 'Proses Kerja Praktek Selesai')
                    ->orWhere('prodi_id', '2')->where('status_kp', 'DAFTAR SEMINAR KP')->where('keterangan', 'Menunggu Jadwal Seminar KP')
                    ->orderBy('status_kp', 'desc')->get(),

                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->get(),

                'jml_seminarkp' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get(),

                'penjadwalan_sempros' => $riwayat,
                'penjadwalan_skripsis' => $riwayatt,
                'penjadwalan_skripsis_draf' => $draf,
                'penjadwalan_skripsis_draff' => $draff,
                'penjadwalan_kps' => $riwayattt,
            ]);
        }
        if (auth()->user()->role_id == 10) {
            return view('penilaian.riwayat-penilaian', [
                'jml_prodikp' => PendaftaranKP::where('prodi_id', '2')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('status_kp', 'desc')->get(),

                'jml_persetujuankp' => PendaftaranKP::where('prodi_id', '2')->where('keterangan', 'Menunggu persetujuan Koordinator KP')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Seminar KP Dijadwalkan')
                    ->orWhere('prodi_id', '2')->where('keterangan', 'Proses Kerja Praktek Selesai')
                    ->orWhere('prodi_id', '2')->where('status_kp', 'DAFTAR SEMINAR KP')->where('keterangan', 'Menunggu Jadwal Seminar KP')
                    ->orderBy('status_kp', 'desc')->get(),

                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->get(),

                'jml_seminarkp' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get(),

                'penjadwalan_sempros' => $riwayat,
                'penjadwalan_skripsis' => $riwayatt,
                'penjadwalan_skripsis_draf' => $draf,
                'penjadwalan_skripsis_draff' => $draff,
                'penjadwalan_kps' => $riwayattt,
            ]);
        }
        if (auth()->user()->role_id == 11) {
            return view('penilaian.riwayat-penilaian', [
                'jml_prodikp' => PendaftaranKP::where('prodi_id', '3')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('status_kp', 'desc')->get(),

                'jml_persetujuankp' => PendaftaranKP::where('prodi_id', '3')->where('keterangan', 'Menunggu persetujuan Koordinator KP')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Seminar KP Dijadwalkan')
                    ->orWhere('prodi_id', '3')->where('keterangan', 'Proses Kerja Praktek Selesai')
                    ->orWhere('prodi_id', '3')->where('status_kp', 'DAFTAR SEMINAR KP')->where('keterangan', 'Menunggu Jadwal Seminar KP')
                    ->orderBy('status_kp', 'desc')->get(),

                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->get(),

                'jml_seminarkp' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get(),

                'penjadwalan_sempros' => $riwayat,
                'penjadwalan_skripsis' => $riwayatt,
                'penjadwalan_skripsis_draf' => $draf,
                'penjadwalan_skripsis_draff' => $draff,
                'penjadwalan_kps' => $riwayattt,

            ]);
        }

        // DOSEN PEMBIMBING
        if (auth()->user()->nip > 0) {
            return view('penilaian.riwayat-penilaian', [
                'jml_prodikp' => PendaftaranKP::where('keterangan', '<>', 'Nilai KP Telah Keluar')->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->orderBy('status_kp', 'desc')->get(),

                'jml_persetujuankp' =>  PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Menunggu persetujuan Pembimbing')
                    ->orWhere('dosen_pembimbing_nip', Auth::user()->nip)->where('keterangan', 'Seminar KP Dijadwalkan')
                    ->orderBy('status_kp', 'desc')->get(),

                'jml_bimbingankp' => PendaftaranKP::where('dosen_pembimbing_nip', Auth::user()->nip)->where('status_kp', '<>', 'USULAN KP DITOLAK')->where('status_kp', '<>', 'USULKAN KP ULANG')->where('keterangan', '<>', 'Nilai KP Telah Keluar')->orderBy('status_kp', 'desc')->get(),

                'jml_seminarkp' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 0)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 0)->get(),

                'penjadwalan_kps' => PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 1)->get(),

            ]);
        }
    }

    // public function riwayatskripsi()
    // {
    //     $riwayat = PenjadwalanSempro::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 1)->get();        

    //     $riwayatt = PenjadwalanSkripsi::where('pembimbingsatu_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pembimbingdua_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujidua_nip', Auth::user()->nip)->where('status_seminar', 3)->orWhere('pengujitiga_nip', Auth::user()->nip)->where('status_seminar', 3)->get();

    //     $draf = PenjadwalanSkripsi::where('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 1)->get();

    //     $draff = PenjadwalanSkripsi::where('pengujisatu_nip', Auth::user()->nip)->where('status_seminar', 2)->get();

    //     // $riwayattt = PenjadwalanKP::where('penguji_nip', Auth::user()->nip)->where('status_seminar', 1)->orWhere('pembimbing_nip', Auth::user()->nip)->where('status_seminar', 1)->get();

    //     return view('penilaian.riwayat-penilaian-skripsi', [
    //         'penjadwalan_sempros' => $riwayat,
    //         'penjadwalan_skripsis' => $riwayatt,
    //         'penjadwalan_skripsis_draf' => $draf,
    //         'penjadwalan_skripsis_draff' => $draff,
    //         // 'penjadwalan_kps' => $riwayattt,
    //     ]);
    // }
}
