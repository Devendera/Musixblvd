<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creatives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('website');
            $table->string('stage');
            $table->string('gender');
            $table->string('type');
            $table->string('pro');
            $table->string('craft');
            $table->string('secondary_craft')->nullable();
            $table->string('genre');
            $table->string('secondary_genre')->nullable();
            $table->string('influencers');
            $table->text('social_media_links')->nullable();
//            $table->text('platforms')->nullable();
            $table->string('status');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creatives');
    }
}
