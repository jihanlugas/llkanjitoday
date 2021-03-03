<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
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
            $table->id('user_id');
            $table->string('email', 255)->unique();
            $table->string('name', 255)->default('');
            $table->string('password', 255);
            $table->foreignId('role_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        $user = new User();
        $user->email = 'jihanlugas2@gmail.com';
        $user->name = "Jihan Lugas";
        $user->password = Hash::make('123456');
        $user->role_id = 1;
        $user->save();
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
