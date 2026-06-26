<?php

namespace App\Filament\Resources\Registrations;

use App\Filament\Resources\Registrations\Pages\ManageRegistrations;
use App\Models\ProgramClass;
use App\Models\Registration;
use Carbon\Carbon;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static \UnitEnum|string|null $navigationGroup = 'Pendaftaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('student_id')
                    ->relationship('student', 'full_name')
                    ->required()
                    ->label('Siswa'),
                \Filament\Forms\Components\Select::make('program_id')
                    ->relationship('program', 'name')
                    ->required()
                    ->label('Program'),
                \Filament\Forms\Components\Select::make('program_class_id')
                    ->relationship('programClass', 'name')
                    ->label('Kelas')
                    ->placeholder('Pilih kelas (opsional)'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required()
                    ->label('Status'),
                \Filament\Forms\Components\DateTimePicker::make('registered_at')
                    ->default(now())
                    ->required()
                    ->label('Tanggal Daftar'),
                \Filament\Forms\Components\Textarea::make('notes')
                    ->columnSpanFull()
                    ->label('Catatan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('program.name')
                    ->label('Program')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('programClass.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('registered_at')
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($record) {
                        $student = $record->student;
                        $age = Carbon::parse($student->birth_date)->age;

                        // Find matching class based on age and program
                        $matchedClass = ProgramClass::where('program_id', $record->program_id)
                            ->where('min_age', '<=', $age)
                            ->where('max_age', '>=', $age)
                            ->first();

                        $record->update([
                            'status' => 'approved',
                            'program_class_id' => $matchedClass?->id,
                        ]);

                        if ($matchedClass) {
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Pendaftaran Disetujui')
                                ->body("Siswa {$student->full_name} (usia {$age} tahun) ditempatkan di kelas: {$matchedClass->name}")
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('Pendaftaran Disetujui')
                                ->body("Siswa {$student->full_name} (usia {$age} tahun) disetujui, tetapi tidak ada kelas yang sesuai dengan usianya.")
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->status === 'approved'),
                \Filament\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn ($record) => $record->update(['status' => 'rejected']))
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->status === 'rejected'),
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
            'index' => ManageRegistrations::route('/'),
        ];
    }
}
