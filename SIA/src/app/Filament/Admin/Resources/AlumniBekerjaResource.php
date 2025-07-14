<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlumniBekerjaResource\Pages;
use App\Models\AlumniBekerja;
use App\Exports\AlumniBekerjaExport;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;

class AlumniBekerjaResource extends Resource
{
    protected static ?string $model = AlumniBekerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Data Alumni Bekerja';
    protected static ?string $pluralModelLabel = 'Alumni Bekerja';
    protected static ?string $navigationGroup = 'Alumni';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Tambahkan field edit alumni jika perlu
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->size(45)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->icon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->icon('heroicon-o-identification'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->toggleable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\BadgeColumn::make('fakultas.nama_fakultas')
                    ->label('Fakultas')
                    ->sortable()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('jurusan.nama_jurusan')
                    ->label('Jurusan')
                    ->sortable()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('angkatan')
                    ->label('Angkatan')
                    ->sortable()
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('fakultas_id')
                    ->label('Filter Fakultas')
                    ->relationship('fakultas', 'nama_fakultas')
                    ->searchable(),

                SelectFilter::make('jurusan_id')
                    ->label('Filter Jurusan')
                    ->relationship('jurusan', 'nama_jurusan')
                    ->searchable(),

                SelectFilter::make('angkatan')
                    ->label('Filter Angkatan')
                    ->options(
                        AlumniBekerja::query()->select('angkatan')->distinct()->pluck('angkatan', 'angkatan')->toArray()
                    )
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Ubah'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ])
            ->headerActions([
                Action::make('exportExcel')
                    ->label('Export ke Excel')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->outlined()
                    ->tooltip('Unduh seluruh data alumni bekerja dalam format Excel')
                    ->action(fn () => \Maatwebsite\Excel\Facades\Excel::download(new AlumniBekerjaExport, 'data_alumni_bekerja.xlsx')),
            ])
            ->emptyStateHeading('Belum Ada Data Alumni Bekerja')
            ->emptyStateDescription('Silakan tambahkan atau unggah data alumni yang bekerja untuk ditampilkan di sini.');
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan RelationManagers jika ada
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlumniBekerjas::route('/'),
        ];
    }
}
