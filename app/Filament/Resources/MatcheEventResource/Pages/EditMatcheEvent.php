<?php

namespace App\Filament\Resources\MatcheEventResource\Pages;

use App\Filament\Resources\MatcheEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatcheEvent extends EditRecord
{
    protected static string $resource = MatcheEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
