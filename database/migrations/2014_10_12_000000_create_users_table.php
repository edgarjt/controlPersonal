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
            $table->string('email')->unique();
            $table->string('rfc')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('password');
            $table->string('theme');
            $table->rememberToken();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('work_id')->nullable();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('cat_roles');
            $table->foreign('work_id')->references('id')->on('workPosition');
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
