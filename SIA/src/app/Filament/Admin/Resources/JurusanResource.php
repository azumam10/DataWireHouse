<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\JurusanResource\Pages;
use App\Models\Jurusan;
use App\Models\Fakultas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class JurusanResource extends Resource
{
    protected static ?string $model = Jurusan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Data Jurusan';
    protected static ?string $pluralModelLabel = 'Jurusan';
    protected static ?string $navigationGroup = 'Manajemen Akademik';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('fakultas_id')
                ->label('Fakultas')
                ->options(Fakultas::all()->pluck('nama_fakultas', 'id'))
                ->searchable()
                ->required()
                ->preload()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('nama_jurusan')
                ->label('Nama Jurusan')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: Teknik Informatika')
                ->columnSpanFull()
                ->autofocus(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('nama_jurusan')
                    ->label('Nama Jurusan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains(strtolower($state), 'informatika') => 'blue',
                        str_contains(strtolower($state), 'ekonomi') => 'green',
                        str_contains(strtolower($state), 'hukum') => 'red',
                        default => 'gray',
                    })
                    ->tooltip(fn ($record) => "Jurusan: {$record->nama_jurusan}"),

                Tables\Columns\TextColumn::make('fakultas.nama_fakultas')
                    ->label('Fakultas')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-building-library')
                    ->color('success')
                    ->tooltip(fn ($record) => "Fakultas: {$record->fakultas->nama_fakultas}"),

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
                SelectFilter::make('fakultas_id')
                    ->label('Filter Fakultas')
                    ->options(Fakultas::all()->pluck('nama_fakultas', 'id'))
                    ->searchable()
                    ->placeholder('Semua Fakultas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit')->icon('heroicon-o-pencil-square'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih')->icon('heroicon-o-trash'),
                ]),
            ])
            ->emptyStateHeading('Data Jurusan Belum Tersedia')
            ->emptyStateDescription('Klik tombol "Tambah Jurusan" untuk mulai menambahkan data baru.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurusans::route('/'),
            'create' => Pages\CreateJurusan::route('/create'),
            'edit' => Pages\EditJurusan::route('/{record}/edit'),
        ];
    }
}
