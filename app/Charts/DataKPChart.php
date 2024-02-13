<?php

namespace App\Charts;

use App\Models\PendaftaranKP;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class DataKPChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function buildChart2(): \ArielMejiaDev\LarapexCharts\PieChart
    {
         $statuses = [
            'USULAN KP', 'USULAN KP DITOLAK', 'USULKAN KP ULANG', 'USULAN KP DITERIMA',
            'DAFTAR SEMINAR KP', 'SEMINAR KP DISETUJUI',
            'SEMINAR KP DIJADWALKAN', 'SEMINAR KP SELESAI',
            'BUKTI PENYERAHAN LAPORAN', 'BUKTI PENYERAHAN LAPORAN DITOLAK',
            'KP SELESAI'
        ];

        $data = [];
        $total = 0;

        foreach ($statuses as $status) {
            $count = PendaftaranKP::where('status_kp', $status)->count();
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
