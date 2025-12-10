<?php

namespace App\Filament\Resources\Frecuencies;

use App\Filament\Resources\Frecuencies\Pages\CreateFrecuencie;
use App\Filament\Resources\Frecuencies\Pages\EditFrecuencie;
use App\Filament\Resources\Frecuencies\Pages\ListFrecuencies;
use App\Filament\Resources\Frecuencies\Pages\ViewFrecuencie;
use App\Filament\Resources\Frecuencies\Schemas\FrecuencieForm;
use App\Filament\Resources\Frecuencies\Schemas\FrecuencieInfolist;
use App\Filament\Resources\Frecuencies\Tables\FrecuenciesTable;
use App\Models\Frecuencie;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FrecuencieResource extends Resource
{
    protected static ?string $model = Frecuencie::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Frecuencie';

    public static function form(Schema $schema): Schema
    {
        return FrecuencieForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FrecuencieInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FrecuenciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFrecuencies::route('/'),
            'create' => CreateFrecuencie::route('/create'),
            'view' => ViewFrecuencie::route('/{record}'),
            'edit' => EditFrecuencie::route('/{record}/edit'),
        ];
    }
}
