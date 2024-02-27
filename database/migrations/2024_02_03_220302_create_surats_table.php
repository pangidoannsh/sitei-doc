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
            $table->string("role_handler")->nullable();
            $table->enum('jenis_user', ['dosen', 'plp', 'admin', "mahasiswa"]);
            $table->integer("prodi_user");
            $table->integer("role_tujuan");
            $table->integer("role_rejected")->nullable();
            $table->string("nama");
            $table->string("keterangan")->nullable();
            $table->string("url_lampiran")->nullable();
            $table->string("url_lampiran_lokal")->nullable();
            $table->string("alasan_ditolak")->nullable();
            $table->string("status");
            $table->string("keterangan_status");
            $table->string("nomor_surat")->nullable();
            $table->string("semester");
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
