<?php

use GuzzleHttp\Middleware;
use App\Models\PenjadwalanKP;
use App\Http\Middleware\CekRole;
use App\Models\PenjadwalanSempro;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRController;
use App\Http\Controllers\PLPController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UsulanController;
use App\Http\Controllers\RiwayatController;
// use App\Http\Controllers\RuanganController;
use App\Http\Controllers\DeveloperController;
// use App\Http\Controllers\JadwalkanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\DosenProfilController;
use App\Http\Controllers\KonsentrasiController;
//M.Seprinaldi
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PenilaianKPController;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Controllers\StaffProfilController;
use App\Http\Controllers\PendaftaranKPController;

use App\Http\Controllers\PenjadwalanKPController;
use App\Http\Controllers\CountdownTimerController;
use App\Http\Controllers\MahasiswaProfilController;
use App\Http\Controllers\PeminjamanAdminController;
use App\Http\Controllers\PeminjamanDosenController;
use App\Http\Controllers\PenilaianSemproController;
use App\Http\Controllers\UndanganSeminarController;
use App\Http\Controllers\PenilaianSkripsiController;

// INVENTARIS
use App\Http\Controllers\PenjadwalanSemproController;
use App\Http\Controllers\PendaftaranSkripsiController;
use App\Http\Controllers\PenjadwalanSkripsiController;
use App\Http\Controllers\PeminjamanMahasiswaController;
use App\Http\Controllers\PeminjamanPLPController;

