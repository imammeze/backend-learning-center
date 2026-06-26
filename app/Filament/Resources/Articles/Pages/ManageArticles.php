<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageArticles extends ManageRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['all' => \Filament\Schemas\Components\Tabs\Tab::make('All Articles')];

        $programs = \App\Models\Program::all();
        foreach ($programs as $program) {
            $tabs[$program->slug ?? $program->code ?? $program->id] = \Filament\Schemas\Components\Tabs\Tab::make($program->name)
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('program_id', $program->id));
        }

        return $tabs;
    }
}
