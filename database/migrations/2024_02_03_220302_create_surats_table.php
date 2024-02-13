<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_surat', function (Blueprint $table) {
            $table->id();
            $table->string("user_created");
            $table->string("user_handler")->nullable();
            $table->enum('jenis_user', ['dosen', 'plp', 'admin', "mahasiswa"]);
            $table->string("nama");
            $table->string("keterangan")->nullable();
            $table->string("url_lampiran")->nullable();
            $table->string("alasan_ditolak")->nullable();
            $table->boolean("is_local_file");
            $table->enum("status", ["proses", "ditolak", "diterima"])->default("proses");
            $table->string("keterangan_status");
            $table->string("nomor_surat")->nullable();
            $table->string("url_surat_jadi")->nullable();
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
        Schema::dropIfExists('doc_surat');
    }
}