// DOKUMEN
use App\Http\Controllers\DistribusiDokumen\DistribusiDokumenController;
use App\Http\Controllers\DistribusiDokumen\PengumumanController;
use App\Http\Controllers\DistribusiDokumen\DokumenController;
use App\Http\Controllers\DistribusiDokumen\DokumenMentionController;
use App\Http\Controllers\DistribusiDokumen\SertifikatController;
use App\Http\Controllers\DistribusiDokumen\PenerimaSertifikatController;
use App\Http\Controllers\DistribusiDokumen\SuratCutiController;
use App\Http\Controllers\DistribusiDokumen\SuratController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(["auth:web,dosen,mahasiswa", "cek-jenis-user"])->group(function () {
    // DiISTRIBUSI
    Route::get('/distribusi-dokumen', [DistribusiDokumenController::class, 'index'])->name('doc.index');
    Route::get('/distribusi-dokumen/arsip', [DistribusiDokumenController::class, 'arsip'])->name('doc.arsip');
    Route::get('/distribusi-dokumen/arsip/jurusan', [DistribusiDokumenController::class, 'arsipJurusan'])->name('arsip.jurusan');
    Route::get('/distribusi-dokumen/arsip/prodi', [DistribusiDokumenController::class, 'arsipProdi'])->name('arsip.prodi');

    //PENGUMUMAN
    Route::get('/distribusi-dokumen/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/distribusi-dokumen/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/distribusi-dokumen/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/distribusi-dokumen/pengumuman/{id}', [PengumumanController::class, 'detail'])->name('pengumuman.detail');
    Route::get('/distribusi-dokumen/pengumuman/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('/distribusi-dokumen/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/distribusi-dokumen/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.delete');

    //DOKUMEN
    Route::get('/distribusi-dokumen/dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('/distribusi-dokumen/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('/distribusi-dokumen/dokumen/{id}', [DokumenController::class, 'detail'])->name('dokumen.detail');
    Route::get('/distribusi-dokumen/dokumen/{id}/edit', [DokumenController::class, 'edit'])->name('dokumen.edit');
    Route::put('/distribusi-dokumen/dokumen/{id}', [DokumenController::class, 'update'])->name('dokumen.update');
    Route::delete('/distribusi-dokumen/dokumen/{id}', [DokumenController::class, 'destroy'])->name('dokumen.delete');

    //DOKUMEN MENTION
    Route::get('/distribusi-dokumen/dokumen/mention/{dokumen_id}/{user_mentioned}', [DokumenMentionController::class, 'accept'])->name('dokumen.mention.accept');
    Route::delete('/distribusi-dokumen/dokumen/mention/{dokumen_id}/{user_mentioned}', [DokumenMentionController::class, 'destroy'])->name('dokumen.mention.delete');

    // SURAT CUTI
    Route::get('/distribusi-dokumen/suratcuti/create', [SuratCutiController::class, 'create'])->name('suratcuti.create');
    Route::post('/distribusi-dokumen/suratcuti', [SuratCutiController::class, 'store'])->name('suratcuti.store');
    Route::get('/distribusi-dokumen/suratcuti/{id}', [SuratCutiController::class, 'detail'])->name('suratcuti.detail');
    Route::get('/distribusi-dokumen/suratcuti/{id}/edit', [SuratCutiController::class, 'edit'])->name('suratcuti.edit');
    Route::put('/distribusi-dokumen/suratcuti/{id}', [SuratCutiController::class, 'update'])->name('suratcuti.update');
    Route::delete('/distribusi-dokumen/suratcuti/{id}', [SuratCutiController::class, 'destroy'])->name('suratcuti.delete');
    Route::get('/distribusi-dokumen/suratcuti/{id}/approve', [SuratCutiController::class, 'approve'])->name('suratcuti.approve');
    Route::delete('/distribusi-dokumen/suratcuti/{id}/reject', [SuratCutiController::class, 'reject'])->name('suratcuti.reject');

    // SURAT
    Route::get('/distribusi-dokumen/surat/create', [SuratController::class, 'create'])->name('surat.create');
    Route::get('/distribusi-dokumen/surat/{id}', [SuratController::class, 'detail'])->name('surat.detail');
    Route::put('/distribusi-dokumen/surat/{id}', [SuratController::class, 'update'])->name('surat.update');
    Route::get('/distribusi-dokumen/surat/{id}/edit', [SuratController::class, 'edit'])->name('surat.edit');
    Route::post('/distribusi-dokumen/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::delete('/distribusi-dokumen/surat/{id}', [SuratController::class, 'destroy'])->name('surat.delete');
    Route::post('/distribusi-dokumen/surat/{id}/done', [SuratController::class, 'done'])->name('surat.done');
    Route::post('/distribusi-dokumen/surat/{id}/reject', [SuratController::class, 'reject'])->name('surat.reject');

    //SERTIFIKAT
    Route::get('/distribusi-dokumen/sertifikat/create', [SertifikatController::class, 'create'])->name('sertif.create');
    Route::post('/distribusi-dokumen/sertifikat', [SertifikatController::class, 'store'])->name('sertif.store');
    Route::get('/distribusi-dokumen/sertifikat/{id}', [SertifikatController::class, 'detail'])->name('sertif.detail');
    Route::get('/distribusi-dokumen/sertifikat/{id}/edit', [SertifikatController::class, 'edit'])->name('sertif.edit');
    Route::put('/distribusi-dokumen/sertifikat/{id}', [SertifikatController::class, 'update'])->name('sertif.update');
    Route::delete('/distribusi-dokumen/sertifikat/{id}', [SertifikatController::class, 'delete'])->name('sertif.delete');
    Route::get('/distribusi-dokumen/sertifikat/{id}/completion', [SertifikatController::class, 'make'])->name('sertif.make');
    Route::post('/distribusi-dokumen/sertifikat/{id}/completion', [SertifikatController::class, 'completion'])->name('sertif.completion');
});
// PENERIMA SERTIFIKAT
Route::get("/sertifikat/{slug}", [PenerimaSertifikatController::class, 'show'])->name("sertif.penerima");

Route::group(['middleware' => 'prevent-back-history'], function () {
    Route::get('/detail-kp/{id}', [QRController::class, 'detailkp']);
    Route::get('/detail-sempro/{id}', [QRController::class, 'detailsempro']);
    Route::get('/detail-skripsi/{id}', [QRController::class, 'detailskripsi']);

    Route::get('/detail-undangan-kp/{id}', [QRController::class, 'detail_undangan_kp']);
    Route::get('/detail-undangan-sempro/{id}', [QRController::class, 'detail_undangan_sempro']);
    Route::get('/detail-undangan-sidang/{id}', [QRController::class, 'detail_undangan_sidang']);

    Route::get('/developer', [DeveloperController::class, 'index']);
    Route::get('/developer/fahril-hadi', [DeveloperController::class, 'fahril']);
    Route::get('/developer/m-seprinaldi', [DeveloperController::class, 'naldi']);
    Route::get('/developer/rahul-ilsa-tajri-mukhti', [DeveloperController::class, 'rahul']);
    Route::get('/developer/ahmad-fajri', [DeveloperController::class, 'ahmad']);
    Route::get('/developer/yabes-maychel', [DeveloperController::class, 'yabes']);
    Route::get('/developer/yasmine', [DeveloperController::class, 'yasmine']);

    Route::group(['middleware' => ['guest:web,dosen,mahasiswa']], function () {
        Route::get('/', [LoginController::class, 'index']);
        Route::post('/', [LoginController::class, 'postlogin'])->name('login');
    });



    Route::group(['middleware' => ['auth:dosen,web,mahasiswa']], function () {
        Route::post('/logout', [LoginController::class, 'logout']);
    });



    Route::group(['middleware' => ['auth:mahasiswa']], function () {

        // Route::get('/kp-skripsi', [PendaftaranKPController::class, 'index']);
        // Route::get('/daftar/kerja-praktek', [PendaftaranKPController::class, 'indexkp']);
        // Route::get('/daftar/skripsi', [PendaftaranSkripsiController::class, 'indexskripsi']);

        Route::get('/usulankp/index', [PendaftaranKPController::class, 'indexusulankp']);

        Route::get('/usulankp/create', [PendaftaranKPController::class, 'createusulankp']);
        Route::post('/usulankp/create', [PendaftaranKPController::class, 'storeusulankp']);
        Route::get('/usulankp-ulang/create', [PendaftaranKPController::class, 'createusulankp_ulang']);
        Route::post('/usulankp-ulang/create', [PendaftaranKPController::class, 'storeusulankp_ulang']);

        Route::get('/usuljudul/index', [PendaftaranSkripsiController::class, 'indexusuljudul']);
        Route::get('/usuljudul/create', [PendaftaranSkripsiController::class, 'createusuljudul']);
        Route::post('/usuljudul/create', [PendaftaranSkripsiController::class, 'storeusuljudul']);
        Route::get('/usuljudul-ulang/create', [PendaftaranSkripsiController::class, 'create_ulang_usuljudul']);
        Route::post('/usuljudul-ulang/create', [PendaftaranSkripsiController::class, 'store_ulang_usuljudul']);

        Route::get('/surat-permohonan-kp/{id}', [PendaftaranKPController::class, 'suratpermohonankp']);
        Route::get('/surat-permohonan-kp-data/{id}', [QRController::class, 'suratpermohonankp']);

        Route::get('/permohonankp/index', [PendaftaranKPController::class, 'indexpermohonan']);
        Route::get('/permohonankp/create/{id}', [PendaftaranKPController::class, 'createpermohonan']);
        Route::put('/permohonankp/create/{id}', [PendaftaranKPController::class, 'storepermohonan']);

        Route::get('/balasankp/index', [PendaftaranKPController::class, 'indexbalasan']);
        Route::get('/balasankp/create/{id}', [PendaftaranKPController::class, 'createbalasan']);
        Route::put('/balasankp/create/{id}', [PendaftaranKPController::class, 'storebalasan']);

        Route::get('/seminarkp/index', [PendaftaranKPController::class, 'indexsemkp']);
        Route::get('/daftar-semkp/create/{id}', [PendaftaranKPController::class, 'createsemkp']);
        Route::put('/daftar-semkp/create/{id}', [PendaftaranKPController::class, 'storesemkp']);

        Route::get('/kpti10-kp/index', [PendaftaranKPController::class, 'indexkpti_10']);
        Route::get('/kpti10-kp/create/{id}', [PendaftaranKPController::class, 'createkpti_10']);
        Route::put('/kpti10-kp/create/{id}', [PendaftaranKPController::class, 'storekpti_10']);

        Route::get('/daftar-sempro/index', [PendaftaranSkripsiController::class, 'indexsempro']);
        Route::get('/daftar-sempro/create/{id}', [PendaftaranSkripsiController::class, 'createsempro']);
        Route::put('/daftar-sempro/create/{id}', [PendaftaranSkripsiController::class, 'storesempro']);

        Route::get('/daftar-sidang/index', [PendaftaranSkripsiController::class, 'indexsidang']);
        Route::get('/daftar-sidang/create/{id}', [PendaftaranSkripsiController::class, 'createsidang']);
        Route::put('/daftar-sidang/create/{id}', [PendaftaranSkripsiController::class, 'storesidang']);

        Route::get('/perpanjangan-revisi/create/{id}', [PendaftaranSkripsiController::class, 'createperpanjangan_revisi']);
        Route::put('/perpanjangan-revisi/create/{id}', [PendaftaranSkripsiController::class, 'storeperpanjangan_revisi']);

        Route::get('/perpanjangan1-skripsi/create/{id}', [PendaftaranSkripsiController::class, 'createperpanjangan1_skripsi']);
        Route::put('/perpanjangan1-skripsi/create/{id}', [PendaftaranSkripsiController::class, 'storeperpanjangan1_skripsi']);

        Route::put('/perpanjangan2-skripsi/create/{id}', [PendaftaranSkripsiController::class, 'storeperpanjangan2_skripsi']);

        Route::get('/penyerahan-buku-skripsi/create/{id}', [PendaftaranSkripsiController::class, 'createbukti_buku_skripsi']);
        Route::put('/penyerahan-buku-skripsi/create/{id}', [PendaftaranSkripsiController::class, 'storebukti_buku_skripsi']);


        Route::get('/profil-mhs/editpasswordmhs', [MahasiswaProfilController::class, 'editpswmhs']);
        Route::put('/profil-mhs/editpasswordmhs', [MahasiswaProfilController::class, 'updatepswmhs']);
        Route::get('/jadwal', [PenjadwalanController::class, 'jadwal_mahasiswa']);
        Route::get('/jadwal/mahasiswa', [PenjadwalanController::class, 'seminar_mahasiswa']);
        Route::get('/seminar', [PenjadwalanController::class, 'riwayat_mahasiswa']);



        // Route::get('/daftar-kp', [PendaftaranKPController::class, 'index']);

        Route::get('/daftar-kp', [PendaftaranController::class, 'daftarkp_mahasiswa']);

        Route::get('/daftar-kp/create', [PendaftaranKPController::class, 'createusulankp']);
        Route::post('/daftar-kp/create', [PendaftaranKPController::class, 'store']);

        Route::get('/status-kp/create', [PendaftaranKPController::class, 'create']);
        Route::post('/status-kp/create', [PendaftaranKPController::class, 'store']);


        //    INVENTARIS
        Route::get('/inventaris/peminjamanmhs', [PeminjamanMahasiswaController::class, 'index'])->name('peminjaman');
        Route::get('/inventaris/riwayatmhs', [RiwayatController::class, 'riwayatmhs'])->name('riwayatmhs');
        Route::get('/inventaris/delete/{id}', [PeminjamanMahasiswaController::class, 'destroy']);
        Route::get('/inventaris/edit/{id}', [PeminjamanMahasiswaController::class, 'edit']);
        Route::post('/inventaris/update/{id}', [PeminjamanMahasiswaController::class, 'update']);

        Route::get('/inventaris/formpinjam', [UsulanController::class, 'index'])->name('formusulan');
        Route::post('/inventaris/usulan', [UsulanController::class, 'create']);
    });



    Route::group(['middleware' => ['auth:web']], function () {

        //Murdillah
        // Route::get('/ruangan', [RuanganController::class, 'index']);
        // Route::get('/ruangan/create', [RuanganController::class, 'create']);
        // Route::post('/ruangan/create', [RuanganController::class, 'store']);
        // Route::get('/ruangan/edit/{ruangan:id}', [RuanganController::class, 'edit']);
        // Route::put('/ruangan/edit/{ruangan:id}', [RuanganController::class, 'update']);
        // Route::delete('/ruangan/{ruangan:id}', [RuanganController::class, 'destroy']);

        // Route::get('/jadwalkan', [JadwalkanController::class, 'index']);
        // Route::get('/jadwalkan/create', [JadwalkanController::class, 'create']);
        // Route::post('/jadwalkan/create', [JadwalkanController::class, 'store']);


        Route::get('/kp-skripsi/persetujuan/perpanjangan-revisi/{id}', [PendaftaranSkripsiController::class, 'detailpersetujuan_perpanjangan_revisi']);

        //APPROVAL ADMIN
        Route::put('/usulankp/admin/approve/{id}', [PendaftaranKPController::class, 'approveusulankp_admin']);
        Route::put('/usulankp/admin/tolak/{id}', [PendaftaranKPController::class, 'tolakusulankp_admin']);
        Route::put('/semkp/admin/approve/{id}', [PendaftaranKPController::class, 'approvesemkp_admin']);
        Route::put('/semkp/admin/tolak/{id}', [PendaftaranKPController::class, 'tolaksemkp_admin']);

        Route::put('/daftar-sempro/admin/approve/{id}', [PendaftaranSkripsiController::class, 'approvesempro_admin']);
        Route::put('/daftar-sempro/admin/tolak/{id}', [PendaftaranSkripsiController::class, 'tolaksempro_admin']);
        Route::put('/daftar-sidang/admin/approve/{id}', [PendaftaranSkripsiController::class, 'approvesidang_admin']);
        Route::put('/daftar-sidang/admin/tolak/{id}', [PendaftaranSkripsiController::class, 'tolaksidang_admin']);


        Route::put('/usuljudul/admin/approve/{id}', [PendaftaranSkripsiController::class, 'approveusuljudul_admin']);
        Route::put('/usuljudul/admin/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakusuljudul_admin']);
        Route::put('/persetujuansempro/admin/approve/{id}', [PenjadwalanSemproController::class, 'approve_sempro_admin']);
        Route::put('/persetujuansempro/admin/tolak/{id}', [PenjadwalanSemproController::class, 'tolak_sempro_admin']);

        Route::get('/kerja-praktek/admin/index', [PendaftaranController::class, 'pendaftaran_kp_admin']);
        Route::get('/sempro/admin/index', [PendaftaranController::class, 'pendaftaran_sempro_admin']);
        Route::get('/sidang/admin/index', [PendaftaranController::class, 'pendaftaran_sidang_admin']);
        Route::get('/persetujuan/admin/index', [PendaftaranController::class, 'persetujuan_admin']);
        Route::get('/persetujuan/admin/detail/usulankp/{id}', [PendaftaranController::class, 'detail_persetujuan_admin']);
        Route::get('/persetujuan/admin/detail/kpti10/{id}', [PendaftaranController::class, 'detail_persetujuan_kpti10_admin']);
        Route::get('/persetujuan/admin/detail/usulanjudul/{id}', [PendaftaranController::class, 'detail_persetujuan_usulanjudul_admin']);
        Route::get('/persetujuan/admin/detail/sempro/{id}', [PendaftaranController::class, 'detail_persetujuan_sempro_admin']);
        Route::get('/persetujuan/admin/detail/sidang/{id}', [PendaftaranController::class, 'detail_persetujuan_sidang_admin']);
        Route::get('/sidang/admin/perpanjangan-1/detail/{id}', [PendaftaranController::class, 'detail_perpanjangan_1_admin']);
        Route::get('/sidang/admin/perpanjangan-2/detail/{id}', [PendaftaranController::class, 'detail_perpanjangan_2_admin']);

        Route::get('/profil-staff/editpasswordstaff', [StaffProfilController::class, 'editpswstaff']);
        Route::put('/profil-staff/editpasswordstaff', [StaffProfilController::class, 'updatepswstaff']);

        Route::get('/dosen', [DosenController::class, 'index']);
        Route::get('/dosen/create', [DosenController::class, 'create']);
        Route::post('/dosen/create', [DosenController::class, 'store']);
        Route::get('/dosen/edit/{dosen:id}', [DosenController::class, 'edit']);
        Route::put('/dosen/edit/{dosen:id}', [DosenController::class, 'update']);
        Route::delete('/dosen/{dosen:id}', [DosenController::class, 'destroy']);

        Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
        Route::get('/mahasiswa/create', [MahasiswaController::class, 'create']);
        Route::post('/mahasiswa/create', [MahasiswaController::class, 'store']);
        Route::get('/mahasiswa/edit/{mahasiswa:id}', [MahasiswaController::class, 'edit']);
        Route::put('/mahasiswa/edit/{mahasiswa:id}', [MahasiswaController::class, 'update']);
        Route::delete('/mahasiswa/{mahasiswa:id}', [MahasiswaController::class, 'destroy']);

        Route::get('/role', [RoleController::class, 'index']);
        Route::get('/role/create', [RoleController::class, 'create']);
        Route::post('/role/create', [RoleController::class, 'store']);
        Route::get('/role/edit/{role:id}', [RoleController::class, 'edit']);
        Route::put('/role/edit/{role:id}', [RoleController::class, 'update']);
        Route::delete('/role/{role:id}', [RoleController::class, 'destroy']);

        Route::get('/prodi', [ProdiController::class, 'index']);
        Route::get('/prodi/create', [ProdiController::class, 'create']);
        Route::post('/prodi/create', [ProdiController::class, 'store']);
        Route::get('/prodi/edit/{prodi:id}', [ProdiController::class, 'edit']);
        Route::put('/prodi/edit/{prodi:id}', [ProdiController::class, 'update']);
        Route::delete('/prodi/{prodi:id}', [ProdiController::class, 'destroy']);

        Route::get('/konsentrasi', [KonsentrasiController::class, 'index']);
        Route::get('/konsentrasi/create', [KonsentrasiController::class, 'create']);
        Route::post('/konsentrasi/create', [KonsentrasiController::class, 'store']);
        Route::get('/konsentrasi/edit/{konsentrasi:id}', [KonsentrasiController::class, 'edit']);
        Route::put('/konsentrasi/edit/{konsentrasi:id}', [KonsentrasiController::class, 'update']);
        Route::delete('/konsentrasi/{konsentrasi:id}', [KonsentrasiController::class, 'destroy']);

        Route::get('/user', [UserController::class, 'index']);
        Route::get('/user/create', [UserController::class, 'create']);
        Route::post('/user/create', [UserController::class, 'store']);
        Route::get('/user/edit/{user:id}', [UserController::class, 'edit']);
        Route::put('/user/edit/{user:id}', [UserController::class, 'update']);
        Route::delete('/user/{user:id}', [UserController::class, 'destroy']);

        Route::get('/plp', [UserController::class, 'plp_index']);
        Route::get('/plp/create', [UserController::class, 'plp_create']);
        Route::post('/plp/create', [UserController::class, 'plp_store']);
        Route::get('/plp/edit/{user:id}', [UserController::class, 'plp_edit']);
        Route::put('/plp/edit/{user:id}', [UserController::class, 'plp_update']);


        //RESET PASSWORD 
        Route::put('/reset-password/mahasiswa/{id}', [MahasiswaController::class, 'reset_password']);
        Route::put('/reset-password/dosen/{id}', [DosenController::class, 'reset_password']);
        Route::put('/reset-password/user/{id}', [UserController::class, 'reset_password']);
    });


    Route::group(['middleware' => ['auth:dosen']], function () {
        Route::get('/pendaftaran', [PendaftaranController::class, 'pendaftaran_kp_pembimbing']);
        Route::get('/kerja-praktek', [PendaftaranController::class, 'pendaftaran_kp']);


        Route::get('/kp-skripsi/pembimbing-penguji/riwayat-seminar', [PendaftaranController::class, 'riwayat_seminar_pembimbing_penguji']);
        Route::get('/pembimbing-penguji/riwayat-bimbingan', [PendaftaranController::class, 'riwayat_bimbingan_pembimbing_penguji']);



        Route::get('/skripsi', [PendaftaranController::class, 'pendaftaran_skripsi']);
        Route::get('/persetujuan-kp-skripsi', [PendaftaranController::class, 'persetujuankpskripsi_dosen']);
        Route::get('/kp-skripsi/persetujuan-skripsi', [PendaftaranController::class, 'persetujuanskripsi_dosen']);
        // Route::get('/kp-skripsi/persetujuan/usulankp/{id}', [PendaftaranKPController::class, 'detailpersetujuan_usulankp']);
        Route::get('/kp-skripsi/persetujuan/suratperusahaan/{id}', [PendaftaranKPController::class, 'detailpersetujuan_balasankp']);
        // Route::get('/kp-skripsi/persetujuan/semkp/{id}', [PendaftaranKPController::class, 'detailpersetujuan_semkp']);
        Route::get('/kp-skripsi/persetujuan/kpti10/{id}', [PendaftaranKPController::class, 'detailpersetujuan_kpti10']);
        Route::get('/pembimbing/kerja-praktek', [PendaftaranController::class, 'pendaftaran_kp_pembimbing']);

        Route::get('/kp-skripsi/persetujuan/usulanjudul/{id}', [PendaftaranSkripsiController::class, 'detailpersetujuan_usulanjudul']);
        Route::get('/kp-skripsi/persetujuan/sempro/{id}', [PendaftaranSkripsiController::class, 'detailpersetujuan_daftarsempro']);
        Route::get('/kp-skripsi/persetujuan/sidang/{id}', [PendaftaranSkripsiController::class, 'detailpersetujuan_daftarsidang']);
        Route::get('/kp-skripsi/persetujuan/perpanjangan-revisi/{id}', [PendaftaranSkripsiController::class, 'detailpersetujuan_perpanjangan_revisi']);
        Route::get('/kp-skripsi/persetujuan/perpanjangan-1/{id}', [PendaftaranSkripsiController::class, 'detailpersetujuan_perpanjangan_1']);
        Route::get('/kp-skripsi/persetujuan/perpanjangan-2/{id}', [PendaftaranSkripsiController::class, 'detailpersetujuan_perpanjangan_2']);
        Route::get('/kp-skripsi/persetujuan/bukti-buku-skripsi/{id}', [PendaftaranSkripsiController::class, 'detailpersetujuan_bukti_buku_skripsi']);
        Route::get('/kp-skripsi/pembimbing/perpanjangan-1/{id}', [PendaftaranSkripsiController::class, 'detail_perpanjangan_1_pemb']);
        Route::get('/kp-skripsi/pembimbing/perpanjangan-2/{id}', [PendaftaranSkripsiController::class, 'detail_perpanjangan_2_pemb']);
        Route::get('/kp-skripsi/pembimbing/perpanjangan-revisi/{id}', [PendaftaranSkripsiController::class, 'detail_perpanjangan_revisi_pemb']);
        Route::get('/kp-skripsi/pembimbing/bukti-buku-skripsi/{id}', [PendaftaranSkripsiController::class, 'detail_bukti_buku_skripsi']);
        Route::get('/kp-skripsi/riwayat/pembimbing/bukti-buku-skripsi/{id}', [PendaftaranSkripsiController::class, 'detail_riwayat_pemb_bukti_buku_skripsi']);


        Route::get('/pembimbing/skripsi', [PendaftaranController::class, 'pendaftaran_skripsi_pembimbing']);

        Route::get('/permohonan-kp/detail/pembimbing/{id}', [PendaftaranController::class, 'detailpermohonankp_pembimbing']);

        Route::get('/daftar-semkp/detail/pembimbing/{id}', [PendaftaranController::class, 'detailusulan_semkp_pembimbing']);

        Route::put('/usulan-semkp/pembimbing/approve/{id}', [PendaftaranKPController::class, 'approveusulan_semkp_pemb']);
        Route::put('/usulan-semkp/pembimbing/tolak/{id}', [PendaftaranKPController::class, 'tolakusulan_semkp_pemb']);

        Route::get('/usuljudul/detail/pembimbing2/{id}', [PendaftaranController::class, 'detailusuljudul_pembimbing2']);

        Route::put('/usulankp/pembimbing/approve/{id}', [PendaftaranKPController::class, 'approveusulankp_pemb']);
        Route::put('/usulankp/pembimbing/tolak/{id}', [PendaftaranKPController::class, 'tolakusulankp_pemb']);


        Route::put('/permohonankp/pembimbing/approve/{id}', [PendaftaranKPController::class, 'approvepermohonankp_pemb']);
        Route::put('/permohonankp-koordinator/pembimbing/tolak/{id}', [PendaftaranKPController::class, 'tolakpermohonankp_pembimbing']);

        Route::put('/usuljudul/pembimbing1/approve/{id}', [PendaftaranSkripsiController::class, 'approveusuljudul_pembimbing']);
        Route::put('/usuljudul/pembimbing1/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakusuljudul_pembimbing']);

        Route::put('/daftarsempro/pembimbing1/approve/{id}', [PendaftaranSkripsiController::class, 'approvedaftarsempro_pembimbing']);
        Route::put('/daftarsempro/pembimbing1/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakdaftarsempro_pembimbing']);

        Route::put('/daftarsidang/pembimbing1/approve/{id}', [PendaftaranSkripsiController::class, 'approvedaftarsidang_pembimbing']);
        Route::put('/daftarsidang/pembimbing1/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakdaftarsidang_pembimbing']);

        Route::put('/daftarsempro/pembimbing2/approve/{id}', [PendaftaranSkripsiController::class, 'approvedaftarsempro_pembimbing2']);
        Route::put('/daftarsempro/pembimbing2/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakdaftarsempro_pembimbing2']);

        Route::put('/daftarsidang/pembimbing2/approve/{id}', [PendaftaranSkripsiController::class, 'approvedaftarsidang_pembimbing2']);
        Route::put('/daftarsidang/pembimbing2/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakdaftarsidang_pembimbing2']);

        Route::put('/usuljudul/pembimbing2/approve/{id}', [PendaftaranSkripsiController::class, 'approveusuljudul_pembimbing2']);
        Route::put('/usuljudul/pembimbing2/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakusuljudul_pembimbing2']);

        Route::put('/selesaiseminar-kp/pembimbing/approve/{id}', [PendaftaranKPController::class, 'approveselesaiseminarkp_pemb']);
        Route::put('/selesaiseminar-kp/pembimbing/tolak/{id}', [PendaftaranKPController::class, 'tolakselesaiseminarkp_pemb']);

        Route::put('/selesaisempro/pembimbing/approve/{id}', [PendaftaranSkripsiController::class, 'approveselesaisempro_pemb']);
        Route::put('/selesaisempro/pembimbing/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakselesaisempro_pemb']);

        Route::put('/lewat-batas-sidang/hapus/{id}', [PendaftaranSkripsiController::class, 'lewat_batas_sidang']);

        Route::put('/selesaisidang/pembimbing/approve/{id}', [PendaftaranSkripsiController::class, 'approveselesaisidang_pemb']);
        Route::put('/selesaisidang/pembimbing/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakselesaisidang_pemb']);

        Route::put('/perpanjangan1/pembimbing/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan1_pembimbing']);
        Route::put('/perpanjangan1/pembimbing/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan1_pembimbing']);
        Route::put('/perpanjangan2/pembimbing/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan2_pembimbing']);
        Route::put('/perpanjangan2/pembimbing/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan2_pembimbing']);

        Route::put('/perpanjangan-revisi/pembimbing/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan_revisi_pembimbing']);
        Route::put('/perpanjangan-revisi/pembimbing/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan_revisi_pembimbing']);

        // Route::put('/perpanjangan-revisi/pembimbing2/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan_revisi_pembimbing1']);
        // Route::put('/perpanjangan-revisi/pembimbing2/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan_revisi_pembimbing1']);

        Route::get('/pendaftaran/kp', [PendaftaranController::class, 'daftarkp_dosen']);


        Route::put('/pendaftaran-kp/approve/{id}', [PendaftaranKPController::class, 'approve']);
        Route::get('/riwayatkp', [PendaftaranKPController::class, 'riwayat']);
        // Route::get('/nilai-kp/{id}', [PenjadwalanKPController::class, 'nilaikp']);
        // Route::get('/perbaikan-kp/{id}', [PenjadwalanKPController::class, 'perbaikan']);


        Route::get('/profil-dosen', [DosenProfilController::class, 'index']);
        Route::get('/profil-dosen/editfotodsn/{dosen:id}', [DosenProfilController::class, 'editfotodsn']);
        Route::put('/profil-dosen/editfotodsn/{dosen:id}', [DosenProfilController::class, 'updatefotodsn']);
        Route::get('/profil-dosen/editpassworddsn', [DosenProfilController::class, 'editpswdsn']);
        Route::put('/profil-dosen/editpassworddsn', [DosenProfilController::class, 'updatepswdsn']);


        Route::get('/prodi/kp-skripsi/seminar', [PenilaianController::class, 'index']);
        Route::get('/kp-skripsi/seminar-pembimbing-penguji', [PenilaianController::class, 'indexpembimbing']);
        Route::get('/kp-skripsi/riwayat-penilaian-seminar', [PenilaianController::class, 'riwayat']);
        Route::get('/kp-skripsi/riwayat-penilaian-skripsi', [PenilaianController::class, 'riwayatskripsi']);

        Route::get('/penilaian-kp', [PenilaianKPController::class, 'index']);
        Route::get('/penilaian-kp/create/{penjadwalan_kp:id}', [PenilaianKPController::class, 'create']);
        Route::post('/penilaian-kp-pembimbing/create/{penjadwalan_kp:id}', [PenilaianKPController::class, 'store_pembimbing']);
        Route::post('/penilaian-kp-penguji/create/{penjadwalan_kp:id}', [PenilaianKPController::class, 'store_penguji']);

        Route::post('/penilaian-kp-penguji-pembimbing-sama/create/{penjadwalan_kp:id}', [PenilaianKPController::class, 'store_pembimbing_penguji_sama']);
        Route::get('/penilaian-kp/edit/{penjadwalan_kp:id}', [PenilaianKPController::class, 'edit']);
        Route::put('/penilaian-kp-penguji/edit/{penilaian_kp_penguji:id}', [PenilaianKPController::class, 'update_penguji']);
        Route::put('/penilaian-kp-pembimbing/edit/{penilaian_kp_pembimbing:id}', [PenilaianKPController::class, 'update_pembimbing']);

        Route::put('/penilaian-kp-pembimbing-penguji/edit/sama/{penilaian_kp_penguji:penjadwalan_kp_id}', [PenilaianKPController::class, 'update_pembimbing_penguji_sama']);


        Route::put('/penilaian-kp/approve/{id}', [PenjadwalanKPController::class, 'approve']);
        Route::put('/penilaian-kp/tolak/{id}', [PenjadwalanKPController::class, 'tolak']);
        Route::get('/riwayat-penilaian-kp', [PenilaianKPController::class, 'riwayat']);
        Route::get('/nilai-kp/{id}', [PenjadwalanKPController::class, 'nilaikp']);
        Route::get('/perbaikan-kp/{id}', [PenjadwalanKPController::class, 'perbaikan']);

        Route::get('/undangan-kp/{id}', [UndanganSeminarController::class, 'undangan_kp']);
        Route::get('/undangan-sempro/{id}', [UndanganSeminarController::class, 'undangan_sempro']);
        Route::get('/undangan-sidang/{id}', [UndanganSeminarController::class, 'undangan_sidang']);

        Route::get('/penilaian-sempro', [PenilaianSemproController::class, 'index']);
        Route::get('/penilaian-sempro/create/{penjadwalan_sempro:id}', [PenilaianSemproController::class, 'create']);
        Route::post('/penilaian-sempro-pembimbing/create/{penjadwalan_sempro:id}', [PenilaianSemproController::class, 'store_pembimbing']);
        Route::post('/penilaian-sempro-penguji/create/{penjadwalan_sempro:id}', [PenilaianSemproController::class, 'store_penguji']);
        Route::get('/penilaian-sempro/edit/{penjadwalan_sempro:id}', [PenilaianSemproController::class, 'edit']);
        Route::put('/penilaian-sempro-pembimbing/edit/{penilaian_sempro_pembimbing:id}', [PenilaianSemproController::class, 'update_pembimbing']);
        Route::put('/penilaian-sempro-penguji/edit/{penjadwalan_sempro_id:id}', [PenilaianSemproController::class, 'update_penguji']);


        Route::put('/penilaian-sempro/approve/{id}', [PenjadwalanSemproController::class, 'approve']);
        Route::put('/penilaian-sempro/gagal/{id}', [PenjadwalanSemproController::class, 'gagal']);
        Route::get('/riwayat-penilaian-sempro', [PenilaianSemproController::class, 'riwayat']);
        Route::get('/nilai-sempro/{id}', [PenjadwalanSemproController::class, 'nilaisempro']);
        Route::get('/perbaikan-sempro/{id}', [PenjadwalanSemproController::class, 'perbaikan']);
        Route::post('/revisi-proposal/create/{id}', [PenjadwalanSemproController::class, 'revisiproposal']);
        Route::post('/catatansempro/create/{id}', [PenjadwalanSemproController::class, 'catatansempro']);


        Route::get('/penilaian-skripsi', [PenilaianSkripsiController::class, 'index']);
        Route::get('/penilaian-skripsi/create/{penjadwalan_skripsi:id}', [PenilaianSkripsiController::class, 'create']);
        Route::post('/penilaian-skripsi-pembimbing/create/{penjadwalan_skripsi:id}', [PenilaianSkripsiController::class, 'store_pembimbing']);
        Route::post('/penilaian-skripsi-penguji/create/{penjadwalan_skripsi:id}', [PenilaianSkripsiController::class, 'store_penguji']);
        Route::get('/penilaian-skripsi/edit/{penjadwalan_skripsi:id}', [PenilaianSkripsiController::class, 'edit']);
        Route::put('/penilaian-skripsi-pembimbing/edit/{penilaian_skripsi_pembimbing:id}', [PenilaianSkripsiController::class, 'update_pembimbing']);
        Route::put('/penilaian-skripsi-penguji/edit/{penilaian_skripsi_penguji:id}', [PenilaianSkripsiController::class, 'update_penguji']);
        Route::put('/penilaian-skripsi/approve/{id}', [PenjadwalanSkripsiController::class, 'approve']);
        Route::put('/penilaian-skripsi/tolak/{id}', [PenjadwalanSkripsiController::class, 'tolak']);
        Route::get('/riwayat-penilaian-skripsi', [PenilaianSkripsiController::class, 'riwayat']);
        Route::get('/nilai-skripsi/{id}', [PenjadwalanSkripsiController::class, 'nilaiskripsi']);
        Route::get('/perbaikan-skripsi/{id}', [PenjadwalanSkripsiController::class, 'perbaikan']);
        Route::post('/revisi-skripsi/create/{id}', [PenjadwalanSkripsiController::class, 'revisiskripsi']);
        Route::post('/catatanskripsi/create/{id}', [PenjadwalanSkripsiController::class, 'catatanskripsi']);
        Route::put('/nilaijurnal/create/{id}', [PenjadwalanSkripsiController::class, 'nilaijurnal']);



        //    INVENTARIS DOSEN
        Route::get('/inventaris/peminjaman-dosen', [PeminjamanDosenController::class, 'index'])->name('peminjamandsn');
        Route::get('/inventaris/riwayat-dosen', [RiwayatController::class, 'riwayatdsn'])->name('riwayatdsn');
        Route::get('/inventaris/delete-dosen/{id}', [PeminjamanDosenController::class, 'destroydsn']);
        Route::get('/inventaris/edit-dosen/{id}', [PeminjamanDosenController::class, 'editdsn']);
        Route::post('/inventaris/update-dosen/{id}', [PeminjamanDosenController::class, 'updatedsn']);

        Route::get('/inventaris/formpinjam-dosen', [UsulanController::class, 'indexdsn'])->name('formusulandosen');
        Route::post('/inventaris/usulan-dosen', [UsulanController::class, 'createdsn']);
    });




    Route::group(['middleware' => ['auth:web']], function () {

        Route::get('/form', [PenjadwalanController::class, 'index'])->name('form');
        Route::get('/riwayat-penjadwalan', [PenjadwalanController::class, 'riwayat']);
        Route::delete('/clear', [PenjadwalanController::class, 'clear']);

        Route::get('/form-kp', [PenjadwalanKPController::class, 'index']);
        Route::get('/form-kp/create', [PenjadwalanKPController::class, 'create']);
        Route::post('/form-kp/create', [PenjadwalanKPController::class, 'store']);

        Route::delete('/form-kp/{penjadwalan_kp:id}', [PenjadwalanKPController::class, 'destroy']);
        Route::get('/riwayat-penjadwalan-kp', [PenjadwalanKPController::class, 'riwayat']);


        Route::get('/form-sempro', [PenjadwalanSemproController::class, 'index']);
        Route::get('/form-sempro/create', [PenjadwalanSemproController::class, 'create']);
        Route::post('/form-sempro/create', [PenjadwalanSemproController::class, 'store']);
        Route::get('/form-sempro/edit/{penjadwalan_sempro:id}', [PenjadwalanSemproController::class, 'edit']);
        Route::put('/form-sempro/edit/{penjadwalan_sempro:id}', [PenjadwalanSemproController::class, 'update']);
        Route::delete('/form-sempro/{penjadwalan_sempro:id}', [PenjadwalanSemproController::class, 'destroy']);
        Route::get('/riwayat-penjadwalan-sempro', [PenjadwalanSemproController::class, 'riwayat']);
        Route::get('/penilaian-sempro/riwayat-judul/{id}', [PenjadwalanSemproController::class, 'riwayatjudul']);

        Route::get('/form-skripsi', [PenjadwalanSkripsiController::class, 'index']);
        Route::get('/form-skripsi/create', [PenjadwalanSkripsiController::class, 'create']);
        Route::post('/form-skripsi/create', [PenjadwalanSkripsiController::class, 'store']);
        Route::get('/form-skripsi/edit/{penjadwalan_skripsi:id}', [PenjadwalanSkripsiController::class, 'edit']);
        Route::put('/form-skripsi/edit/{penjadwalan_skripsi:id}', [PenjadwalanSkripsiController::class, 'update']);
        Route::delete('/form-skripsi/{penjadwalan_skripsi:id}', [PenjadwalanSkripsiController::class, 'destroy']);
        Route::get('/riwayat-penjadwalan-skripsi', [PenjadwalanSkripsiController::class, 'riwayat']);
        Route::get('/penilaian-skripsi/riwayat-judul/{id}', [PenjadwalanSkripsiController::class, 'riwayatjudul']);


        // INVENTARIS
        Route::get('/inventaris/peminjamanadm', [PeminjamanAdminController::class, 'index'])->name('peminjamanadm');
        Route::get('/inventaris/setuju/{id}', [PeminjamanAdminController::class, 'setuju']);
        Route::get('/inventaris/tolak/{id}', [PeminjamanAdminController::class, 'ditolak']);
        Route::get('/inventaris/kembali/{id}', [PeminjamanAdminController::class, 'kembali']);
        Route::get('/inventaris/riwayatadm', [RiwayatController::class, 'riwayat'])->name('riwayatadm');
        Route::get('/inventaris/stok', [BarangController::class, 'index'])->name('stok');
        Route::post('/inventaris/stokbaru', [BarangController::class, 'create'])->name('stokbaru');
        Route::get('/inventaris/tambahbarang', [BarangController::class, 'addbarang'])->name('tambahbarang');
        Route::delete('/inventaris/deletebarang/{id}', [BarangController::class, 'destroy'])->name('deletebarang');
        Route::get('/inventaris/editbarang/{id}', [BarangController::class, 'edit'])->name('editbarang');
        Route::put('/inventaris/updatebarang/{id}', [BarangController::class, 'update'])->name('updatebarang');

        // PENGATURAN KUOTA BIMBINGAN
        Route::get('/kapasitas-bimbingan/index', [PendaftaranKPController::class, 'kapasitas_index']);
        Route::get('/kapasitas-bimbingan/edit/{id}', [PendaftaranKPController::class, 'kapasitas_bimbingan_edit']);
        Route::post('/kapasitas-bimbingan/edit/{id}', [PendaftaranKPController::class, 'kapasitasbimbingan_store']);
    });

    Route::group(['middleware' => ['auth:web,dosen']], function () {
        Route::get('/ceknilai-kp/{id}', [PenjadwalanKPController::class, 'ceknilaikp']);
        Route::get('/nilai-kp/{id}', [PenjadwalanKPController::class, 'nilaikp']);
        Route::get('/beritaacara-kp/{id}', [PenjadwalanKPController::class, 'beritaacarakp']);

        Route::get('/perbaikan-pengujikp/{id}/{penguji}', [PenjadwalanKPController::class, 'perbaikanpengujikp']);

        Route::get('/nilai-sempro-pembimbing/{id}/{pembimbing}', [PenjadwalanSemproController::class, 'nilaipembimbing']);
        Route::get('/nilai-sempro-penguji/{id}/{penguji}', [PenjadwalanSemproController::class, 'nilaipenguji']);
        Route::get('/nilai-skripsi-pembimbing/{id}/{pembimbing}', [PenjadwalanSkripsiController::class, 'nilaipembimbing']);
        Route::get('/nilai-skripsi-penguji/{id}/{penguji}', [PenjadwalanSkripsiController::class, 'nilaipenguji']);
        Route::get('/perbaikan-pengujisempro/{id}/{penguji}', [PenjadwalanSemproController::class, 'perbaikanpengujisempro']);
        Route::get('/perbaikan-pengujiskripsi/{id}/{penguji}', [PenjadwalanSkripsiController::class, 'perbaikanpengujiskripsi']);
        Route::get('/penilaian-sempro/cek-nilai/{id}', [PenjadwalanSemproController::class, 'ceknilai']);
        Route::get('/penilaian-sempro/beritaacara-sempro/{id}', [PenjadwalanSemproController::class, 'beritaacarasempro']);
        Route::get('/penilaian-skripsi/cek-nilai/{id}', [PenjadwalanSkripsiController::class, 'ceknilai']);
        Route::get('/penilaian-skripsi/draft-ba/{id}', [PenjadwalanSkripsiController::class, 'draft']);
        Route::get('/penilaian-skripsi/beritaacara-skripsi/{id}', [PenjadwalanSkripsiController::class, 'beritaacaraskripsi']);
    });

    Route::group(['middleware' => ['auth:web,dosen,mahasiswa']], function () {

        Route::get('/kpti10-kp/detail/{id}', [PendaftaranKPController::class, 'detailkpti_10']);
        Route::get('/kpti10-kp/detail/riwayat/{id}', [PendaftaranKPController::class, 'detail_riwayat_prodi_kpti_10']);
        Route::get('/perpanjangan-revisi/detail/{id}', [PendaftaranSkripsiController::class, 'detailperpanjangan_revisi']);
        Route::get('/perpanjangan-1/detail/{id}', [PendaftaranSkripsiController::class, 'detailperpanjangan_1']);
        Route::get('/perpanjangan-2/detail/{id}', [PendaftaranSkripsiController::class, 'detailperpanjangan_2']);
        Route::get('/bukti-buku-skripsi/detail/{id}', [PendaftaranSkripsiController::class, 'detailbukti_buku_skripsi']);
        Route::get('/bukti-buku-skripsi/riwayat/detail/{id}', [PendaftaranSkripsiController::class, 'detail_riwayat_prodi_bukti_buku_skripsi']);

        Route::get('/statistik', [PendaftaranController::class, 'kuotabimbingan']);
        Route::get('/detail/kuota-bimbingan/kp/{nip}', [PendaftaranController::class, 'detail_kuota_bimbingan_kp']);
        Route::get('/detail/kuota-bimbingan/skripsi/{nip}', [PendaftaranController::class, 'detail_kuota_bimbingan_skripsi']);
        Route::get('/kuota-bimbingan/kp', [PendaftaranController::class, 'kuotabimbingan_kp']);
        Route::get('/kuota-bimbingan/skripsi', [PendaftaranController::class, 'kuotabimbingan_skripsi']);


        Route::get('/daftar-semkp/detail/{id}', [PendaftaranKPController::class, 'detailusulansemkp']);
        Route::get('/daftar-sempro/detail/{id}', [PendaftaranSkripsiController::class, 'detailsempro']);
        Route::get('/daftar-sidang/detail/{id}', [PendaftaranSkripsiController::class, 'detailsidang']);

        Route::get('/perbaikan-pengujikp/{id}/{penguji}', [PenjadwalanKPController::class, 'perbaikanpengujikp']);
        Route::get('/perbaikan-pengujisempro/{id}/{penguji}', [PenjadwalanSemproController::class, 'perbaikanpengujisempro']);
        Route::get('/perbaikan-pengujiskripsi/{id}/{penguji}', [PenjadwalanSkripsiController::class, 'perbaikanpengujiskripsi']);

        Route::get('/surat-permohonankp/{id}', [PendaftaranKPController::class, 'kpti_1']);
    });

    //ADMIN DAN KOORDINATOR

    Route::group(['middleware' => ['auth:dosen', 'cekrole:9,10,11']], function () {
        Route::get('/form-kp/edit/koordinator/{id}', [PenjadwalanKPController::class, 'edit_koordinator']);
        Route::put('/form-kp/edit/koordinator/{penjadwalan_kp:id}', [PenjadwalanKPController::class, 'update_koordinator']);

        Route::get('/form-sempro/edit/koordinator/{penjadwalan_sempro:id}', [PenjadwalanSemproController::class, 'edit_koordinator']);
        Route::put('/form-sempro/edit/koordinator/{penjadwalan_sempro:id}', [PenjadwalanSemproController::class, 'update_koordinator']);

        Route::get('/form-skripsi/edit/koordinator/{penjadwalan_skripsi:id}', [PenjadwalanSkripsiController::class, 'edit_koordinator']);
        Route::put('/form-skripsi/edit/koordinator/{penjadwalan_skripsi:id}', [PenjadwalanSkripsiController::class, 'update_koordinator']);
    });
    Route::group(['middleware' =>  ['auth:web']], function () {
        Route::get('/form-kp/edit/{id}', [PenjadwalanKPController::class, 'edit']);
        Route::put('/form-kp/edit/{penjadwalan_kp:id}', [PenjadwalanKPController::class, 'update']);
    });

    // BATAS

    Route::group(['middleware' => ['auth:dosen', 'cekrole:9,10,11']], function () {

        Route::get('/developer/create', [DeveloperController::class, 'create']);
        Route::post('/developer/create', [DeveloperController::class, 'store']);

        Route::get('/developer/edit/{id}', [DeveloperController::class, 'edit']);
        Route::put('/developer/edit/{id}', [DeveloperController::class, 'update']);

        Route::get('/persetujuan-koordinator', [PenjadwalanController::class, 'persetujuan_koordinator']);
        Route::get('/persetujuan-koordinator/detail/{id}', [PenjadwalanController::class, 'detail_persetujuan_koordinator']);

        Route::get('/riwayat-koordinator', [PenjadwalanController::class, 'riwayat_koordinator']);
        Route::put('/persetujuankp-koordinator/approve/{id}', [PenjadwalanKPController::class, 'approve_koordinator']);
        Route::put('/persetujuankp-koordinator/tolak/{id}', [PenjadwalanKPController::class, 'tolak_koordinator']);

        Route::put('/persetujuanskripsi-koordinator/approve/{id}', [PenjadwalanSkripsiController::class, 'approve_koordinator']);
        Route::put('/persetujuanskripsi-koordinator/tolak/{id}', [PenjadwalanSkripsiController::class, 'tolak_koordinator']);

        Route::put('/usulankp/koordinator/approve/{id}', [PendaftaranKPController::class, 'approveusulankp_koordinator']);
        Route::put('/usulankp/koordinator/tolak/{id}', [PendaftaranKPController::class, 'tolakusulan_koordinator']);
        Route::put('/balasankp/koordinator/approve/{id}', [PendaftaranKPController::class, 'approvebalasankp_koordinator']);
        Route::put('/balasankp/koordinator/tolak/{id}', [PendaftaranKPController::class, 'tolakbalasankp_koordinator']);
        Route::put('/kpti10/koordinator/approve/{id}', [PendaftaranKPController::class, 'approvekpti10_koordinator']);
        Route::put('/kpti10/koordinator/tolak/{id}', [PendaftaranKPController::class, 'tolakkpti10_koordinator']);

        Route::put('/usuljudul/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approveusuljudul']);
        Route::put('/usuljudul/koordinator/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakusuljudul_koordinator']);

        Route::put('/daftarsempro/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approvedaftarsempro_koordinator']);
        Route::put('/daftarsempro/koordinator/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakdaftarsempro_koordinator']);


        Route::put('/perpanjangan2/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan2_koordinator']);
        Route::put('/perpanjangan2/koordinator/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan2_koordinator']);

        Route::put('/perpanjangan-revisi/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan_revisi_koordinator']);
        Route::put('/perpanjangan-revisi/koordinator/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan_revisi_koordinator']);

        Route::put('/buku-skripsi/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approvebuku_skripsi_koordinator']);
        Route::put('/buku-skripsi/koordinator/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakbuku_skripsi_koordinator']);

        Route::put('/daftarsidang/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approvedaftarsidang_koordinator']);
        Route::put('/daftarsidang/koordinator/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakdaftarsidang_koordinator']);

        Route::put('/nilaikpkeluar/koordinator/approve/{id}', [PendaftaranKPController::class, 'approvenilai_keluar_koordinator']);

        Route::put('/nilaiskripsikeluar/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approvelulus_koordinator']);

        Route::put('/usulan-semkp/koordinator/approve/{id}', [PendaftaranKPController::class, 'approveusulan_semkp_koordinator']);
        Route::put('/usulan-semkp/koordinator/tolak/{id}', [PendaftaranKPController::class, 'tolak_semkp_koordinator']);
        Route::put('/daftar-sempro/koordinator/tolak/{id}', [PendaftaranSkripsiController::class, 'tolak_sempro_koordinator']);
        Route::put('/daftar-sempro/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approve_sempro_koordinator']);
        Route::put('/daftar-sidang/koordinator/tolak/{id}', [PendaftaranSkripsiController::class, 'tolak_sidang_koordinator']);
        Route::put('/daftar-sidang/koordinator/approve/{id}', [PendaftaranSkripsiController::class, 'approve_sidang_koordinator']);


        // Route::get('/daftarkp-koordinator/{id}', [PendaftaranController::class, 'daftarkp_koordinator_detail']);

        Route::put('/pendaftarankp-koordinator/approve/{id}', [PendaftaranKPController::class, 'approve_koordinator']);
        Route::put('/perdaftarankp-koordinator/tolak/{id}', [PendaftaranKPController::class, 'tolak_koordinator']);
    });

    // PLP

    Route::group(['middleware' => ['auth:web', 'cekrole:12']], function () {

        // INVENTARIS
        Route::get('/inventaris/peminjaman-plp', [PeminjamanPLPController::class, 'index'])->name('peminjamanplp');
        Route::get('/inventaris/setuju-plp/{id}', [PeminjamanPLPController::class, 'setujuplp']);
        Route::get('/inventaris/tolak-plp/{id}', [PeminjamanPLPController::class, 'ditolakplp']);
        Route::get('/inventaris/kembali-plp/{id}', [PeminjamanPLPController::class, 'kembaliplp']);
        Route::get('/inventaris/riwayat', [RiwayatController::class, 'riwayatplp'])->name('riwayatplp');
        Route::get('/inventaris/stok-plp', [BarangController::class, 'indexplp'])->name('stokplp');
        Route::post('/inventaris/stokbaru-plp', [BarangController::class, 'createplp'])->name('stokbaruplp');
        Route::get('/inventaris/tambahbarang-plp', [BarangController::class, 'addbarangplp'])->name('tambahbarangplp');
        Route::delete('/inventaris/deletebarang-plp/{id}', [BarangController::class, 'destroyplp'])->name('deletebarangplp');
        Route::get('/inventaris/editbarang-plp/{id}', [BarangController::class, 'editplp'])->name('editbarangplp');
        Route::put('/inventaris/updatebarang-plp/{id}', [BarangController::class, 'updateplp'])->name('updatebarangplp');
    });



    Route::group(['middleware' => ['auth:dosen', 'cekrole:6,7,8']], function () {
        //APPROVAL USULAN KP
        Route::put('/usulankp/kaprodi/approve/{id}', [PendaftaranKPController::class, 'approveusulankp_kaprodi']);
        Route::put('/usulankp/kaprodi/tolak/{id}', [PendaftaranKPController::class, 'tolakusulan_kaprodi']);

        Route::get('/persetujuan-kaprodi/detail/{id}', [PenjadwalanController::class, 'detail_persetujuan_kaprodi']);

        Route::put('/usulan-semkp/kaprodi/approve/{id}', [PendaftaranKPController::class, 'approveusulan_semkp_kaprodi']);
        Route::put('/usulan-semkp/kaprodi/tolak/{id}', [PendaftaranKPController::class, 'tolak_semkp_kaprodi']);

        Route::put('/perpanjangan1/kaprodi/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan1_kaprodi']);
        Route::put('/perpanjangan1/kaprodi/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan1_kaprodi']);
        Route::put('/perpanjangan2/kaprodi/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan2_kaprodi']);
        Route::put('/perpanjangan2/kaprodi/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan2_kaprodi']);

        Route::put('/perpanjangan-revisi/kaprodi/approve/{id}', [PendaftaranSkripsiController::class, 'approveperpanjangan_revisi_kaprodi']);
        Route::put('/perpanjangan-revisi/kaprodi/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakperpanjangan_revisi_kaprodi']);


        Route::put('/usuljudul/kaprodi/approve/{id}', [PendaftaranSkripsiController::class, 'approveusuljudul_kaprodi']);
        Route::put('/usuljudul/kaprodi/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakusuljudul_kaprodi']);

        Route::put('/daftarsempro/kaprodi/approve/{id}', [PendaftaranSkripsiController::class, 'approvedaftarsempro_kaprodi']);
        Route::put('/daftarsempro/kaprodi/tolak/{id}', [PendaftaranSkripsiController::class, 'tolakdaftarsempro_kaprodi']);


        Route::put('/daftar-sidang/kaprodi/tolak/{id}', [PendaftaranSkripsiController::class, 'tolak_sidang_kaprodi']);
        Route::put('/daftar-sidang/kaprodi/approve/{id}', [PendaftaranSkripsiController::class, 'approve_sidang_kaprodi']);

        Route::get('/persetujuan-kaprodi', [PenjadwalanController::class, 'persetujuan_kaprodi']);
        Route::get('/riwayat-kaprodi', [PenjadwalanController::class, 'riwayat_kaprodi']);
        Route::put('/persetujuankp-kaprodi/approve/{id}', [PenjadwalanKPController::class, 'approve_kaprodi']);
        Route::put('/persetujuankp-kaprodi/tolak/{id}', [PenjadwalanKPController::class, 'tolak_kaprodi']);
        Route::put('/persetujuansempro-kaprodi/approve/{id}', [PenjadwalanSemproController::class, 'approve_kaprodi']);
        Route::put('/persetujuansempro-kaprodi/tolak/{id}', [PenjadwalanSemproController::class, 'tolak_kaprodi']);
        Route::put('/persetujuanskripsi-kaprodi/approve/{id}', [PenjadwalanSkripsiController::class, 'approve_kaprodi']);
        Route::put('/persetujuanskripsi-kaprodi/tolak/{id}', [PenjadwalanSkripsiController::class, 'tolak_kaprodi']);
    });
});

