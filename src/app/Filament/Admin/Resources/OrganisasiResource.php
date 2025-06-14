<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrganisasiResource\Pages;
use App\Filament\Admin\Resources\OrganisasiResource\RelationManagers;
use App\Models\Organisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganisasiResource extends Resource
{
    protected static ?string $model = Organisasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis_organisasi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alumni_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('kegiatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('periode_jabatan')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('status_aktif')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_organisasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alumni_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('periode_jabatan')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status_aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisasis::route('/'),
            'create' => Pages\CreateOrganisasi::route('/create'),
            'edit' => Pages\EditOrganisasi::route('/{record}/edit'),
        ];
    }
}
