<?php

namespace App\Filament\Resources\SeriesMetaResource\Pages;

use App\Filament\Resources\SeriesMetaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeriesMetas extends ListRecords
{
    protected static string $resource = SeriesMetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
