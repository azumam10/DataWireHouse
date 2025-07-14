<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LokerDistribusiManualResource\Pages;
use App\Models\LokerDistribusiManual;
use App\Models\Alumni;
use App\Models\Loker;
use App\Models\Fakultas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class LokerDistribusiManualResource extends Resource
{
    protected static ?string $model = LokerDistribusiManual::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationGroup = 'Distribusi Informasi';
    protected static ?string $navigationLabel = 'Distribusi Loker Manual';
    protected static ?string $pluralModelLabel = 'Distribusi Loker Manual';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('fakultas_filter')
                ->label('Filter Fakultas')
                ->options(Fakultas::all()->pluck('nama_fakultas', 'id'))
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('alumni_id', null))
                ->columnSpanFull(),

            Forms\Components\Select::make('alumni_id')
                ->label('Pilih Alumni')
                ->options(function (callable $get) {
                    $query = Alumni::query()->whereNull('pekerjaan');

                    if ($get('fakultas_filter')) {
                        $query->where('fakultas_id', $get('fakultas_filter'));
                    }

                    return $query->pluck('nama_lengkap', 'id');
                })
                ->searchable()
                ->required()
                ->preload()
                ->columnSpanFull(),

            Forms\Components\Select::make('loker_id')
                ->label('Pilih Loker')
                ->options(Loker::all()->pluck('judul_loker', 'id'))
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull(),

            Forms\Components\Select::make('status_kirim')
                ->label('Status Pengiriman')
                ->options([
                    'sukses' => 'Sukses',
                    'gagal' => 'Gagal',
                ])
                ->required()
                ->native(false),

            Forms\Components\DateTimePicker::make('waktu_kirim')
                ->label('Waktu Pengiriman')
                ->required()
                ->displayFormat('d M Y, H:i')
                ->timezone('Asia/Jakarta')
                ->seconds(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('alumni.foto')
                ->label('Foto')
                ->circular()
                ->size(40)
                ->disk('public'),

            Tables\Columns\TextColumn::make('alumni.nama_lengkap')
                ->label('Nama Alumni')
                ->icon('heroicon-o-user-circle')
                ->tooltip(fn ($record) => $record->alumni->nama_lengkap)
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('loker.judul_loker')
                ->label('Judul Loker')
                ->icon('heroicon-o-briefcase')
                ->searchable()
                ->sortable()
                ->tooltip(fn ($record) => $record->loker->judul_loker),

            Tables\Columns\TextColumn::make('status_kirim')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'sukses' => 'success',
                    'gagal' => 'danger',
                    default => 'gray',
                })
                ->tooltip(fn ($record) => ucfirst($record->status_kirim)),

            Tables\Columns\TextColumn::make('waktu_kirim')
                ->label('Waktu Kirim')
                ->dateTime('d M Y, H:i')
                ->sortable()
                ->icon('heroicon-o-clock'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diperbarui')
                ->dateTime('d M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('status_kirim')
                ->label('Filter Status')
                ->options([
                    'sukses' => 'Sukses',
                    'gagal' => 'Gagal',
                ]),

            SelectFilter::make('alumni_id')
                ->label('Filter Alumni')
                ->options(Alumni::all()->pluck('nama_lengkap', 'id'))
                ->searchable(),

            SelectFilter::make('loker_id')
                ->label('Filter Loker')
                ->options(Loker::all()->pluck('judul_loker', 'id'))
                ->searchable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make()->label('Edit')->icon('heroicon-o-pencil-square'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih')->icon('heroicon-o-trash'),
            ]),
        ])
        ->emptyStateHeading('Belum Ada Distribusi')
        ->emptyStateDescription('Klik "Tambah Distribusi" untuk mulai menyalurkan lowongan ke alumni.')
        ->emptyStateIcon('heroicon-o-paper-airplane');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLokerDistribusiManuals::route('/'),
            'create' => Pages\CreateLokerDistribusiManual::route('/create'),
            'edit' => Pages\EditLokerDistribusiManual::route('/{record}/edit'),
        ];
    }
}
