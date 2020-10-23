<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class UserApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_api', function (Blueprint $table) {
            $table->bigIncrements('id',1000);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('user_level');
            $table->string('email');
            $table->string('password');
            $table->string('user_status');
            $table->timestamps();
        });

        DB::table('user_api')->insert([
            [
                'first_name' => 'Jhon',
                'last_name' => 'Doe',
                'user_level' => 'Admin',
                'email' => 'admin@testapi.com',
                'password' => Hash::make('demoapimarcos'),
                'user_status' => 1,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('user_api');
    }
}
