<?php

namespace App\Exports;

use App\Models\AlumniBekerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlumniBekerjaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return AlumniBekerja::with(['fakultas', 'jurusan'])
            ->orderBy('fakultas_id')
            ->orderBy('jurusan_id')
            ->orderBy('angkatan')
            ->get();
    }

    public function map($alumni): array
    {
        return [
            $alumni->nama_lengkap,
            $alumni->nim,
            $alumni->email,
            $alumni->no_hp,
            $alumni->fakultas->nama_fakultas ?? '-',
            $alumni->jurusan->nama_jurusan ?? '-',
            $alumni->angkatan,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'NIM',
            'Email',
            'No HP',
            'Fakultas',
            'Jurusan',
            'Angkatan',
        ];
    }
}

