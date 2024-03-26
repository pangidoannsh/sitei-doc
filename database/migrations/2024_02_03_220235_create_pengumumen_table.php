<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengumumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('user_created');
            $table->enum('jenis_user', ['dosen', 'admin', 'plp']);
            $table->string('nama');
            $table->string('nomor_pengumuman')->nullable();
            $table->string('isi');
            $table->date('tgl_batas_pengumuman');
            $table->string('kategori', 20);
            $table->string('semester', 25);
            $table->string('url_dokumen')->nullable();
            $table->string('url_dokumen_lokal')->nullable();
            $table->boolean('for_all_dosen')->nullable();
            $table->boolean('for_all_staf')->nullable();
            $table->boolean('for_all_mahasiswa')->nullable();
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
        Schema::dropIfExists('doc_pengumuman');
    }
}
