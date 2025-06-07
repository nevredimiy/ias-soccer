<?php

namespace App\Filament\Resources\MatcheResource\Pages;

use App\Filament\Resources\MatcheResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMatche extends ViewRecord
{
    protected static string $resource = MatcheResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
