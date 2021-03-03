<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKanjionyomiexamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanjionyomiexamples', function (Blueprint $table) {
            $table->id('kanjionyomiexample_id');
            $table->foreignId('kanjionyomi_id');
            $table->string('word')->default('');
            $table->string('mean')->default('');
            $table->text('description');
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
        Schema::dropIfExists('kanjionyomiexamples');
    }
}
