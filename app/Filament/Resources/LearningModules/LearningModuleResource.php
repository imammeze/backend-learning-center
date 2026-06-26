<?php

namespace App\Filament\Resources\LearningModules;

use App\Filament\Resources\LearningModules\Pages\ManageLearningModules;
use App\Models\LearningModule;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class LearningModuleResource extends Resource
{
    protected static ?string $model = LearningModule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static \UnitEnum|string|null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Modul Pembelajaran';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Hidden::make('teacher_id')
                    ->default(fn () => Auth::id()),
                Forms\Components\Select::make('program_id')
                    ->relationship('program', 'name')
                    ->required()
                    ->label('Program')
                    ->live(),
                Forms\Components\Select::make('program_class_id')
                    ->relationship('programClass', 'name', fn ($query, $get) => $query->where('program_id', $get('program_id')))
                    ->label('Kelas (Opsional)')
                    ->helperText('Pilih kelas jika modul ini spesifik untuk kelas tertentu.'),
                Forms\Components\TextInput::make('meeting_number')
                    ->numeric()
                    ->required()
                    ->label('Pertemuan Ke-'),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Judul Modul'),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('file_path')
                    ->label('File Materi')
                    ->disk('public')
                    ->directory('learning-modules')
                    ->acceptedFileTypes(['application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])
                    ->maxSize(10240) // 10MB
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('youtube_url')
                    ->url()
                    ->maxLength(255)
                    ->label('Tautan Video (YouTube)')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program')
                    ->sortable(),
                Tables\Columns\TextColumn::make('programClass.name')
                    ->label('Kelas')
                    ->sortable()
                    ->placeholder('Semua Kelas'),
                Tables\Columns\TextColumn::make('meeting_number')
                    ->label('Pertemuan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Pengunggah')
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
            'index' => ManageLearningModules::route('/'),
        ];
    }
}
