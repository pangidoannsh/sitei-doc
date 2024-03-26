<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSertifikatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_sertifikat', function (Blueprint $table) {
            $table->id();
            $table->string("user_created");
            $table->enum("jenis_user", ['dosen', 'admin', "plp"]);
            $table->string("sign_by");
            $table->string("signer_role");
            $table->string("rejected_by")->nullable();
            $table->string("nama");
            $table->string("isi")->nullable();
            $table->string("alasan_ditolak")->nullable();
            $table->date("tanggal");
            $table->string("status")->default("staf_jurusan");
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
        Schema::dropIfExists('doc_sertifikat');
    }
}
