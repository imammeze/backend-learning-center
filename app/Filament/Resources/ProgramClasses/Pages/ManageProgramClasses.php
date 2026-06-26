<?php

namespace App\Filament\Resources\ProgramClasses\Pages;

use App\Filament\Resources\ProgramClasses\ProgramClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProgramClasses extends ManageRecords
{
    protected static string $resource = ProgramClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
