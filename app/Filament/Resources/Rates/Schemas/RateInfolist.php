<?php

namespace App\Filament\Resources\Rates\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('percent')
                    ->numeric(),
                TextEntry::make('fee')
                    ->numeric(),
                TextEntry::make('state')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
