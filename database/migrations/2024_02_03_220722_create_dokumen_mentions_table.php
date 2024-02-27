<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenMentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_dokumen_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id');
            $table->string("user_mentioned");
            $table->enum('jenis_user', ['dosen', 'plp', 'admin', 'mahasiswa', 'angkatan']);
            $table->boolean('accepted')->default(false);
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
        Schema::dropIfExists('doc_dokumen_mentions');
    }
}
