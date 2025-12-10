<?php

namespace App\Filament\Resources\Frecuencies\Pages;

use App\Filament\Resources\Frecuencies\FrecuencieResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFrecuencie extends ViewRecord
{
    protected static string $resource = FrecuencieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
