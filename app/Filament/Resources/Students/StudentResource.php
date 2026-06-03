<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\ManageStudents;
use App\Models\Student;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static \UnitEnum|string|null $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Data Siswa';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Siswa Mandiri (User)'),
                \Filament\Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->label('Orang Tua (User)'),
                \Filament\Forms\Components\TextInput::make('full_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Lengkap'),
                \Filament\Forms\Components\TextInput::make('nickname')
                    ->maxLength(255)
                    ->label('Nama Panggilan'),
                \Filament\Forms\Components\TextInput::make('birth_place')
                    ->maxLength(255)
                    ->label('Tempat Lahir'),
                \Filament\Forms\Components\DatePicker::make('birth_date')
                    ->label('Tanggal Lahir'),
                \Filament\Forms\Components\Select::make('gender')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->label('Jenis Kelamin'),
                \Filament\Forms\Components\TextInput::make('birth_order')
                    ->numeric()
                    ->label('Anak Ke-'),
                \Filament\Forms\Components\TextInput::make('sibling_count')
                    ->numeric()
                    ->label('Dari Bersaudara'),
                \Filament\Forms\Components\Textarea::make('address')
                    ->columnSpanFull()
                    ->label('Alamat Lengkap'),
                \Filament\Forms\Components\Textarea::make('medical_history')
                    ->columnSpanFull()
                    ->label('Riwayat Penyakit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('parent.name')
                    ->label('Orang Tua')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Siswa Mandiri')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => $state,
                    }),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime()
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
            'index' => ManageStudents::route('/'),
        ];
    }
}
