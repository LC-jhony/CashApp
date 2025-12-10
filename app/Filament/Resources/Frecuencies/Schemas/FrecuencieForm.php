<?php

namespace App\Filament\Resources\Frecuencies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FrecuencieForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('days')
                    ->required()
                    ->numeric(),
            ]);
    }
}
