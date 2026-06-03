<?php

namespace App\Filament\Resources\OrangTuas;

use App\Filament\Resources\OrangTuas\Pages\ManageOrangTuas;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrangTuaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'orang-tua';

    protected static ?string $navigationLabel = 'Data Orang Tua';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static \UnitEnum|string|null $navigationGroup = 'Data Master';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->role('orang_tua');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Nama Orang Tua')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('whatsapp_number')
                    ->label('No. WhatsApp')
                    ->maxLength(20),
                \Filament\Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Nama Orang Tua')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('whatsapp_number')
                    ->label('No. WhatsApp')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('students_count')
                    ->label('Jumlah Anak')
                    ->counts('students')
                    ->badge()
                    ->color('success'),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrangTuas::route('/'),
        ];
    }
}
