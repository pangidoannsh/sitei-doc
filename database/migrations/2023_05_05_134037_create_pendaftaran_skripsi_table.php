<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftaranSkripsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftaran_skripsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_nim');
            $table->string('mahasiswa_nama');
            $table->foreignId('prodi_id');
            $table->foreignId('konsentrasi_id');
             //usul judul
             $table->string('judul_skripsi');  
             $table->string('krs_berjalan');  
             $table->string('khs');  
             $table->string('transkip_nilai'); 
             $table->string('pembimbing_1_nip');
             $table->string('pembimbing_2_nip')->nullable();
             $table->string('tgl_created_usuljudul')->nullable();
             $table->string('tgl_disetujui_usuljudul')->nullable();
             // daftar sempro
             $table->string('krs_berjalan_sempro')->nullable();  
             $table->string('khs_kpti_10')->nullable();  
             $table->string('sti_3')->nullable();  
             $table->string('logbook')->nullable();  
             $table->string('proposal')->nullable();  
             $table->string('sti_30')->nullable();  
             $table->string('sti_31_sempro')->nullable(); 
             $table->string('tgl_created_sempro')->nullable();
             $table->string('tgl_disetujui_sempro')->nullable();
             //jadwal sempro
             $table->string('tgl_disetujui_jadwalsempro')->nullable();
             //sempro selesai
             $table->string('tgl_semproselesai')->nullable();
             //perpanjangan skripsi 1
             $table->string('sti_22_p1')->nullable(); 
             $table->string('tgl_created_perpanjangan1')->nullable();
             $table->string('tgl_disetujui_perpanjangan1')->nullable();
             //perpanjangan skripsi 2
             $table->string('sti_22_p2')->nullable(); 
             $table->string('tgl_created_perpanjangan2')->nullable();
             $table->string('tgl_disetujui_perpanjangan2')->nullable();
             //daftar sidang
             $table->string('skor_turnitin')->nullable(); 
             $table->string('resume_turnitin')->nullable(); 
             $table->string('sti_9')->nullable(); 
             $table->string('sti_11')->nullable(); 
             $table->string('naskah_skripsi')->nullable(); 
             $table->string('dokumen_kelengkapan')->nullable(); 
             $table->string('pasang_poster')->nullable(); 
             $table->string('sti_10')->nullable(); 
             $table->string('url_poster')->nullable(); 
             $table->string('sti_30_skripsi')->nullable(); 
             $table->string('sti_31_skripsi')->nullable(); 
             $table->string('tgl_created_sidang')->nullable();
             $table->string('tgl_disetujui_sidang')->nullable();
             //STI-17 / PENYERAHAN BUKU SKRIPSI
             $table->string('sti_17')->nullable();
             $table->string('sti_29')->nullable();
             $table->string('buku_skripsi_akhir')->nullable();
             $table->string('tgl_created_sti_17')->nullable();
             $table->string('tgl_disetujui_sti_17')->nullable();
             //perpanjangan revisi
             $table->string('sti_23')->nullable();
             $table->string('tgl_created_revisi')->nullable();
             $table->string('tgl_disetujui_revisi')->nullable();
             //Alasan ditolak
            $table->string('alasan')->nullable();
             
             $table->string('jenis_usulan')->default('Usulan Judul Skripsi');
             $table->string('status_skripsi')->default('USULAN JUDUL');
             $table->string('keterangan')->default('Menunggu persetujuan Pembimbing 1');
            //  $table->string('persetujuan')->default('Menunggu persetujuan Koordinator Skripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendaftaran_skripsi');
    }
}