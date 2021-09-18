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
            $table->id();
            $table->string('name');
            $table->string('first_surname');
            $table->string('last_surname');
            $table->string('nacimiento');
            $table->string('curp')->unique();
            $table->string('rfc')->unique();
            $table->string('state');
            $table->string('street');
            $table->string('betweenStreet');
            $table->string('city');
            $table->string('cp');
            $table->string('genero');
            $table->string('date');
            $table->string('dep');
            $table->string('depa');
            $table->string('cargo');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('boss');
            $table->timestamp('email_verified_at')->nullable();
            $table->text('password');
            $table->string('theme');
            $table->rememberToken();
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('cat_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
