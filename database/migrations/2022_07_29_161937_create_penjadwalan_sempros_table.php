<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjadwalanSemprosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjadwalan_sempro', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa_nim');
            $table->string('pembimbingsatu_nip');
            $table->string('pembimbingdua_nip')->nullable();
            $table->string('pengujisatu_nip')->nullable();
            $table->string('pengujidua_nip')->nullable();
            $table->string('pengujitiga_nip')->nullable();
            $table->foreignId('prodi_id');          
            $table->string('jenis_seminar')->default('Proposal');
            $table->string('judul_proposal');
            $table->string('revisi_proposal')->nullable();
            $table->string('catatan1')->nullable();
            $table->string('catatan2')->nullable();
            $table->string('catatan3')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('waktu')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('status_seminar')->default(0);
            $table->string('dibuat_oleh')->nullable();
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
        Schema::dropIfExists('penjadwalan_sempros');
    }
}
