<?php

namespace App\Filament\Resources\Frecuencies\Pages;

use App\Filament\Resources\Frecuencies\FrecuencieResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFrecuencies extends ListRecords
{
    protected static string $resource = FrecuencieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
