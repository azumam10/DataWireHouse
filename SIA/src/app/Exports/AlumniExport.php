<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlumniExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Alumni::with(['fakultas', 'jurusan'])
            ->orderBy('fakultas_id')
            ->orderBy('jurusan_id')
            ->orderBy('angkatan')
            ->orderByRaw('pekerjaan IS NULL') // menaruh pekerjaan kosong di akhir
            ->orderBy('pekerjaan')
            ->orderBy('status_alumni')
            ->get();
    }

    public function map($alumni): array
    {
        return [
            $alumni->id,
            $alumni->nama_lengkap,
            $alumni->nim,
            $alumni->email,
            $alumni->no_hp,
            $alumni->fakultas->nama_fakultas ?? '-',
            $alumni->jurusan->nama_jurusan ?? '-',
            $alumni->angkatan,
            $alumni->pekerjaan ?? 'Belum Bekerja',
            $alumni->status_alumni,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'NIM',
            'Email',
            'No HP',
            'Fakultas',
            'Jurusan',
            'Angkatan',
            'Pekerjaan',
            'Status Alumni',
        ];
    }
}
