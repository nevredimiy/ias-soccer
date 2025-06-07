<?php

namespace App\Filament\Resources\TeamColorResource\Pages;

use App\Filament\Resources\TeamColorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeamColor extends EditRecord
{
    protected static string $resource = TeamColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
