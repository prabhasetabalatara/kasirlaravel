<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Kunci utama (ID)
            $table->string('name'); // Kolom untuk nama pengguna
            $table->string('email')->unique(); // Kolom email, harus unik
            $table->timestamp('email_verified_at')->nullable(); // Untuk verifikasi email, boleh kosong
            $table->string('password'); // Kolom untuk password yang sudah di-hash
            $table->rememberToken(); // Kolom untuk fitur "Remember Me"
            $table->timestamps(); // Kolom created_at dan updated_at
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
};