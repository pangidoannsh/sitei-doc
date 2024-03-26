<?php

use Illuminate\Support\Facades\Route;

// DOKUMEN
use App\Http\Controllers\DistribusiDokumen\DistribusiDokumenController;
use App\Http\Controllers\DistribusiDokumen\PengumumanController;
use App\Http\Controllers\DistribusiDokumen\DokumenController;
use App\Http\Controllers\DistribusiDokumen\DokumenMentionController;
use App\Http\Controllers\DistribusiDokumen\LogoController;
use App\Http\Controllers\DistribusiDokumen\SertifikatController;
use App\Http\Controllers\DistribusiDokumen\PenerimaSertifikatController;
use App\Http\Controllers\DistribusiDokumen\PengelolaController;
use App\Http\Controllers\DistribusiDokumen\SemesterController;
use App\Http\Controllers\DistribusiDokumen\SuratCutiController;
use App\Http\Controllers\DistribusiDokumen\SuratController;

Route::middleware(["auth:web,dosen,mahasiswa", "cek-jenis-user"])->group(function () {
    // DiISTRIBUSI
    Route::get('/distribusi-dokumen', [DistribusiDokumenController::class, 'index'])->name('doc.index');
    Route::get('/distribusi-dokumen/arsip', [DistribusiDokumenController::class, 'arsip'])->name('doc.arsip');

    //PENGUMUMAN
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::get('/pengumuman/arsip', [PengumumanController::class, 'arsip'])->name('pengumuman.arsip');
    // Pengelola
    Route::get('/pengumuman/pengelola', [PengelolaController::class, 'indexPengumuman'])->name('pengumuman.pengelola');
    Route::get('/pengumuman/pengelola/arsip', [PengelolaController::class, 'arsipPengumuman'])->name('pengumuman.pengelola.arsip');
    // --- C U D ---
    Route::get('/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/pengumuman/{id}', [PengumumanController::class, 'detail'])->name('pengumuman.detail');
    Route::get('/pengumuman/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.delete');

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
    Route::post('/distribusi-dokumen/suratcuti/{id}/acc/admin', [SuratCutiController::class, 'accAdmin'])->name('suratcuti.acc.admin');
    Route::get('/distribusi-dokumen/suratcuti/{id}/approve', [SuratCutiController::class, 'approve'])->name('suratcuti.approve');
    Route::get('/distribusi-dokumen/suratcuti/{id}/download', [SuratCutiController::class, 'download'])->name('suratcuti.download');
    Route::delete('/distribusi-dokumen/suratcuti/{id}/reject', [SuratCutiController::class, 'reject'])->name('suratcuti.reject');

    // SURAT
    Route::get('/distribusi-dokumen/surat/create', [SuratController::class, 'create'])->name('surat.create');
    Route::get('/distribusi-dokumen/surat/{id}', [SuratController::class, 'detail'])->name('surat.detail');
    Route::put('/distribusi-dokumen/surat/{id}', [SuratController::class, 'update'])->name('surat.update');
    Route::get('/distribusi-dokumen/surat/{id}/edit', [SuratController::class, 'edit'])->name('surat.edit');
    Route::post('/distribusi-dokumen/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::delete('/distribusi-dokumen/surat/{id}', [SuratController::class, 'destroy'])->name('surat.delete');
    // Persetujuan Staf Admin Prodi
    Route::get('/distribusi-dokumen/surat/{id}/acc/stafprodi', [SuratController::class, 'accStafProdi'])->name('surat.acc.stafprodi');
    // Persetujuan Koordinator Prodi
    Route::get('/distribusi-dokumen/surat/{id}/acc/kaprodi', [SuratController::class, 'accKaprodi'])->name('surat.acc.kaprodi');
    // Persetujuan Staf Admin Jurusan
    Route::get('/distribusi-dokumen/surat/{id}/acc/stafJurusan', [SuratController::class, 'accStafJurusan'])->name('surat.acc.stafjurusan');
    // Persetujuan Dari Pihak Yang Dituju
    Route::get('/distribusi-dokumen/surat/{id}/acc', [SuratController::class, 'accept'])->name('surat.accept');
    // Perubahan Tujuan Surat
    Route::post('/distribusi-dokumen/surat/{id}/ubah-tujuan', [SuratController::class, 'ubahTujuan'])->name('surat.edit.tujuan');
    // Penyelesaian Surat
    Route::post('/distribusi-dokumen/surat/{id}/done', [SuratController::class, 'done'])->name('surat.done');
    // Penolakan Surat
    Route::post('/distribusi-dokumen/surat/{id}/reject', [SuratController::class, 'reject'])->name('surat.reject');

    //SERTIFIKAT
    Route::get('/distribusi-dokumen/sertifikat/create', [SertifikatController::class, 'create'])->name('sertif.create');
    Route::post('/distribusi-dokumen/sertifikat', [SertifikatController::class, 'store'])->name('sertif.store');
    Route::get('/distribusi-dokumen/sertifikat/{id}', [SertifikatController::class, 'detail'])->name('sertif.detail');
    Route::get('/distribusi-dokumen/sertifikat/{id}/edit', [SertifikatController::class, 'edit'])->name('sertif.edit');
    Route::put('/distribusi-dokumen/sertifikat/{id}', [SertifikatController::class, 'update'])->name('sertif.update');
    Route::delete('/distribusi-dokumen/sertifikat/{id}', [SertifikatController::class, 'delete'])->name('sertif.delete');

    // Penolakan Pembuatam Sertifikat
    Route::post('/distribusi-dokumen/sertifikat/{id}/reject', [SertifikatController::class, 'reject'])->name('sertif.reject');
    // Persetujuan Admin
    Route::get('/distribusi-dokumen/sertifikat/{id}/acc/admin', [SertifikatController::class, 'accAdmin'])->name('sertif.acc.admin');
    // Persetujuan Kajur
    Route::get('/distribusi-dokumen/sertifikat/{id}/acc/kajur', [SertifikatController::class, 'accKajur'])->name('sertif.acc.kajur');
    // Persetujuan Sign User
    Route::get('/distribusi-dokumen/sertifikat/{id}/acc/sign', [SertifikatController::class, 'sign'])->name('sertif.acc.sign');
    // Pelengakapan Sertifikat
    Route::get('/distribusi-dokumen/sertifikat/{id}/completion', [SertifikatController::class, 'make'])->name('sertif.make');
    Route::post('/distribusi-dokumen/sertifikat/{id}/completion', [SertifikatController::class, 'completion'])->name('sertif.completion');

    Route::middleware(["auth:dosen"])->group(function () {
        // PENGELOLA
        Route::get('/distribusi-dokumen/pengelola', [PengelolaController::class, 'index'])->name('pengelola');
        Route::get('/distribusi-dokumen/pengelola/pengumuman', [PengelolaController::class, 'pengumuman'])->name('pengelola.pengumuman');
        Route::get('/distribusi-dokumen/pengelola/arsip', [PengelolaController::class, 'arsip'])->name('pengelola.arsip');
    });

    Route::middleware(["auth:web"])->group(function () {
        // SEMESTER
        Route::get('/semester', [SemesterController::class, 'index'])->name("semester");
        Route::get('/semester/create', [SemesterController::class, 'create'])->name("semester.create");
        Route::post('/semester', [SemesterController::class, 'store'])->name("semester.store");
        Route::get('/semester/{id}/edit', [SemesterController::class, 'edit'])->name("semester.edit");
        Route::put('/semester/{id}', [SemesterController::class, 'update'])->name("semester.update");
        Route::delete('/semester/{id}', [SemesterController::class, 'delete'])->name("semester.delete");

        //LOGO
        Route::get('/logo', [LogoController::class, 'index'])->name("logo");
        Route::get('/logo/create', [LogoController::class, 'create'])->name("logo.create");
        Route::post('/logo', [LogoController::class, 'store'])->name("logo.store");
        Route::get('/logo/{id}/edit', [LogoController::class, 'edit'])->name("logo.edit");
        Route::put('/logo/{id}', [LogoController::class, 'update'])->name("logo.update");
        Route::delete('/logo/{id}', [LogoController::class, 'delete'])->name("logo.delete");
    });
});

//PUBLIC DETAIL
Route::get('/pengumuman/{encryptId}/public', [PengumumanController::class, 'detailForPublic'])->name('pengumuman.detail.public'); //PENGUMUMAN
Route::prefix('distribusi-dokumen')->group(function () {
    Route::get('/dokumen/{encryptId}/public', [DokumenController::class, 'detailForPublic'])->name('dokumen.detail.public'); //DOKUMEN
    Route::get('/surat/{encryptId}/public', [SuratController::class, 'detailForPublic'])->name('surat.detail.public'); //SURAT
    Route::get('/suratcuti/{encryptId}/public', [SuratCutiController::class, 'detailForPublic'])->name('suratcuti.detail.public'); //SURAT CUTI
});

// PENERIMA SERTIFIKAT
Route::get("/sertifikat/{slug}", [PenerimaSertifikatController::class, 'show'])->name("sertif.penerima");
Route::get("/sertifikat/{slug}/download", [PenerimaSertifikatController::class, 'download'])->name("sertif.download");
