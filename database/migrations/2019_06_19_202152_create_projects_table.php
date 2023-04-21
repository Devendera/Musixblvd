<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('img');
            $table->string('release_date');
            $table->string('audio');
            $table->text('apple_music')->nullable();
            $table->text('spotify')->nullable();
            $table->text('pandora')->nullable();
            $table->text('google')->nullable();
            $table->text('amazon')->nullable();
            $table->text('deezer')->nullable();
            $table->text('tidal')->nullable();
            $table->text('rhapsody')->nullable();
            $table->text('youtube')->nullable();
            $table->text('xbox_music')->nullable();
            $table->text('sound_cloud')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
