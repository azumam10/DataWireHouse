<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nim', 20)->unique();
            $table->foreignId('fakultas_id')->constrained('fakultas')->onDelete('cascade');
            $table->year('tahun_lulus');
            $table->string('pekerjaan')->nullable();
            $table->string('email')->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->enum('status_pekerjaan', ['Bekerja', 'Belum Bekerja', 'Wirausaha', 'Melanjutkan Studi'])->default('Belum Bekerja');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};