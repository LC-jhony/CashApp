<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('loan_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('number')
                    ->required()
                    ->numeric(),
                TextInput::make('payment')
                    ->required()
                    ->numeric(),
                TextInput::make('interest')
                    ->required()
                    ->numeric(),
                TextInput::make('amort')
                    ->required()
                    ->numeric(),
                TextInput::make('balance')
                    ->required()
                    ->numeric(),
            ]);
    }
}
