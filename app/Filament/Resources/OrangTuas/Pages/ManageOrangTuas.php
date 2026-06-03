<?php

namespace App\Filament\Resources\OrangTuas\Pages;

use App\Filament\Resources\OrangTuas\OrangTuaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOrangTuas extends ManageRecords
{
    protected static string $resource = OrangTuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->after(function ($record) {
                    $record->assignRole('orang_tua');
                }),
        ];
    }
}
