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
            $table->enum("jenis_user", ['dosen', 'admin']);
            $table->string("nama");
            $table->string("jenis");
            $table->string("isi")->nullable();
            $table->date("tanggal");
            $table->boolean("is_done")->default(false);
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
