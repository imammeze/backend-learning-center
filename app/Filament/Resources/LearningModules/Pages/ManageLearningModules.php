<?php

namespace App\Filament\Resources\LearningModules\Pages;

use App\Filament\Resources\LearningModules\LearningModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLearningModules extends ManageRecords
{
    protected static string $resource = LearningModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
