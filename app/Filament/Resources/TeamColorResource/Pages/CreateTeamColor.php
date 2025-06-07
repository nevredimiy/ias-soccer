<?php

namespace App\Filament\Resources\TeamColorResource\Pages;

use App\Filament\Resources\TeamColorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTeamColor extends CreateRecord
{
    protected static string $resource = TeamColorResource::class;

     // Метод позволяет перейти в список, после редактировании
     protected function getRedirectUrl(): string
     {
         // Перенаправление на список событий
         return $this->getResource()::getUrl('index');
     }
}
