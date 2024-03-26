<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('user_created');
            $table->enum('jenis_user', ['dosen', 'plp', 'admin']);
            $table->string('nama');
            $table->string('nomor_dokumen')->nullable();
            $table->string('keterangan')->nullable();
            $table->date('tgl_dokumen');
            $table->string('kategori', 20)->default('lainnya');
            $table->string('semester',);
            $table->string('url_dokumen')->nullable();
            $table->string('url_dokumen_lokal')->nullable();
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
        Schema::dropIfExists('doc_dokumen');
    }
}
