<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('email_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('last_login')->nullable();
            $table->string('ip')->nullable();
            $table->string('salt')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropForeign(['role_id']);
        });

        Schema::dropIfExists('users');

    }
}
