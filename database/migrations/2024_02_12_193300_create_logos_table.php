<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_logo', function (Blueprint $table) {
            $table->id();
            $table->string("nama", 20);
            $table->string("url");
            $table->enum("position", ['kiri', 'kanan'])->default('kiri');
            $table->boolean("is_mandatory")->default(false);
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
        Schema::dropIfExists('doc_logo');
    }
}
