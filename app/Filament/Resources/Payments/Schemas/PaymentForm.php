<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('loan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('interest')
                    ->required()
                    ->numeric(),
                TextInput::make('amort')
                    ->required()
                    ->numeric(),
                Select::make('type')
                    ->options(['ONTIME' => 'O n t i m e', 'LATE' => 'L a t e'])
                    ->default('ONTIME')
                    ->required(),
            ]);
    }
}
