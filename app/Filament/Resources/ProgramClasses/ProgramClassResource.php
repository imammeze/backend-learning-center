<?php

namespace App\Filament\Resources\ProgramClasses;

use App\Filament\Resources\ProgramClasses\Pages\ManageProgramClasses;
use App\Models\ProgramClass;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class ProgramClassResource extends Resource
{
    protected static ?string $model = ProgramClass::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static \UnitEnum|string|null $navigationGroup = 'Pusat Pembelajaran';

    protected static ?string $navigationLabel = 'Kelas Program';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('program_id')
                    ->relationship('program', 'name')
                    ->required()
                    ->label('Program'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kelas'),
                Forms\Components\TextInput::make('min_age')
                    ->numeric()
                    ->label('Usia Minimal (Tahun)'),
                Forms\Components\TextInput::make('max_age')
                    ->numeric()
                    ->label('Usia Maksimal (Tahun)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('min_age')
                    ->label('Usia Min')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_age')
                    ->label('Usia Max')
                    ->sortable(),
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
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProgramClasses::route('/'),
        ];
    }
}
