<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Mahasiswa;
use App\Models\Konsentrasi;
use Illuminate\Database\Seeder;
use App\Models\KapasitasBimbingan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'role_id' => 1,
            'username' => 'tariseptaviani',
            'nama' => 'Tari Septa Viani',
            'email' => 'tarisepta@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        User::create([
            'role_id' => 2,
            'username' => 'muhammadpeter',
            'nama' => 'Muhammad Peter, A.Md',
            'email' => 'muhpeter@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        User::create([
            'role_id' => 3,
            'username' => 'khairinafitria',
            'nama' => 'Khairina Fitria Lubis, S.Kom',
            'email' => 'khairinafitria@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        User::create([
            'role_id' => 4,
            'username' => 'zainalasri',
            'nama' => 'Zainal Asri',
            'email' => 'zainalasri@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        Role::create([
            'role_akses' => 'Staff Jurusan'
        ]);

        Role::create([
            'role_akses' => 'Staff Prodi TE D3'
        ]);

        Role::create([
            'role_akses' => 'Staff Prodi TE S1'
        ]);

        Role::create([
            'role_akses' => 'Staff Prodi TI S1'
        ]);

        Role::create([
            'role_akses' => 'Ketua Jurusan'
        ]);

        Role::create([
            'role_akses' => 'Ketua Prodi TE D3'
        ]);

        Role::create([
            'role_akses' => 'Ketua Prodi TE S1'
        ]);

        Role::create([
            'role_akses' => 'Ketua Prodi TI S1'
        ]);

        Role::create([
            'role_akses' => 'Koordinator KP & Skripsi TE D3'
        ]);

        Role::create([
            'role_akses' => 'Koordinator KP & Skripsi TE S1'
        ]);

        Role::create([
            'role_akses' => 'Koordinator KP & Skripsi TI S1'
        ]);

        Role::create([
            'role_akses' => 'PLP'
        ]);


        Prodi::create([
            'nama_prodi' => 'Teknik Elektro D3'
        ]);

        Prodi::create([
            'nama_prodi' => 'Teknik Elektro S1'
        ]);

        Prodi::create([
            'nama_prodi' => 'Teknik Informatika S1'
        ]);

        Konsentrasi::create([
            'nama_konsentrasi' => 'Teknik Tenaga Listrik'
        ]);

        Konsentrasi::create([
            'nama_konsentrasi' => 'Teknik Telekomunikasi'
        ]);

        Konsentrasi::create([
            'nama_konsentrasi' => 'Komputasi Cerdas dan Visualiasi'
        ]);

        Konsentrasi::create([
            'nama_konsentrasi' => 'Rekayasa Perangkat Lunak'
        ]);

        Konsentrasi::create([
            'nama_konsentrasi' => 'Komputasi Berbasis Jaringan'
        ]);

        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125148',
            'nama' => 'Rahul Ilsa Tajri Mukhti',
            'email' => 'rahul.ilsa5148@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);

        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 3,
            'nim' => '1807125149',
            'nama' => 'Murdillah Rezky',
            'email' => 'murdillah.rezky5149@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);

        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 5,
            'nim' => '1807125150',
            'nama' => 'M. Fajar Edwin Saputra',
            'email' => 'fajar.edwin5150@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);

        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807111442',
            'nama' => 'Fahril Hadi',
            'email' => 'fahril.hadi515q@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);

        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125152',
            'nama' => 'M. Seprinaldi',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125160',
            'nama' => 'Sayuri',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125161',
            'nama' => 'Yakob',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125162',
            'nama' => 'Yance',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125163',
            'nama' => 'Arhan',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125164',
            'nama' => 'Pratama',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125165',
            'nama' => 'Witan',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125166',
            'nama' => 'Sulaiman',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125167',
            'nama' => 'Marcel',
            'email' => 'm.seprinaldi5152@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);

        Mahasiswa::create([
            'prodi_id' => 3,
            // 'role_id' => 13,
            'konsentrasi_id' => 4,
            'nim' => '1807125153',
            'nama' => 'Aqil Muafa',
            'email' => 'aqilmuafa@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 2,
            // 'role_id' => 13,
            'konsentrasi_id' => 1,
            'nim' => '123',
            'nama' => 'Agung',
            'email' => 'agung@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 1,
            // 'role_id' => 13,
            'konsentrasi_id' => 2,
            'nim' => '1234',
            'nama' => 'Soang',
            'email' => 'soang@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2018',
        ]);
        Mahasiswa::create([
            'prodi_id' => 3,
            'konsentrasi_id' => 4,
            'nim' => '2007113796',
            'nama' => 'Pangidoan Nugroho Syahputra Harahap',
            'email' => 'pangidoan.nugroho3796@student.unri.ac.id',
            'password' => bcrypt('123456'),
            'angkatan' => '2020',
        ]);

        Dosen::create([
            'prodi_id' => 2,
            'role_id' => 5,
            'nip' => 197604092002121002,
            'password' => bcrypt('123456'),
            'nama' => 'Anhar, ST., MT., Ph.D',
            'nama_singkat' => 'AN',
            'email' => 'anhar.lecturer@unri.ac.id',
        ]);

        Dosen::create([
            'role_id' => 6,
            'prodi_id' => 1,
            'nip' => 197507052002121003,
            'password' => bcrypt('123456'),
            'nama' => 'Amir Hamzah, S.T., M.T.',
            'nama_singkat' => 'AH',
            'email' => 'amirhamzah.lecturer@unri.ac.id',
        ]);
        Dosen::create([
            'role_id' => 7,
            'prodi_id' => 2,
            'nip' => 197511042005012001,
            'password' => bcrypt('123456'),
            'nama' => 'Yusnita Rahayu, ST, M.Eng, Ph.D',
            'nama_singkat' => 'YR',
            'email' => 'yusnita.rahayu@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'role_id' => 8,
            'prodi_id' => 3, 'prodi_id' => 3,
            'nip' => 197404282002121003,
            'password' => bcrypt('123456'),
            'nama' => 'Dr. Feri Candra, ST., MT',
            'nama_singkat' => 'FC',
            'email' => 'fericandra.lecturer@unri.ac.id',
        ]);

        Dosen::create([
            'prodi_id' => 1,
            'role_id' => 9,
            'nip' => 197705102005011002,
            'password' => bcrypt('123456'),
            'nama' => 'Firdaus, S.T., M.T.',
            'nama_singkat' => 'FD',
            'email' => 'firdaus@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 3,
            'role_id' => 10,
            'nip' => 198312032019031006,
            'password' => bcrypt('123456'),
            'nama' => 'Rahmat Rizal Andhi, S.T., M.T.',
            'nama_singkat' => 'RR',
            'email' => 'edisusilo.lecturer@unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 3,
            'nip' => 199110292019031010,
            'password' => bcrypt('123456'),
            'nama' => 'Edi Susilo, S.Pd., M.Kom., M.Eng',
            'nama_singkat' => 'ED',
            'email' => 'edisusilo.lecturer@unri.ac.id',
        ]);

        User::create([
            'role_id' => 12,
            'username' => 'jatwoko',
            'nama' => 'Jatwoko',
            'email' => 'jatwoko@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        Dosen::create([
            'prodi_id' => 3,
            'nip' => 197207122000121002,
            'password' => bcrypt('123456'),
            'nama' => 'T. Yudi Hadiwandra, S.Kom., M.Kom',
            'nama_singkat' => 'TY',
            'email' => 'yudi.lecturer@unri.ac.id',
        ]);

        Dosen::create([
            'prodi_id' => 3,
            'nip' => 198005102005011003,
            'password' => bcrypt('123456'),
            'nama' => 'Dr. Irsan Taufik Ali, ST., MT',
            'nama_singkat' => 'IT',
            'email' => 'anhar.lecturer@unri.ac.id',
        ]);

        Dosen::create([
            'prodi_id' => 3,
            'nip' => 196707231999031001,
            'password' => bcrypt('123456'),
            'nama' => 'Rahyul Amri, ST., MT',
            'nama_singkat' => 'RA',
            'email' => 'rahyulamri.lecturer@unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 3,
            'nip' => 197311271999032002,
            'password' => bcrypt('123456'),
            'nama' => 'Noveri Lysbetti Marpaung, ST., M.Sc.',
            'nama_singkat' => 'NL',
            'email' => 'noveri.marpaung@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 3,
            'nip' => 197910152006042002,
            'password' => bcrypt('123456'),
            'nama' => 'Linna Oktaviana Sari, ST., MT',
            'nama_singkat' => 'LO',
            'email' => 'linnaoasari@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 3,
            'role_id' => 11,
            'nip' => 198805052020122012,
            'password' => bcrypt('123456'),
            'nama' => 'Dian Ramadhani, S.T., M.T',
            'nama_singkat' => 'DR',
            'email' => 'dianramadhani@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 197304011999032003,
            'password' => bcrypt('123456'),
            'nama' => 'Prof. Azriyenni, S.T., M.Eng., Ph.D',
            'nama_singkat' => 'AZ',
            'email' => 'azriyenni@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 197807152003121006,
            'password' => bcrypt('123456'),
            'nama' => 'Iswadi HR, S.T., M.T., Ph.D.',
            'nama_singkat' => 'IH',
            'email' => 'iswadi.hr@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 197802222002121003,
            'password' => bcrypt('123456'),
            'nama' => 'Dr. Febrizal, S.T., M.T.',
            'nama_singkat' => 'FZ',
            'email' => 'febrizal@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 196607311997021001,
            'password' => bcrypt('123456'),
            'nama' => 'Dr. Ir. Antonius Rajagukguk, M.T.',
            'nama_singkat' => 'AR',
            'email' => 'antonius.rajagukguk@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 197403072002121002,
            'password' => bcrypt('123456'),
            'nama' => 'Indra Yasri, S.T., M.T., Ph.D',
            'nama_singkat' => 'IY',
            'email' => 'indra.yasri@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 196611021999031002,
            'password' => bcrypt('123456'),
            'nama' => 'Suwitno, S.T., M.T.',
            'nama_singkat' => 'SW',
            'email' => 'suwitno@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 197408202002121001,
            'password' => bcrypt('123456'),
            'nama' => 'Nurhalim S.T., M.T.',
            'nama_singkat' => 'NH',
            'email' => 'nurhalim@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 197803082003121001,
            'password' => bcrypt('123456'),
            'nama' => 'Dian Yayan Sukma, S.T., M.T.',
            'nama_singkat' => 'DY',
            'email' => 'dianyayan.sukma@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 197402242000032001,
            'password' => bcrypt('123456'),
            'nama' => 'Ery Safrianti, S.T., M.T.',
            'nama_singkat' => 'DY',
            'email' => 'esafrianti@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 197302012005012002,
            'password' => bcrypt('123456'),
            'nama' => 'Feranita , S.T., M.T.',
            'nama_singkat' => 'FN',
            'email' => 'feranita@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 2,
            'nip' => 199407102022032019,
            'password' => bcrypt('123456'),
            'nama' => 'R.A Rizka Qori Yuliani Putri S.ST., MT.',
            'nama_singkat' => 'RQ',
            'email' => 'rizkaqoriyulianiputri@lecturer.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 1,
            'nip' => 198002052003121001,
            'password' => bcrypt('123456'),
            'nama' => 'Dr. Fri Murdiya, S.T., M.T.',
            'nama_singkat' => 'FM',
            'email' => 'frimurdiya@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 1,
            'nip' => 196412151997021001,
            'password' => bcrypt('123456'),
            'nama' => 'Ir. Edy Ervianto, M.T.',
            'nama_singkat' => 'EE',
            'email' => 'edy.ervianto@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 1,
            'nip' => 196709081999031001,
            'password' => bcrypt('123456'),
            'nama' => 'Eddy Hamdani, S.T., M.T.',
            'nama_singkat' => 'EH',
            'email' => 'ehamdani@eng.unri.ac.id',
        ]);
        Dosen::create([
            'prodi_id' => 1,
            'nip' => 197208132000121001,
            'password' => bcrypt('123456'),
            'nama' => 'Budhi Anto, S.T., MT.',
            'nama_singkat' => 'BA',
            'email' => 'budhianto@eng.unri.ac.id',
        ]);


        // Ruangan::create([
        //     'nama_ruangan' => 'C314',
        //     'kode_ruangan' => 'C01',
        // ]);

        // Ruangan::create([
        //     'nama_ruangan' => 'C315',
        //     'kode_ruangan' => 'C02',
        // ]);

        // Ruangan::create([
        //     'nama_ruangan' => 'Lab RPL',
        //     'kode_ruangan' => 'R01',
        // ]);

        // Ruangan::create([
        //     'nama_ruangan' => 'RC1TI',
        //     'kode_ruangan' => 'R02',
        // ]);

        // Ruangan::create([
        //     'nama_ruangan' => 'Lab Jarkom',
        //     'kode_ruangan' => 'J01',
        // ]);

        KapasitasBimbingan::create([
            'kapasitas_kp' => '10',
            'kapasitas_skripsi' => '10',

        ]);



        // INVENTARIS

        Barang::create([
            'kode_barang' => '001',
            'nama_barang' => 'Infocus 001',
            'jumlah' => 1
        ]);

        Barang::create([
            'kode_barang' => '002',
            'nama_barang' => 'Infocus 002',
            'jumlah' => 1
        ]);

        Barang::create([
            'kode_barang' => '003',
            'nama_barang' => 'Infocus 003',
            'jumlah' => 1
        ]);

        Barang::create([
            'kode_barang' => '004',
            'nama_barang' => 'Infocus 004',
            'jumlah' => 1
        ]);

        Barang::create([
            'kode_barang' => 'RW',
            'nama_barang' => 'Absen Rekayasa Web',
            'jumlah' => 1
        ]);

        Barang::create([
            'kode_barang' => 'IMK',
            'nama_barang' => 'Absen IMK',
            'jumlah' => 1
        ]);

        Barang::create([
            'kode_barang' => 'MMD',
            'nama_barang' => 'Absen Multimedia',
            'jumlah' => 1
        ]);

        Barang::create([
            'kode_barang' => 'RC1TI',
            'nama_barang' => 'Kunci RC1TI',
            'jumlah' => 1
        ]);

        Barang::create([
            'kode_barang' => 'C314',
            'nama_barang' => 'Kunci C314',
            'jumlah' => 1
        ]);
        DistribusiDokumenSeeder::seed();
    }
}
