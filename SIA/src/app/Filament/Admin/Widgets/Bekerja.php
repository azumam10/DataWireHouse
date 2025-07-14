<?php

namespace App\Filament\Admin\Widgets;

use App\Models\AlumniBekerja;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class Bekerja extends ChartWidget
{
    protected static ?string $heading = 'Alumni Bekerja (Fakultas - Jurusan - Angkatan)';
    protected static ?string $maxHeight = '400px';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Ambil data alumni bekerja dari model AlumniBekerja
        $data = AlumniBekerja::select(
                'fakultas.nama_fakultas',
                'jurusan.nama_jurusan',
                'angkatan',
                DB::raw('COUNT(*) as total')
            )
            ->join('fakultas', 'alumnis.fakultas_id', '=', 'fakultas.id')
            ->join('jurusan', 'alumnis.jurusan_id', '=', 'jurusan.id')
            ->groupBy('fakultas.nama_fakultas', 'jurusan.nama_jurusan', 'angkatan')
            ->orderBy('angkatan', 'asc') // urutkan berdasarkan angkatan dari kecil ke besar
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
                    'label' => 'Jumlah Alumni Bekerja',
                    'data' => $values,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // bisa diubah ke 'line' atau 'pie' jika perlu
    }
}
