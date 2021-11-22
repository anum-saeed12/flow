<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('list_id')->references('id')->on('listicles')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
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
        Schema::dropIfExists('list_users');
    }
}
