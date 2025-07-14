<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlumniResource\Pages;
use App\Models\Alumni;
use App\Models\Fakultas;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlumniExport;
use App\Imports\AlumniImport;
use Filament\Notifications\Notification;

class AlumniResource extends Resource
{
    protected static ?string $model = Alumni::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Data Alumni';
    protected static ?string $pluralModelLabel = 'Alumni';
    protected static ?string $navigationGroup = 'Alumni';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_lengkap')->label('Nama Lengkap')->required()->maxLength(255),
            Forms\Components\TextInput::make('nim')->label('NIM')->required()->maxLength(255),
            Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(255),
            Forms\Components\TextInput::make('no_hp')->label('Nomor HP')->maxLength(255),
            Forms\Components\Select::make('fakultas_id')
                ->label('Fakultas')
                ->options(Fakultas::all()->pluck('nama_fakultas', 'id'))
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Select::make('jurusan_id')
                ->label('Jurusan')
                ->options(Jurusan::all()->pluck('nama_dengan_id', 'id'))
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('angkatan')->label('Angkatan')->required(),
            Forms\Components\TextInput::make('pekerjaan')->label('Pekerjaan')->maxLength(255),
            Forms\Components\Select::make('status_alumni')
                ->label('Status Alumni')
                ->options([
                    'aktif' => 'Aktif',
                    'tidak_aktif' => 'Tidak Aktif',
                    'meninggal' => 'Meninggal',
                ])
                ->required(),
            Forms\Components\FileUpload::make('foto')
                ->label('Foto Alumni')
                ->image()
                ->disk('public')
                ->directory('alumni-foto')
                ->visibility('public')
                ->imageEditor()
                ->imageResizeMode('cover'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('foto')->label('Foto')->disk('public')->circular()->height(45)->width(45),
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama')->searchable()->sortable()->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('nim')->label('NIM')->searchable()->icon('heroicon-o-identification'),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable()->toggleable()->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('no_hp')->label('No. HP')->toggleable()->icon('heroicon-o-phone'),
                Tables\Columns\BadgeColumn::make('fakultas.nama_fakultas')->label('Fakultas')->color('info'),
                Tables\Columns\BadgeColumn::make('jurusan.nama_jurusan')->label('Jurusan')->color('warning'),
                Tables\Columns\TextColumn::make('angkatan')->label('Angkatan')->sortable(),
                Tables\Columns\TextColumn::make('pekerjaan')->label('Pekerjaan')->searchable(),
                Tables\Columns\BadgeColumn::make('status_alumni')
                    ->label('Status')
                    ->color(fn ($state) => match ($state) {
                        'aktif' => 'success',
                        'tidak_aktif' => 'warning',
                        'meninggal' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        Alumni::query()->select('angkatan')->distinct()->pluck('angkatan', 'angkatan')->toArray()
                    )
                    ->searchable(),
                SelectFilter::make('status_alumni')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif',
                        'meninggal' => 'Meninggal',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ])
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export ke Excel')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('success')
                    ->outlined()
                    ->tooltip('Unduh semua data alumni ke Excel')
                    ->action(fn () => Excel::download(new AlumniExport, 'data_alumni.xlsx')),

                Action::make('import_excel')
                    ->label('Import dari Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->outlined()
                    ->tooltip('Unggah data alumni dari file Excel')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('Pilih File Excel (.xlsx)')
                            ->required()
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->disk('public')
                            ->directory('uploads/temp')
                            ->preserveFilenames(),
                    ])
                    ->action(function (array $data): void {
                        $filePath = storage_path('app/public/' . $data['file']);
                        Excel::import(new AlumniImport, $filePath);

                        Notification::make()
                            ->title('Berhasil Import Data Alumni')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Belum Ada Data Alumni')
            ->emptyStateDescription('Silakan tambahkan data alumni untuk ditampilkan di sini.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlumnis::route('/'),
            'create' => Pages\CreateAlumni::route('/create'),
            'edit' => Pages\EditAlumni::route('/{record}/edit'),
        ];
    }
}
