<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FakultasResource\Pages;
use App\Models\Fakultas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class FakultasResource extends Resource
{
    protected static ?string $model = Fakultas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Data Fakultas';
    protected static ?string $pluralModelLabel = 'Fakultas';
    protected static ?string $navigationGroup = 'Manajemen Akademik';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_fakultas')
                ->label('Nama Fakultas')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: Fakultas Ilmu Komputer')
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

                Tables\Columns\TextColumn::make('nama_fakultas')
                    ->label('Nama Fakultas')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains(strtolower($state), 'komputer') => 'blue',
                        str_contains(strtolower($state), 'ekonomi') => 'green',
                        str_contains(strtolower($state), 'hukum') => 'red',
                        default => 'gray',
                    })
                    ->tooltip(fn ($record) => "Fakultas: {$record->nama_fakultas}"),

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
                SelectFilter::make('nama_fakultas_awal')
                    ->label('Awalan Nama Fakultas')
                    ->options(
                        collect(range('A', 'Z'))
                            ->mapWithKeys(fn ($char) => [$char => $char])
                            ->toArray()
                    )
                    ->query(function (Builder $query, $data) {
                        return $query->where('nama_fakultas', 'like', "{$data['value']}%");
                    })
                    ->placeholder('Semua Awalan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah')->icon('heroicon-o-pencil-square'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih')->icon('heroicon-o-trash'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Data Fakultas')
            ->emptyStateDescription('Silakan tambahkan data fakultas melalui tombol "Tambah Fakultas" di atas.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFakultas::route('/'),
            'create' => Pages\CreateFakultas::route('/create'),
            'edit' => Pages\EditFakultas::route('/{record}/edit'),
        ];
    }
}