Route::group(['middleware' => ['auth:dosen,mahasiswa']], function () {

    Route::get('/kp-skripsi', [PendaftaranKPController::class, 'index']);

    Route::get('/usulan/detail/{id}', [PendaftaranKPController::class, 'detailusulankp']);

    Route::get('/usuljudul/detail/{id}', [PendaftaranSkripsiController::class, 'detailusuljudul']);


    // Route::get('/daftar-sempro/detail/{id}', [PendaftaranSkripsiController::class, 'detailsempro']);

    // Route::get('/daftar-sidang/detail/{id}', [PendaftaranSkripsiController::class, 'detailsidang']);

    Route::get('/permohonan-kp/detail/{id}', [PendaftaranKPController::class, 'detailpermohonankp']);

    Route::get('/balasan-kp/detail/{id}', [PendaftaranKPController::class, 'detailbalasankp']);
    // Route::get('/usulan-semkp/detail/{id}', [PendaftaranKPController::class, 'detailusulansemkp']);


    Route::get('/balasan-kp/index', [PendaftaranKPController::class, 'indexbalasan']);
    Route::get('/balasan-kp/create', [PendaftaranKPController::class, 'create']);
    Route::post('/balasan-kp/create', [PendaftaranKPController::class, 'store']);
});

Route::group(['middleware' => ['auth:dosen,web']], function () {
    Route::get('/usulan/detail/pembimbingprodi/{id}', [PendaftaranController::class, 'detailusulan_pembimbing']);
    Route::get('/suratperusahaan/detail/pembimbingprodi/{id}', [PendaftaranController::class, 'detailbalasan_pembimbing']);
    Route::get('/kpti10/detail/pembimbingprodi/{id}', [PendaftaranController::class, 'detailkpti10_pembimbing']);
    Route::get('/kpti10/detail/riwayat/pembimbingprodi/{id}', [PendaftaranController::class, 'detail_riwayat_kpti10_pembimbing']);

    Route::get('/prodi/riwayat', [PendaftaranController::class, 'riwayat_prodi']);

    Route::get('/kerja-praktek/nilai-keluar', [PendaftaranController::class, 'nilai_keluar']);

    Route::get('/skripsi/nilai-keluar', [PendaftaranController::class, 'lulus']);

    Route::get('/usuljudul/detail/pembimbing/{id}', [PendaftaranController::class, 'detailusuljudul_pembimbing']);

    Route::get('/daftar-sempro/detail/pembimbing/{id}', [PendaftaranController::class, 'detailsempro_pemb']);
    Route::get('/daftar-sidang/detail/pembimbing/{id}', [PendaftaranController::class, 'detailsidang_pemb']);

    Route::get('/kp-skripsi/persetujuan/usulankp/{id}', [PendaftaranKPController::class, 'detailpersetujuan_usulankp']);
    Route::get('/kp-skripsi/persetujuan/semkp/{id}', [PendaftaranKPController::class, 'detailpersetujuan_semkp']);
});
