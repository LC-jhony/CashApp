<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('loan_id')
                    ->numeric(),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('number')
                    ->numeric(),
                TextEntry::make('payment')
                    ->numeric(),
                TextEntry::make('interest')
                    ->numeric(),
                TextEntry::make('amort')
                    ->numeric(),
                TextEntry::make('balance')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
