<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nim')->unique();
            $table->string('email')->unique();
            $table->string('no_hp')->nullable();
            $table->foreignId('fakultas_id')->constrained('fakultas');
            $table->foreignId('jurusan_id')->constrained('jurusan');
            $table->year('angkatan');
            $table->string('pekerjaan')->nullable();
            $table->enum('status_alumni', ['aktif', 'tidak_aktif', 'meninggal']);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnis');
    }
};
