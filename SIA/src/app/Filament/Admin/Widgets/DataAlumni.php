<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Alumni;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DataAlumni extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Alumni';

    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        if ($this->filter === 'fakultas') {
            // Grafik Alumni per Fakultas
            $data = Alumni::select('fakultas_id', DB::raw('count(*) as total'))
                ->with('fakultas:id,nama_fakultas')
                ->groupBy('fakultas_id')
                ->get();

            $labels = $data->map(fn($item) => $item->fakultas->nama_fakultas ?? 'Unknown')->toArray();
            $values = $data->pluck('total')->toArray();
        } else {
            // Grafik Alumni per Jurusan
            $data = Alumni::select('jurusan_id', DB::raw('count(*) as total'))
                ->with('jurusan:id,nama_jurusan')
                ->groupBy('jurusan_id')
                ->get();

            $labels = $data->map(fn($item) => $item->jurusan->nama_jurusan ?? 'Unknown')->toArray();
            $values = $data->pluck('total')->toArray();
        }

        // Warna acak terbatas
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#C9CBCF', '#B2FF66',
            '#FF6FA0', '#33CCFF', '#FFDB58', '#ADFF2F',
            '#FF884D', '#8B008B', '#20B2AA', '#DA70D6',
            '#87CEFA', '#FFA07A', '#BA55D3', '#3CB371'
        ];

        return [
            'datasets' => [
                [
                    'label' => $this->filter === 'fakultas'
                        ? 'Total Alumni per Fakultas'
                        : 'Total Alumni per Jurusan',
                    'data' => $values,
                    'backgroundColor' => array_slice($colors, 0, count($values)),
                    'borderColor' => array_slice($colors, 0, count($values)),
                    'borderWidth' => 1,
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'font' => [
                            'size' => 12,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => \Illuminate\Support\Js::from('function(context) {
                            return context.label + ": " + context.raw + " Alumni";
                        }'),
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Alumni',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'autoSkip' => false,
                        'maxRotation' => 60,
                        'minRotation' => 20,
                    ],
                ],
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'fakultas' => 'Per Fakultas',
            'jurusan' => 'Per Jurusan',
        ];
    }
}
