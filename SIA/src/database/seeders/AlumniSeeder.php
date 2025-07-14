<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlumniSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        $namaDepan = ['Ahmad', 'Dina', 'Budi', 'Siti', 'Rizky', 'Lina', 'Fajar', 'Rani', 'Hendra', 'Putri'];
        $namaBelakang = ['Saputra', 'Permata', 'Wijaya', 'Anjani', 'Kurniawan', 'Sari', 'Santoso', 'Maulana', 'Utami', 'Rahmawati'];
        $pekerjaanList = ['Software Engineer', 'Data Analyst', 'Product Manager', 'UI/UX Designer', 'Network Engineer', 'Web Developer', 'IT Support', 'Mobile Developer'];
        $statusList = ['aktif', 'tidak_aktif', 'meninggal'];

        $alumni = [];

        for ($i = 1; $i <= 30; $i++) {
            $firstName = $namaDepan[array_rand($namaDepan)];
            $lastName = $namaBelakang[array_rand($namaBelakang)];
            $fullName = "$firstName $lastName";

            $fakultas_id = rand(1, 3); // Asumsi Anda memiliki 3 fakultas (misalnya Fasilkom, FEB, FT)
            $jurusan_id = match($fakultas_id) {
                1 => rand(1, 2), // Asumsi Fasilkom memiliki Jurusan ID 1 (TI) dan 2 (SI)
                2 => 3,           // Asumsi FEB memiliki Jurusan ID 3 (Manajemen)
                3 => 4,           // Asumsi FT memiliki Jurusan ID 4 (Teknik Elektro)
                default => 1,     // Fallback jika ID fakultas tidak sesuai
            };

            $nimPrefix = match($jurusan_id) {
                1 => 'TI',
                2 => 'SI',
                3 => 'MNJ',
                4 => 'ELK',
                default => 'GEN', // Fallback untuk prefix NIM
            };

            $angkatan = rand(2018, 2023);
            // Pastikan NIM unik dengan menggabungkan prefix, angkatan, dan counter unik per seeder run
            $nim = $nimPrefix . $angkatan . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Sekitar 25% alumni tidak bekerja (pekerjaan null)
            $pekerjaan = rand(1, 4) === 1 ? null : $pekerjaanList[array_rand($pekerjaanList)];

            $alumni[] = [
                'nama_lengkap' => $fullName,
                'nim' => $nim,
                'email' => strtolower(Str::slug($fullName, '.')) . $i . '@email.com', // Email unik untuk setiap entri
                'no_hp' => '08' . rand(100000000, 999999999), // Memastikan 10 digit setelah '08'
                'fakultas_id' => $fakultas_id,
                'jurusan_id' => $jurusan_id,
                'angkatan' => $angkatan,
                'pekerjaan' => $pekerjaan,
                'status_alumni' => $statusList[array_rand($statusList)],
                'foto' => null, // Asumsi kolom 'foto' bisa null
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gunakan upsert untuk mencegah error duplikasi entri pada kolom 'nim'
        // Jika ada record dengan 'nim' yang sama, ia akan memperbarui kolom yang ditentukan.
        // Jika tidak, ia akan memasukkan record baru.
        DB::table('alumnis')->upsert(
            $alumni,
            ['nim'], // Kolom yang digunakan untuk memeriksa keunikan (primary key atau unique index)
            [ // Kolom yang akan diperbarui jika record yang cocok ditemukan
                'nama_lengkap', 'email', 'no_hp', 'fakultas_id', 'jurusan_id',
                'angkatan', 'pekerjaan', 'status_alumni', 'foto', 'updated_at'
            ]
        );
    }
}