<?php
namespace App\Imports;

use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlumniImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $fakultas = Fakultas::where('nama_fakultas', $row['fakultas_id'])->first();
        $jurusan = Jurusan::where('nama_jurusan', $row['jurusan_id'])->first();

        return new Alumni([
            'nama_lengkap'   => $row['nama_lengkap'],
            'nim'            => $row['nim'],
            'email'          => $row['email'],
            'no_hp'          => $row['no_hp'],
            'fakultas_id'    => $fakultas?->id,
            'jurusan_id'     => $jurusan?->id,
            'angkatan'       => $row['angkatan'],
            'pekerjaan'      => $row['pekerjaan'],
            'status_alumni'  => $row['status_alumni'],
        ]);
    }
}
