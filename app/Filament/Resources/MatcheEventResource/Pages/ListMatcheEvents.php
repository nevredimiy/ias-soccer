<?php

namespace App\Filament\Resources\MatcheEventResource\Pages;

use App\Filament\Resources\MatcheEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatcheEvents extends ListRecords
{
    protected static string $resource = MatcheEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
