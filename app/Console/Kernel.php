<?php

namespace App\Console;

use App\Models\PendaftaranKP;
use App\Models\PendaftaranSkripsi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->call(function () {
        // \App\Models\PendaftaranKP::whereNull('surat_balasan')
        // ->where('tgl_disetujui_usulankp', '<', now()->subMinutes(10))
        // ->update([
        //     'status_kp' => 'USULKAN KP ULANG',
        //     'keterangan' => 'Batas Waktu Habis'
        // ]);
        // })->everyMinute();

    

        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('surat_balasan')
        ->where('status_kp', 'USULAN KP DITERIMA')
        ->where('tgl_disetujui_usulankp_kaprodi', '<', now()->subMonths(1))
        ->update([
            'status_kp' => 'USULKAN KP ULANG',
            'alasan' => 'Batas Waktu Unggah Surat Balasan Perusahaan Habis',
            'keterangan' => 'Batas Waktu Unggah Surat Balasan Perusahaan Habis',
            'tgl_disetujui_usulankp_admin' => null,
            'tgl_disetujui_usulankp_pembimbing' => null,
            'tgl_disetujui_usulankp_koordinator' => null,
            'tgl_disetujui_usulankp_kaprodi' => null,
        ]);
        })->monthly()->runInBackground();

        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('judul_laporan')
        ->where('status_kp', 'KP DISETUJUI')
        ->where('tanggal_mulai', '<', now()->subMonths(3))
        ->update([
            'status_kp' => 'USULKAN KP ULANG',
            'keterangan' => 'Batas Waktu Daftar Seminar KP Habis',
            'alasan' => 'Batas Waktu Daftar Seminar KP Habis',
            'tgl_disetujui_usulankp_admin' => null,
            'tgl_disetujui_usulankp_pembimbing' => null,
            'tgl_disetujui_usulankp_koordinator' => null,
            'tgl_disetujui_usulankp_kaprodi' => null,

            'surat_balasan' => null,
            'tgl_disetujui_balasan' => null,
        ]);
        })->monthly()->runInBackground();
        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('kpti_10')
        ->where('status_kp', 'SEMINAR KP SELESAI')
        ->where('tgl_selesai_semkp', '<', now()->subMonths(1))
        ->update([
            'status_kp' => 'USULKAN KP ULANG',
            'keterangan' => 'Batas Waktu Penyerahan Laporan Habis',
            'alasan' => 'Batas Waktu Penyerahan Laporan Habis',
            'tgl_disetujui_usulankp_admin' => null,
            'tgl_disetujui_usulankp_pembimbing' => null,
            'tgl_disetujui_usulankp_koordinator' => null,
            'tgl_disetujui_usulankp_kaprodi' => null,

            'surat_balasan' => null,
            'tgl_disetujui_balasan' => null,

            'judul_laporan' => null,
            'tgl_disetujui_semkp_admin' => null,
            'tgl_disetujui_semkp_pembimbing' => null,
            'tgl_disetujui_semkp_koordinator' => null,
            'tgl_disetujui_semkp_kaprodi' => null,
        ]);
        })->monthly()->runInBackground();

        //SKRIPSI
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_created_sempro')
        ->where('status_skripsi', 'USULAN JUDUL')
        ->where('tgl_disetujui_usuljudul_kaprodi', '<', now()->subMonths(6))
        ->update([
            'status_skripsi' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Batas Waktu Daftar Seminar Proposal Habis'
        ]);
        })->monthly()->runInBackground();

        

        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('buku_skripsi_akhir')
        ->where('tgl_created_revisi', null)
        ->where('tgl_selesai_sidang', 'SIDANG SELESAI')
        ->where('tgl_selesai_sidang', '<', now()->subMonths(1))
        ->update([
            'status_skripsi' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Batas Waktu Peyerahan Buku Skripsi Habis'
        ]);
        })->monthly()->runInBackground();

        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('buku_skripsi_akhir')
        ->where('status_skripsi', 'PERPANJANGAN REVISI DISETUJUI')
        ->where('tgl_selesai_sidang', '<', now()->subMonths(2))
        ->update([
            'status_skripsi' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Batas Waktu Peyerahan Buku Skripsi Habis'
        ]);
        })->monthly()->runInBackground();


        // PERSETUJUAN KP

       $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_usulankp_admin')
        ->where('status_kp', 'USULAN KP')
        ->where('tgl_created_usulankp', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN KP ULANG',
            'keterangan' => 'Usulan belum disetujui Admin Prodi',
            'alasan' => 'Silahkan Usulkan KP Ulang',
        ]);
        })->daily()->runInBackground();
       
        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_usulankp_pembimbing')
        ->where('status_kp', 'USULAN KP')
        ->where('tgl_disetujui_usulankp_admin', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN KP ULANG',
            'keterangan' => 'Usulan belum disetujui Pembimbing',
            'alasan' => 'Silahkan Usulkan KP Ulang',
            'tgl_disetujui_usulankp_admin' => null,
        ]);
        })->daily()->runInBackground();

        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_usulankp_koordinator')
        ->where('status_kp', 'USULAN KP')
        ->where('tgl_disetujui_usulankp_pembimbing', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN KP ULANG',
            'keterangan' => 'Usulan belum disetujui Koordinator',
            'alasan' => 'Silahkan Usulkan KP Ulang',
            'tgl_disetujui_usulankp_admin' => null,
            'tgl_disetujui_usulankp_pembimbing' => null,
        ]);
        })->daily()->runInBackground();

        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_usulankp_kaprodi')
        ->where('status_kp', 'USULAN KP')
        ->where('tgl_disetujui_usulankp_koordinator', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN KP ULANG',
            'keterangan' => 'Usulan belum disetujui Kaprodi',
            'alasan' => 'Silahkan Usulkan KP Ulang',
            'tgl_disetujui_usulankp_admin' => null,
            'tgl_disetujui_usulankp_pembimbing' => null,
            'tgl_disetujui_usulankp_koordinator' => null,
        ]);
        })->daily()->runInBackground();
       
    //    BALASAN KP 
        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_balasan')
        ->where('status_kp', 'SURAT PERUSAHAAN')
        ->where('tgl_created_balasan', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'SURAT PERUSAHAAN DITOLAK',
            'keterangan' => 'Surat Perusahaan belum disetujui Koordinator KP',
            'alasan' => 'Silahkan Unggah Ulang Surat Balasan Perusahaan!',
        ]);
        })->daily()->runInBackground();
       
        //DAFTAR SEMINAR KP
        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_semkp_admin')
        ->where('status_kp', 'DAFTAR SEMINAR KP')
        ->where('tgl_created_semkp', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SEMINAR KP ULANG',
            'keterangan' => 'Surat Perusahaan belum disetujui Koordinator KP',
            'alasan' => 'Silahkan Unggah Ulang Surat Balasan Perusahaan!',
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_semkp_pembimbing')
        ->where('status_kp', 'DAFTAR SEMINAR KP')
        ->where('tgl_disetujui_semkp_admin', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SEMINAR KP ULANG',
            'keterangan' => 'Surat Perusahaan belum disetujui Koordinator KP',
            'alasan' => 'Silahkan Unggah Ulang Surat Balasan Perusahaan!',
            'tgl_disetujui_semkp_admin' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_semkp_koordinator')
        ->where('status_kp', 'DAFTAR SEMINAR KP')
        ->where('tgl_disetujui_semkp_pembimbing', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SEMINAR KP ULANG',
            'keterangan' => 'Surat Perusahaan belum disetujui Koordinator KP',
            'alasan' => 'Silahkan Unggah Ulang Surat Balasan Perusahaan!',
            'tgl_disetujui_semkp_admin' => null,
            'tgl_disetujui_semkp_pembimbing' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_semkp_kaprodi')
        ->where('status_kp', 'DAFTAR SEMINAR KP')
        ->where('tgl_disetujui_semkp_koordinator', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SEMINAR KP ULANG',
            'keterangan' => 'Surat Perusahaan belum disetujui Koordinator KP',
            'alasan' => 'Silahkan Unggah Ulang Surat Balasan Perusahaan!',
            'tgl_disetujui_semkp_admin' => null,
            'tgl_disetujui_semkp_pembimbing' => null,
            'tgl_disetujui_semkp_koordinator' => null,
        ]);
        })->daily()->runInBackground();

        //BUKTI PENYERAHAN LAPORAN

        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_kpti_10_koordinator')
        ->where('status_kp', 'SEMINAR KP SELESAI')
        ->where('tgl_created_kpti10', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'SURAT PERUSAHAAN DITOLAK',
            'keterangan' => 'Surat Perusahaan belum disetujui Koordinator KP',
            'alasan' => 'Silahkan Unggah Ulang Surat Balasan Perusahaan!',
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranKP::whereNull('tgl_disetujui_kpti_10_kaprodi')
        ->where('status_kp', 'SEMINAR KP SELESAI')
        ->where('tgl_disetujui_kpti_10_koordinator', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'SURAT PERUSAHAAN DITOLAK',
            'keterangan' => 'Surat Perusahaan belum disetujui Koordinator KP',
            'alasan' => 'Silahkan Unggah Ulang Surat Balasan Perusahaan!',
            'tgl_disetujui_kpti_10_koordinator' => null,
        ]);
        })->daily()->runInBackground();



        //BATAS PERSETUJUAN SKRIPSI

        //UDUL JUDUL
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_usuljudul_admin')
        ->where('status_skripsi', 'USULAN JUDUL')
        ->where('tgl_created_usuljudul', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Usulan Judul belum disetujui Admin Prodi',
            'alasan' => 'Silahkan Usulkan Judul ulang!',
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_usuljudul_pemb1')
        ->where('status_skripsi', 'USULAN JUDUL')
        ->where('tgl_disetujui_usuljudul_admin', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Usulan Judul belum disetujui Pembimbing 1',
            'alasan' => 'Silahkan Usulkan Judul ulang!',
            'tgl_disetujui_usuljudul_admin' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_usuljudul_pemb2')
        ->where('status_skripsi', 'USULAN JUDUL')
        ->where('pembimbing_2_nip','<>', null )
        ->where('tgl_disetujui_usuljudul_pemb1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Usulan Judul belum disetujui Pembimbing 2',
            'alasan' => 'Silahkan Usulkan Judul ulang!',
            'tgl_disetujui_usuljudul_admin' => null,
            'tgl_disetujui_usuljudul_pemb1' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_usuljudul_koordinator')
        ->where('status_skripsi', 'USULAN JUDUL')
        ->where('pembimbing_2_nip','<>', null )
        ->where('tgl_disetujui_usuljudul_pemb2', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Usulan Judul belum disetujui Koordinator Skripsi',
            'alasan' => 'Silahkan Usulkan Judul ulang!',
            'tgl_disetujui_usuljudul_admin' => null,
            'tgl_disetujui_usuljudul_pemb1' => null,
            'tgl_disetujui_usuljudul_pemb2' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_usuljudul_koordinator')
        ->where('status_skripsi', 'USULAN JUDUL')
        ->where('pembimbing_2_nip', null )
        ->where('tgl_disetujui_usuljudul_pemb1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Usulan Judul belum disetujui Koordinator Skripsi',
            'alasan' => 'Silahkan Usulkan Judul ulang!',
            'tgl_disetujui_usuljudul_admin' => null,
            'tgl_disetujui_usuljudul_pemb1' => null,
            'tgl_disetujui_usuljudul_pemb2' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_usuljudul_kaprodi')
        ->where('status_skripsi', 'USULAN JUDUL')
        ->where('tgl_disetujui_usuljudul_koordinator', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'USULKAN JUDUL ULANG',
            'keterangan' => 'Usulan Judul belum disetujui Koordinator Program Studi',
            'alasan' => 'Silahkan Usulkan Judul ulang!',
            'tgl_disetujui_usuljudul_admin' => null,
            'tgl_disetujui_usuljudul_pemb1' => null,
            'tgl_disetujui_usuljudul_pemb2' => null,
            'tgl_disetujui_usuljudul_koordinator' => null,
        ]);
        })->daily()->runInBackground();


        //DAFTAR SEMPRO
         $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sempro_pemb1')
        ->where('status_skripsi', 'DAFTAR SEMPRO')
        ->where('tgl_created_sempro', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SEMPRO ULANG',
            'keterangan' => 'Pendaftaran Seminar Proposal belum disetujui Pembimbing 1',
            'alasan' => 'Silahkan Daftar Sempro ulang!',
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sempro_pemb2')
        ->where('status_skripsi', 'DAFTAR SEMPRO')
        ->where('pembimbing_2_nip','<>', null )
        ->where('tgl_disetujui_sempro_pemb1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SEMPRO ULANG',
            'keterangan' => 'Pendaftaran Seminar Proposal belum disetujui Pembimbing 2',
            'alasan' => 'Silahkan Daftar Sempro ulang!',
            'tgl_disetujui_sempro_pemb1' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sempro_admin')
        ->where('status_skripsi', 'DAFTAR SEMPRO')
        ->where('pembimbing_2_nip','<>', null )
        ->where('tgl_disetujui_sempro_pemb2', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SEMPRO ULANG',
            'keterangan' => 'Pendaftaran Seminar Proposal belum disetujui Admin Prodi',
            'alasan' => 'Silahkan Daftar Sempro ulang!',
            'tgl_disetujui_sempro_pemb1' => null,
            'tgl_disetujui_sempro_pemb2' => null,
        ]);
        })->daily()->runInBackground();
        
         $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sempro_admin')
        ->where('status_skripsi', 'DAFTAR SEMPRO')
        ->where('pembimbing_2_nip', null )
        ->where('tgl_disetujui_sempro_pemb1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SEMPRO ULANG',
            'keterangan' => 'Pendaftaran Seminar Proposal belum disetujui Admin Prodi',
            'alasan' => 'Silahkan Daftar Sempro ulang!',
            'tgl_disetujui_sempro_pemb1' => null,
            'tgl_disetujui_sempro_pemb2' => null,
        ]);
        })->daily()->runInBackground();

        //PERPANJANGAN 1

         $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_perpanjangan1_pemb1')
        ->where('status_skripsi', 'PERPANJANGAN 1')
        ->where('tgl_created_perpanjangan1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'PERPANJANGAN 1 DITOLAK',
            'keterangan' => 'Usulan Perpanjangan 1 belum disetujui Pembimbing 1',
            'alasan' => 'Silahkan Usulkan Perpanjangan 1 ulang!',
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_perpanjangan1_kaprodi')
        ->where('status_skripsi', 'PERPANJANGAN 1')
        ->where('tgl_disetujui_perpanjangan1_pemb1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'PERPANJANGAN 1 DITOLAK',
            'keterangan' => 'Usulan Perpanjangan 1 belum disetujui Koordinator Program Studi',
            'alasan' => 'Silahkan Usulkan Perpanjangan 1 ulang!',
            'tgl_disetujui_perpanjangan1_pemb1' => null,
        ]);
        })->daily()->runInBackground();
        
        //PERPANJANGAN 2

         $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_perpanjangan2_pemb1')
        ->where('status_skripsi', 'PERPANJANGAN 2')
        ->where('tgl_created_perpanjangan2', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'PERPANJANGAN 2 DITOLAK',
            'keterangan' => 'Usulan Perpanjangan 2 belum disetujui Pembimbing 1',
            'alasan' => 'Silahkan Usulkan Perpanjangan 2 ulang!',
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_perpanjangan2_kaprodi')
        ->where('status_skripsi', 'PERPANJANGAN 2')
        ->where('tgl_disetujui_perpanjangan2_pemb2', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'PERPANJANGAN 2 DITOLAK',
            'keterangan' => 'Usulan Perpanjangan 2 belum disetujui Koordinator Program Studi',
            'alasan' => 'Silahkan Usulkan Perpanjangan 2 ulang!',
            'tgl_disetujui_perpanjangan2_pemb1' => null,
        ]);
        })->daily()->runInBackground();

        // DAFTAR SIDANG
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sidang_admin')
        ->where('status_skripsi', 'DAFTAR SIDANG')
        ->where('tgl_created_sidang', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SIDANG ULANG',
            'keterangan' => 'Pendaftaran Sidang Skripsi belum disetujui Admin Prodi',
            'alasan' => 'Silahkan Daftar Sidang ulang!',
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sidang_pemb1')
        ->where('status_skripsi', 'DAFTAR SIDANG')
        ->where('tgl_disetujui_sidang_admin', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SIDANG ULANG',
            'keterangan' => 'Pendaftaran Sidang Skripsi belum disetujui Pembimbing 1',
            'alasan' => 'Silahkan Daftar Sidang ulang!',
            'tgl_disetujui_sidang_admin' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sidang_pemb2')
        ->where('status_skripsi', 'DAFTAR SIDANG')
        ->where('pembimbing_2_nip','<>', null )
        ->where('tgl_disetujui_sidang_pemb1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SIDANG ULANG',
            'keterangan' => 'Pendaftaran Sidang Skripsi belum disetujui Pembimbing 2',
            'alasan' => 'Silahkan Daftar Sidang ulang!',
            'tgl_disetujui_sidang_admin' => null,
            'tgl_disetujui_sidang_pemb1' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sidang_koordinator')
        ->where('status_skripsi', 'DAFTAR SIDANG')
        ->where('pembimbing_2_nip','<>', null )
        ->where('tgl_disetujui_sidang_pemb2', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SIDANG ULANG',
            'keterangan' => 'Pendaftaran Sidang Skripsi belum disetujui Koordinator Skripsi',
            'alasan' => 'Silahkan Daftar Sidang ulang!',
            'tgl_disetujui_sidang_admin' => null,
            'tgl_disetujui_sidang_pemb1' => null,
            'tgl_disetujui_sidang_pemb2' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sidang_koordinator')
        ->where('status_skripsi', 'DAFTAR SIDANG')
        ->where('pembimbing_2_nip', null )
        ->where('tgl_disetujui_sidang_pemb1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SIDANG ULANG',
            'keterangan' => 'Pendaftaran Sidang Skripsi belum disetujui Koordinator Skripsi',
            'alasan' => 'Silahkan Daftar Sidang ulang!',
            'tgl_disetujui_sidang_admin' => null,
            'tgl_disetujui_sidang_pemb1' => null,
            'tgl_disetujui_sidang_pemb2' => null,
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sidang_kaprodi')
        ->where('status_skripsi', 'DAFTAR SIDANG')
        ->where('tgl_disetujui_sidang_koordinator', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'DAFTAR SIDANG ULANG',
            'keterangan' => 'Pendaftaran Sidang Skripsi belum disetujui Koordinator Program Studi',
            'alasan' => 'Silahkan Daftar Sidang ulang!',
            'tgl_disetujui_sidang_admin' => null,
            'tgl_disetujui_sidang_pemb1' => null,
            'tgl_disetujui_sidang_pemb2' => null,
            'tgl_disetujui_sidang_koordinator' => null,
        ]);
        })->daily()->runInBackground();

        // PERPANJANGAN REVISI
         $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_revisi_pemb1')
        ->where('status_skripsi', 'PERPANJANGAN REVISI')
        ->where('tgl_created_revisi', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'PERPANJANGAN 2 DITOLAK',
            'keterangan' => 'Usulan Perpanjangan 2 belum disetujui Pembimbing 1',
            'alasan' => 'Silahkan Usulkan Perpanjangan 2 ulang!',
        ]);
        })->daily()->runInBackground();
        
        $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_revisi_kaprodi')
        ->where('status_skripsi', 'PERPANJANGAN REVISI')
        ->where('tgl_disetujui_revisi_pemb1', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'PERPANJANGAN 2 DITOLAK',
            'keterangan' => 'Usulan Perpanjangan 2 belum disetujui Koordinator Program Studi',
            'alasan' => 'Silahkan Usulkan Perpanjangan 2 ulang!',
            'tgl_disetujui_revisi_pemb1' => null,
        ]);
        })->daily()->runInBackground();
        
        // PENYERAHAN BUKU SKRIPSI
         $schedule->call(function () {
        \App\Models\PendaftaranSkripsi::whereNull('tgl_disetujui_sti_17_koordinator')
        ->where('status_skripsi', 'PENYERAHAN BUKU SKRIPSI')
        ->where('tgl_created_sti_17', '<', now()->subDays(3))
        ->update([
            'status_kp' => 'BUKTI PENYERAHAN BUKU SKRIPSI DITOLAK',
            'keterangan' => 'Bukti Penyerahan Buku Skripsi belum disetujui Koordinator Skripsi',
            'alasan' => 'Silahkan Usulkan Bukti Penyerahan Buku Skripsi ulang!',
        ]);
        })->daily()->runInBackground();
        

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
