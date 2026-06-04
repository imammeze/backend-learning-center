<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\ManageStudents;
use App\Models\Student;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

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
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Siswa Mandiri (User)'),
                Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->label('Orang Tua (User)'),
                TextInput::make('full_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Lengkap'),
                TextInput::make('nickname')
                    ->maxLength(255)
                    ->label('Nama Panggilan'),
                TextInput::make('birth_place')
                    ->maxLength(255)
                    ->label('Tempat Lahir'),
                DatePicker::make('birth_date')
                    ->label('Tanggal Lahir'),
                Select::make('gender')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->label('Jenis Kelamin'),
                TextInput::make('birth_order')
                    ->numeric()
                    ->label('Anak Ke-'),
                TextInput::make('sibling_count')
                    ->numeric()
                    ->label('Dari Bersaudara'),
                Textarea::make('address')
                    ->columnSpanFull()
                    ->label('Alamat Lengkap'),
                Textarea::make('medical_history')
                    ->columnSpanFull()
                    ->label('Riwayat Penyakit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Nama Panggilan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label('Nama Orang Tua')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->description('Data identitas utama siswa.')
                    ->components([
                        Grid::make(2)
                            ->components([
                                TextEntry::make('full_name')->label('Nama Lengkap'),
                                TextEntry::make('nickname')->label('Nama Panggilan'),
                                TextEntry::make('parent.name')->label('Nama Orang Tua')
                                    ->default('-'),
                                TextEntry::make('user.email')->label('Email Siswa')
                                    ->default('-'),
                                TextEntry::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                        default => '-',
                                    }),
                            ]),
                    ])->columnSpanFull(),
                Section::make('Data Kelahiran & Keluarga')
                    ->components([
                        Grid::make(3)
                            ->components([
                                TextEntry::make('birth_place')->label('Tempat Lahir'),
                                TextEntry::make('birth_date')
                                    ->label('Tanggal Lahir')
                                    ->date('d F Y'),
                                TextEntry::make('birth_order')
                                    ->label('Anak Ke-')
                                    ->formatStateUsing(fn ($record) => $record->birth_order . ' dari ' . $record->sibling_count . ' bersaudara'),
                            ]),
                    ]),
                Section::make('Informasi Tambahan')
                    ->components([
                        TextEntry::make('address')->label('Alamat Lengkap')->columnSpanFull(),
                        TextEntry::make('medical_history')
                            ->label('Riwayat Penyakit')
                            ->columnSpanFull()
                            ->default('Tidak ada riwayat penyakit.'),
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
