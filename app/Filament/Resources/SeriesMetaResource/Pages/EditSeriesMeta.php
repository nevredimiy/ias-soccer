<?php

namespace App\Filament\Resources\SeriesMetaResource\Pages;

use App\Filament\Resources\SeriesMetaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeriesMeta extends EditRecord
{
    protected static string $resource = SeriesMetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
