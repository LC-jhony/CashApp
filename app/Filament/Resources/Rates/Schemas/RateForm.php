<?php

namespace App\Filament\Resources\Rates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('percent')
                    ->required()
                    ->numeric(),
                TextInput::make('fee')
                    ->required()
                    ->numeric(),
                Select::make('state')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
