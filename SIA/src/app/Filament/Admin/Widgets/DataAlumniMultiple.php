<?php

namespace App\Filament\Widgets;

use App\Models\Alumni;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DataAlumniMultiple extends ChartWidget
{
    protected static ?string $heading = 'Analisis Data Alumni';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public ?string $filter = 'fakultas';

    protected function getFilters(): ?array
    {
        return [
            'fakultas' => 'Per Fakultas',
            'jurusan' => 'Per Jurusan',
            'angkatan' => 'Per Angkatan',
            'status' => 'Per Status Alumni'
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter;
        
        switch ($filter) {
            case 'fakultas':
                return $this->getDataPerFakultas();
            case 'jurusan':
                return $this->getDataPerJurusan();
            case 'angkatan':
                return $this->getDataPerAngkatan();
            case 'status':
                return $this->getDataPerStatus();
            default:
                return $this->getDataPerFakultas();
        }
    }

    private function getDataPerFakultas(): array
    {
        $data = Alumni::select('fakultas_id', DB::raw('count(*) as total'))
            ->with('fakultas:id,nama_fakultas')
            ->groupBy('fakultas_id')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Alumni',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                    ],
                    'borderWidth' => 0,
                ]
            ],
            'labels' => $data->map(fn($item) => $item->fakultas->nama_fakultas ?? 'Unknown')->toArray(),
        ];
    }

    private function getDataPerJurusan(): array
    {
        $data = Alumni::select('jurusan_id', DB::raw('count(*) as total'))
            ->with('jurusan:id,nama_jurusan')
            ->groupBy('jurusan_id')
            ->orderBy('total', 'desc')
            ->limit(8)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Alumni',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                    ],
                    'borderColor' => '#fff',
                    'borderWidth' => 2,
                ]
            ],
            'labels' => $data->map(fn($item) => 
                substr($item->jurusan->nama_jurusan ?? 'Unknown', 0, 15) . 
                (strlen($item->jurusan->nama_jurusan ?? '') > 15 ? '...' : '')
            )->toArray(),
        ];
    }

    private function getDataPerAngkatan(): array
    {
        $data = Alumni::select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->orderBy('angkatan', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Alumni',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                ]
            ],
            'labels' => $data->pluck('angkatan')->map(fn($angkatan) => "Angkatan {$angkatan}")->toArray(),
        ];
    }

    private function getDataPerStatus(): array
    {
        $data = Alumni::select('status_alumni', DB::raw('count(*) as total'))
            ->groupBy('status_alumni')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Alumni',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1'
                    ],
                    'borderWidth' => 0,
                ]
            ],
            'labels' => $data->pluck('status_alumni')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return match($this->filter) {
            'angkatan' => 'line',
            'status' => 'pie',
            default => 'bar'
        };
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => in_array($this->filter, ['status', 'fakultas']),
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0,0,0,0.8)',
                    'titleColor' => '#fff',
                    'bodyColor' => '#fff',
                ]
            ],
            'scales' => $this->getScalesConfig(),
            'animation' => [
                'duration' => 1500,
                'easing' => 'easeOutQuart'
            ]
        ];
    }

    private function getScalesConfig(): array
    {
        if (in_array($this->filter, ['status'])) {
            return [];
        }

        return [
            'y' => [
                'beginAtZero' => true,
                'grid' => [
                    'color' => 'rgba(0,0,0,0.1)'
                ],
                'ticks' => [
                    'font' => [
                        'size' => 12
                    ]
                ]
            ],
            'x' => [
                'grid' => [
                    'display' => false
                ],
                'ticks' => [
                    'font' => [
                        'size' => 12
                    ],
                    'maxRotation' => 45
                ]
            ]
        ];
    }
}