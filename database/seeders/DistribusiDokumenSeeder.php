<?php

namespace Database\Seeders;

use App\Models\DistribusiDokumen\Dokumen;
use App\Models\DistribusiDokumen\DokumenMention;
use App\Models\DistribusiDokumen\Logo;
use App\Models\DistribusiDokumen\Pengumuman;
use App\Models\DistribusiDokumen\PengumumanMention;
use App\Models\DistribusiDokumen\SuratCuti;
use App\Models\Semester;
use Carbon\Carbon;

class DistribusiDokumenSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public static function seed()
    {
        Logo::create([
            "nama" => "Unri",
            "url" => "logo/unri.svg",
            "is_mandatory" => true,
            "position" => "kiri",
        ]);
        Logo::create([
            "nama" => "Google",
            "url" => "logo/google.png",
            "is_mandatory" => false,
            "position" => "kiri",
        ]);
        Logo::create([
            "nama" => "Kampus Merdeka",
            "url" => "logo/km.svg",
            "is_mandatory" => false,
            "position" => "kanan",
        ]);
        $tahun1 = 2017;
        $tahun2 = 2018;
        for ($i = 0; $i < 7; $i++) {
            Semester::create([
                'semester' => "Ganjil",
                "tahun_ajaran" => "$tahun1/$tahun2",
                'tanggal_mulai' => Carbon::create($tahun1, 8, 1),
                'tanggal_selesai' => Carbon::create($tahun1, 12, 15),
            ]);
            Semester::create([
                'semester' => "Genap",
                "tahun_ajaran" => "$tahun1/$tahun2",
                'tanggal_mulai' => Carbon::create($tahun2, 2, 3),
                'tanggal_selesai' => Carbon::create($tahun2, 7, 15),
            ]);
            $tahun1++;
            $tahun2++;
        }

        Pengumuman::create([
            'nama' => "Pengumuman 1",
            'isi' => "Pengumuman Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'url_dokumen_lokal' => 'https://drive.google.com/file/d/159pnlukyuRCNP3uNdG5FkPcCp02hJlEZ/view?usp=sharing',
            'jenis_user' => 'dosen',
            'user_created' => '197604092002121002', //Anhar, ST., MT., Ph.D
            'tgl_batas_pengumuman' => now(),
            'kategori' => "pendidikan",
            'semester' => "genap 2023/2024",
        ]);
        PengumumanMention::create([
            'pengumuman_id' => 1,
            'user_mentioned' => '199110292019031010', //Edi Susilo, S.Pd., M.Kom., M.Eng
            'jenis_user' => 'dosen',
        ]);
        Dokumen::create([
            'nama' => "Usulan Lorem Ipsum",
            'keterangan' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'url_dokumen' => 'https://drive.google.com/file/d/159pnlukyuRCNP3uNdG5FkPcCp02hJlEZ/view?usp=sharing',
            'jenis_user' => 'dosen',
            'user_created' => '199110292019031010', //Edi Susilo, S.Pd., M.Kom., M.Eng
            'tgl_dokumen' => "2023-02-01",
            'kategori' => "pendidikan",
            'semester' => "genap 2023/2024",
            'nomor_dokumen' => "test/2324",
        ]);

        Dokumen::create([
            'nama' => "Jurnal Portal dengan Android",
            'keterangan' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'url_dokumen' => 'https://drive.google.com/file/d/159pnlukyuRCNP3uNdG5FkPcCp02hJlEZ/view?usp=sharing',
            'jenis_user' => 'dosen',
            'user_created' => '198312032019031006', //Rahmat Rizal Andhi, S.T., M.T.
            'tgl_dokumen' => "2023-02-01",
            'kategori' => "pendidikan",
            'semester' => "genap 2023/2024",
            'nomor_dokumen' => "Doc/1223un-ft"
        ]);

        // SuratCuti::create([
        //     'jenis_user' => 'dosen',
        //     'user_created' => '199110292019031010', //Edi Susilo, S.Pd., M.Kom., M.Eng
        //     'lama_cuti' => 7,
        //     'alamat_cuti' => 'Jl. Bangau Sakti',
        //     'mulai_cuti' => now()->addDays(2),
        //     'selesai_cuti' => now()->addDays(9),
        //     "alasan_cuti" => "Cuti Tahunan"
        // ]);

        DokumenMention::create([
            'dokumen_id' => 1,
            'user_mentioned' => '197404282002121003', //Dr. Feri Candra, ST., MT
            'jenis_user' => 'dosen',
        ]);
        DokumenMention::create([
            'dokumen_id' => 2,
            'user_mentioned' => '199110292019031010', //Edi Susilo, S.Pd., M.Kom., M.Eng
            'jenis_user' => 'dosen',
        ]);
    }
}
