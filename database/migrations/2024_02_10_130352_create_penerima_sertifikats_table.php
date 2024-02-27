<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaSertifikatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_penerima_sertifikat', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug')->default(DB::raw("UUID()"));
            $table->foreignId("sertifikat_id");
            $table->string("nomor_sertif")->nullable();
            $table->string("user_penerima")->nullable();
            $table->enum("jenis_penerima", ['dosen', 'staf', 'mahasiswa', 'lainnya'])->default('lainnya');
            $table->string("nama_penerima")->nullable();
            $table->string("nama_display")->nullable();
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
        Schema::dropIfExists('doc_penerima_sertifikat');
    }
}
