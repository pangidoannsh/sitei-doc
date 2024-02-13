<?php

namespace App\Charts;

use App\Models\PendaftaranSkripsi;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class DataBimbinganSkripsiChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $statuses = [
            'USULAN JUDUL', 'USULAN JUDUL DITOLAK', 'USULKAN JUDUL ULANG', 'USULAN JUDUL DISETUJUI',
            'DAFTAR SEMPRO', 'DAFTAR SEMPRO ULANG', 'DAFTAR SEMPRO DITOLAK', 'DAFTAR SEMPRO DISETUJUI',
            'SEMPRO DIJADWALKAN', 'SEMPRO SELESAI',
            'PERPANJANGAN 1', 'PERPANJANGAN 1 DITOLAK', 'PERPANJANGAN 1 DISETUJUI',
            'PERPANJANGAN 2', 'PERPANJANGAN 2 DITOLAK', 'PERPANJANGAN 2 DISETUJUI',
            'DAFTAR SIDANG', 'DAFTAR SIDANG ULANG', 'DAFTAR SIDANG DITOLAK', 'DAFTAR SIDANG DISETUJUI',
            'SIDANG DIJADWALKAN', 'SIDANG SELESAI',
            'PERPANJANGAN REVISI', 'PERPANJANGAN REVISI DITOLAK', 'PERPANJANGAN REVISI DISETUJUI',
            'BUKTI PENYERAHAN BUKU SKRIPSI', 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK',
            'LULUS'
        ];

        $data = [];
        $total = 0;

        foreach ($statuses as $status) {
            $count = PendaftaranSkripsi::where('status_skripsi', $status)->count();
            $data[] = $count;
            $total += $count;
        }

        // Hitung persentase dan buat label
        $labels = [];
        foreach ($statuses as $index => $status) {
            $percentage = round(($data[$index] / $total) * 100, 2);
            $labels[] = $status . ' (' . $percentage . '%)';
        }

        return $this->chart->pieChart()
            ->setTitle('Status Skripsi')
            // ->setColors(['#FFC107', '#D32F2F'])
            ->setLabels($labels)
            ->addData($data)
            ->setGrid();
            
    }
}
