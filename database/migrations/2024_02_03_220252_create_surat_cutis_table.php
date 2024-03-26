<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratCutisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_surat_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('user_created');
            $table->enum('jenis_user', ['dosen', 'plp', 'admin', 'mahasiswa']);
            $table->enum('status', ['staf_jurusan', 'proses', 'diterima', 'ditolak'])->default('staf_jurusan');
            $table->integer('lama_cuti');
            $table->enum('jenis_cuti', ['tahunan', 'besar', 'sakit', 'melahirkan', 'kepentingan', 'diluar tanggungan negara'])->default('tahunan');
            $table->date("mulai_cuti");
            $table->date("selesai_cuti");
            $table->string('alamat_cuti');
            $table->string('alasan_cuti');
            $table->string('nomor_telepon', 15);
            $table->string('tanda_tangan');
            $table->string('alasan_ditolak')->nullable();
            $table->string('role_rejected')->nullable();
            $table->string('url_lampiran')->nullable();
            $table->string('url_lampiran_lokal')->nullable();
            $table->string('nama_penandatangan_akhir')->nullable();
            $table->string('jabatan_penandatangan_akhir')->nullable();
            $table->string('nip_penandatangan_akhir')->nullable();
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
        Schema::dropIfExists('doc_surat_cuti');
    }
}
