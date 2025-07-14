<?php

namespace App\Filament\Admin\Widgets;

use App\Models\AlumniTidakBekerja;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class Belum_Bekerja extends ChartWidget
{
    protected static ?string $heading = 'Alumni Tidak Bekerja (Per Fakultas, Jurusan, Angkatan)';
    protected static ?string $maxHeight = '400px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = AlumniTidakBekerja::select(
                'fakultas.nama_fakultas',
                'jurusan.nama_jurusan',
                'angkatan',
                DB::raw('count(*) as total')
            )
            ->join('fakultas', 'alumnis.fakultas_id', '=', 'fakultas.id')
            ->join('jurusan', 'alumnis.jurusan_id', '=', 'jurusan.id')
            ->groupBy('fakultas.nama_fakultas', 'jurusan.nama_jurusan', 'angkatan')
            ->orderBy('angkatan', 'asc') // Mengurutkan berdasarkan angkatan dari tahun kecil ke besar
            ->get();

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = "{$item->nama_fakultas} - {$item->nama_jurusan} - {$item->angkatan}";
            $values[] = $item->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Alumni Tidak Bekerja',
                    'data' => $values,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)', // Warna berbeda dari yang bekerja
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
