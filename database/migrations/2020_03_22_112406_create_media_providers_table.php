<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider_key')->unique();
            $table->string('provider_type');
            $table->string('img')->nullable();
            $table->string('username')->nullable();
            $table->string('followers')->nullable();
            $table->string('songs')->nullable();
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
        Schema::dropIfExists('media_providers');
    }
}
