<?php

namespace App\Filament\Resources\Frecuencies\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FrecuencieInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('days')
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
