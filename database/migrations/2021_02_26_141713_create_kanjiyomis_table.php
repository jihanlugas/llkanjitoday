<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKanjiyomisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanjiyomis', function (Blueprint $table) {
            $table->id('kanjiyomi_id');
            $table->foreignId('kanji_id');
            $table->string('word')->default('');
            $table->string('type')->default('');
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
        Schema::dropIfExists('kanjiyomis');
    }
}
