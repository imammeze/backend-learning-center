<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageStudents extends ManageRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Siswa'),
            
            'alhaq_kids' => Tab::make('Al-Haq Kids')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('registrations', function ($q) {
                    $q->whereHas('program', function ($query) {
                        $query->where('code', 'alhaq-kids');
                    });
                })),
                
            'rumah_quran' => Tab::make("Rumah Qur'an")
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('registrations', function ($q) {
                    $q->whereHas('program', function ($query) {
                        $query->where('code', 'rumah-quran');
                    });
                })),
        ];
    }
}
