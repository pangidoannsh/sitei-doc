<?php

namespace App\Http\Controllers;
use \PDF;


use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\StatusKP;
use App\Models\Mahasiswa;
use App\Charts\DataKPChart;
use App\Models\Konsentrasi;
use App\Models\PermohonanKP;
use Illuminate\Http\Request;
use App\Models\PendaftaranKP;
use App\Models\PenjadwalanKP;
use App\Models\KapasitasBimbingan;
use App\Models\PendaftaranSkripsi;
use App\Models\PenilaianKPPenguji;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PenilaianKPPembimbing;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use App\Charts\DataBimbinganSkripsiChart;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StatistikController extends Controller
{
    
    public function index(DataBimbinganSkripsiChart $chart, DataKPChart $chart2)   
    {

        // $chart = $this->chartService->buildChart();
        // $chart2 = $this->chartService->buildChart2();

        // $dosen = Dosen::findOrFail($nip);
        // $mhs = Dosen::findOrFail($nim);

        // $BimbinganKP = PendaftaranKP::where('status_kp', 'KP SELESAI')
        //             ->where('dosen_pembimbing_nip', $nip)
        //             ->get();

        // $selesaiKP = PendaftaranKP::where('mahasiswa_nim', $nim)->get();
        // $mahasiswa = PendaftaranKP::where('status_kp', 'KP SELESAI')->get();


return view('pendaftaran.statistik.index', [
    'chart' => $chart->build(),
    'chart2' => $chart2->buildChart2(),
    // 'rerata_kp' => $BimbinganKP,
    // 'jumlah_selesai' => $mahasiswa,
]);

    }

    public function bimbingan_kp()
    {

    $dosenskp = Dosen::withCount([
        'pendaftaranKP' => function ($query) {
            $query->where('status_kp', '!=', 'USULAN KP DITOLAK')
                ->where('status_kp', '!=', 'USULKAN KP ULANG')
                ->where('keterangan', '!=', 'Nilai KP Telah Keluar');
        },
    ])->get()->sortByDesc('pendaftaran_k_p_count');
        
    $dosenskplulus = Dosen::withCount([
         'pendaftarankp' => function ($query) {
        $query->where('status_kp','KP SELESAI');
    }
    ])->get()->sortByDesc('pendaftarankp_count');

        return view('pendaftaran.statistik.bimbingan-kp', [
            'dosen' => $dosenskp,
            'dosen_lulus' => $dosenskplulus,
            'kapasitas' => KapasitasBimbingan::first(),
        ]);
    }

    public function detail_lulus_bimbingan_kp($nip)
    {
        $dosen = Dosen::where('nip', $nip)->first();

        $mahasiswa = $dosen->pembimbingkp_lulus;

        return view('pendaftaran.statistik.detail-lulus-kp', [
            'pendaftaran_kp' => $mahasiswa,
            'dosen' => $dosen,
        ]);
    }

    public function detail_lulus_bimbingan_skripsi($nip)
    {
        $dosen = Dosen::where('nip', $nip)->first();

        $mahasiswa1 = $dosen->pembimbing1skripsi_lulus;
        $mahasiswa2 = $dosen->pembimbing2skripsi_lulus;

        $gabungan = $mahasiswa1->merge($mahasiswa2);

        return view('pendaftaran.statistik.detail-lulus-skripsi', [
            'pendaftaran_skripsi' => $gabungan,
            'dosen' => $dosen,
        ]);
    }
    
    public function bimbingan_skripsi()
    {
    
    $dosensskripsi = Dosen::withCount([
        'pendaftaranSkripsi1' => function ($query) {
            $query->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
                ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
                ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar');
        },
        'pendaftaranSkripsi2' => function ($query) {
            $query->where('status_skripsi', '!=', 'USULAN JUDUL DITOLAK')
                ->where('status_skripsi', '!=', 'USULKAN JUDUL ULANG')
                ->where('keterangan', '!=', 'Nilai Skripsi Telah Keluar');
        },
    ])->get()->sortByDesc(function($dosen) {
        return $dosen->pendaftaran_skripsi1_count + $dosen->pendaftaran_skripsi2_count;
    });
    
    $dosensskripsiLulus = Dosen::withCount([
        'pendaftaranSkripsi1' => function ($query) {
            $query->where('status_skripsi', 'LULUS');
        },
        'pendaftaranSkripsi2' => function ($query) {
            $query->where('status_skripsi', 'LULUS');
        },
    ])->get()->sortByDesc(function($dosen) {
        return $dosen->pendaftaran_skripsi1_count + $dosen->pendaftaran_skripsi2_count;
    });

        return view('pendaftaran.statistik.bimbingan-skripsi', [
            'dosen' => $dosensskripsi,
            'dosen_lulus' => $dosensskripsiLulus,
            'kapasitas' => KapasitasBimbingan::first(),
        ]);
    }

    public function indexkp()   
    {
        //  $pendaftaran_kp = PendaftaranKP::all()->first();

        return view('pendaftaran.kerja-praktek.index', [
            'dosens' => Dosen::all(), 
            'prodi' => Prodi::all(),
            'konsentrasi' => Konsentrasi::all(),
            'mahasiswa' => Mahasiswa::where('nim', Auth::user()->nim)->get(),
            'pendaftaran_kp' => PendaftaranKP::all()->sortBy('update_at'),
       
        ]);
    }


}
