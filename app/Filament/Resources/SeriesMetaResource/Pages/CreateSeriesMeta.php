<?php

namespace App\Filament\Resources\SeriesMetaResource\Pages;

use App\Filament\Resources\SeriesMetaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSeriesMeta extends CreateRecord
{
    protected static string $resource = SeriesMetaResource::class;

     // Метод позволяет перейти в список, после редактировании
     protected function getRedirectUrl(): string
     {
         // Перенаправление на список событий
         return $this->getResource()::getUrl('index');
     }
}
