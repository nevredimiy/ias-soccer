<?php

namespace App\Filament\Resources\TeamColorResource\Pages;

use App\Filament\Resources\TeamColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeamColors extends ListRecords
{
    protected static string $resource = TeamColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
