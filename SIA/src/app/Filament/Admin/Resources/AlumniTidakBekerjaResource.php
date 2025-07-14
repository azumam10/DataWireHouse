<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlumniTidakBekerjaResource\Pages;
use App\Models\AlumniTidakBekerja;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use App\Exports\AlumniTidakBekerjaExport;
use Maatwebsite\Excel\Facades\Excel;

class AlumniTidakBekerjaResource extends Resource
{
    protected static ?string $model = AlumniTidakBekerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-minus';
    protected static ?string $navigationLabel = 'Alumni Tidak Bekerja';
    protected static ?string $pluralModelLabel = 'Alumni Tidak Bekerja';
    protected static ?string $navigationGroup = 'Manajemen Alumni';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Kosong karena read-only
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->height(50)
                    ->width(50),

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->toggleable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->toggleable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\BadgeColumn::make('fakultas.nama_fakultas')
                    ->label('Fakultas')
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('jurusan.nama_jurusan')
                    ->label('Jurusan')
                    ->color('warning'),

                Tables\Columns\TextColumn::make('angkatan')
                    ->label('Angkatan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('fakultas_id')
                    ->label('Fakultas')
                    ->relationship('fakultas', 'nama_fakultas')
                    ->searchable(),

                SelectFilter::make('jurusan_id')
                    ->label('Jurusan')
                    ->relationship('jurusan', 'nama_jurusan')
                    ->searchable(),

                SelectFilter::make('angkatan')
                    ->label('Angkatan')
                    ->options(
                        AlumniTidakBekerja::query()
                            ->select('angkatan')
                            ->distinct()
                            ->pluck('angkatan', 'angkatan')
                            ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ])
            ->headerActions([
                Action::make('exportExcel')
                    ->label('Export ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->outlined()
                    ->tooltip('Unduh data alumni tidak bekerja')
                    ->action(fn () => Excel::download(new AlumniTidakBekerjaExport, 'alumni_tidak_bekerja.xlsx')),
            ])
            ->emptyStateHeading('Belum Ada Data Alumni')
            ->emptyStateDescription('Semua alumni dalam data ini sedang bekerja atau belum didata.');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlumniTidakBekerjas::route('/'),
        ];
    }
}
