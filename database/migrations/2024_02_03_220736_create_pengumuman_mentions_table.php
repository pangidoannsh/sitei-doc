<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengumumanMentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_pengumuman_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengumuman_id');
            $table->string("user_mentioned");
            $table->enum('jenis_user', ['dosen', 'plp', 'admin', 'mahasiswa', 'angkatan']);
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
        Schema::dropIfExists('doc_pengumuman_mentions');
    }
}
