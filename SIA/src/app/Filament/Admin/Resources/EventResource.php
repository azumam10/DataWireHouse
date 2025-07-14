<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventResource\Pages;
use App\Models\Event;
use App\Models\Fakultas;
use App\Models\Jurusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Colors\Color;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Event Kampus';
    protected static ?string $pluralModelLabel = 'Event';
    protected static ?string $navigationGroup = 'Manajemen Alumni';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul_event')
                ->label('Judul Event')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: Seminar Karir Alumni'),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi Lengkap')
                ->rows(5)
                ->required()
                ->placeholder('Isi deskripsi kegiatan secara detail...'),

            Forms\Components\DatePicker::make('tanggal_mulai')
                ->label('Tanggal Mulai')
                ->required(),

            Forms\Components\DatePicker::make('tanggal_selesai')
                ->label('Tanggal Selesai')
                ->required(),

            Forms\Components\TextInput::make('lokasi')
                ->label('Lokasi Event')
                ->required()
                ->placeholder('Contoh: Aula Lt. 3 Gedung Rektorat'),

            Forms\Components\Select::make('target_fakultas_id')
                ->label('Target Fakultas')
                ->options(Fakultas::all()->pluck('nama_fakultas', 'id'))
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('target_jurusan_id')
                ->label('Target Jurusan')
                ->options(Jurusan::all()->pluck('nama_dengan_id', 'id'))
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('target_angkatan')
                ->label('Target Angkatan')
                ->numeric()
                ->placeholder('Contoh: 2021')
                ->minValue(2000)
                ->maxValue(date('Y') + 1),

            Forms\Components\FileUpload::make('foto_event')
                ->label('Foto Event')
                ->image()
                ->imageEditor()
                ->disk('public')
                ->directory('event-foto')
                ->visibility('public'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_event')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->tooltip('Foto kegiatan')
                    ->height(45)
                    ->width(45),

                Tables\Columns\TextColumn::make('judul_event')
                    ->label('Judul')
                    ->limit(35)
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->judul_event),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->limit(25)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->lokasi),

                Tables\Columns\BadgeColumn::make('targetFakultas.nama_fakultas')
                    ->label('Fakultas')
                    ->colors([
                        'blue',
                        'cyan' => static fn ($state) => str_contains(strtolower($state), 'teknik'),
                        'green' => static fn ($state) => str_contains(strtolower($state), 'ekonomi'),
                        'red' => static fn ($state) => str_contains(strtolower($state), 'hukum'),
                    ]),

                Tables\Columns\BadgeColumn::make('targetJurusan.nama_jurusan')
                    ->label('Jurusan')
                    ->color('warning'),

                Tables\Columns\TextColumn::make('target_angkatan')
                    ->label('Angkatan')
                    ->sortable(),

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
                SelectFilter::make('target_fakultas_id')
                    ->label('Filter Fakultas')
                    ->relationship('targetFakultas', 'nama_fakultas')
                    ->searchable()
                    ->placeholder('Semua Fakultas'),

                SelectFilter::make('target_jurusan_id')
                    ->label('Filter Jurusan')
                    ->relationship('targetJurusan', 'nama_jurusan')
                    ->searchable()
                    ->placeholder('Semua Jurusan'),

                SelectFilter::make('target_angkatan')
                    ->label('Filter Angkatan')
                    ->options(
                        Event::query()->select('target_angkatan')->distinct()->pluck('target_angkatan', 'target_angkatan')->filter()->toArray()
                    )
                    ->placeholder('Semua Angkatan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit')->icon('heroicon-m-pencil-square'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih')->icon('heroicon-o-trash'),
                ]),
            ])
            ->emptyStateHeading('Tidak Ada Event')
            ->emptyStateDescription('Belum ada data event yang dimasukkan. Silakan klik tombol "Create Event".');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
