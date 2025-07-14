<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LokerResource\Pages;
use App\Models\Loker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class LokerResource extends Resource
{
    protected static ?string $model = Loker::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Manajemen Loker';
    protected static ?string $navigationLabel = 'Lowongan Kerja';
    protected static ?string $pluralModelLabel = 'Data Lowongan Kerja';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul_loker')
                ->label('Judul Lowongan')
                ->required()
                ->placeholder('Contoh: Backend Developer Laravel')
                ->maxLength(255),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi Pekerjaan')
                ->rows(6)
                ->required()
                ->placeholder('Tuliskan detail pekerjaan, kualifikasi, dan benefit...'),

            Forms\Components\TextInput::make('perusahaan')
                ->label('Nama Perusahaan')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: PT Teknologi Nusantara'),

            Forms\Components\TextInput::make('lokasi')
                ->label('Lokasi Kerja')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: Jakarta / Remote / Bandung'),

            Forms\Components\Select::make('tipe')
                ->label('Tipe Pekerjaan')
                ->required()
                ->options([
                    'fulltime' => 'Full-time',
                    'parttime' => 'Part-time',
                    'internship' => 'Magang',
                    'freelance' => 'Freelance',
                ])
                ->native(false)
                ->searchable(),

            Forms\Components\DatePicker::make('tanggal_berakhir')
                ->label('Tanggal Berakhir')
                ->required()
                ->minDate(now())
                ->displayFormat('d M Y'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable()
                ->icon('heroicon-o-identification'),

            Tables\Columns\TextColumn::make('judul_loker')
                ->label('Judul')
                ->sortable()
                ->searchable()
                ->icon('heroicon-o-briefcase')
                ->tooltip(fn ($record) => $record->judul_loker),

            Tables\Columns\TextColumn::make('perusahaan')
                ->label('Perusahaan')
                ->sortable()
                ->searchable()
                ->tooltip(fn ($record) => $record->perusahaan),

            Tables\Columns\TextColumn::make('lokasi')
                ->label('Lokasi')
                ->sortable()
                ->searchable()
                ->icon('heroicon-o-map-pin'),

            Tables\Columns\BadgeColumn::make('tipe')
                ->label('Tipe')
                ->colors([
                    'primary' => fn ($state) => $state === 'fulltime',
                    'warning' => fn ($state) => $state === 'parttime',
                    'success' => fn ($state) => $state === 'internship',
                    'danger'  => fn ($state) => $state === 'freelance',
                ])
                ->formatStateUsing(fn ($state) => ucfirst($state)),

            Tables\Columns\TextColumn::make('tanggal_berakhir')
                ->label('Berlaku Hingga')
                ->date('d M Y')
                ->sortable()
                ->icon('heroicon-o-calendar-days'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y, H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diperbarui')
                ->dateTime('d M Y, H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('tipe')
                ->label('Filter Tipe')
                ->options([
                    'fulltime' => 'Full-time',
                    'parttime' => 'Part-time',
                    'internship' => 'Magang',
                    'freelance' => 'Freelance',
                ]),

            SelectFilter::make('lokasi')
                ->label('Filter Lokasi')
                ->options(Loker::query()->distinct()->pluck('lokasi', 'lokasi')->toArray()),
        ])
        ->actions([
            Tables\Actions\ViewAction::make()->label('Detail')->icon('heroicon-o-eye'),
            Tables\Actions\EditAction::make()->label('Edit')->icon('heroicon-o-pencil-square'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih')->icon('heroicon-o-trash'),
            ]),
        ])
        ->emptyStateHeading('Belum Ada Lowongan')
        ->emptyStateDescription('Klik tombol "Tambah" untuk menambahkan lowongan kerja baru.')
        ->emptyStateIcon('heroicon-o-briefcase');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLokers::route('/'),
            'create' => Pages\CreateLoker::route('/create'),
            'edit' => Pages\EditLoker::route('/{record}/edit'),
        ];
    }
}
