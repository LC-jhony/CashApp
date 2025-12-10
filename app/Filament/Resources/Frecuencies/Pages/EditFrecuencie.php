<?php

namespace App\Filament\Resources\Frecuencies\Pages;

use App\Filament\Resources\Frecuencies\FrecuencieResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFrecuencie extends EditRecord
{
    protected static string $resource = FrecuencieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
