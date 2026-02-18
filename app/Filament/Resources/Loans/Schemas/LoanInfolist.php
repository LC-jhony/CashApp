<?php

namespace App\Filament\Resources\Loans\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LoanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Préstamo')
                    ->schema([
                        Fieldset::make('Parámetros')
                            ->columns(5)
                            ->schema([
                                TextEntry::make('amount')
                                    ->label('Monto')
                                    ->numeric()
                                    ->money('USD'),
                                TextEntry::make('frecuency.name')
                                    ->label('Frecuencia'),
                                TextEntry::make('rate.percent')
                                    ->label('Tasa')
                                    ->suffix('%'),
                                TextEntry::make('years')
                                    ->label('Años')
                                    ->suffix(' años'),
                                TextEntry::make('amort_method')
                                    ->label('Amortización'),
                            ]),

                        RepeatableEntry::make('plan')
                            ->label('Cuadro de Marcha')
                            ->columns(5)
                            ->table([
                                TableColumn::make('Fecha'),
                                TableColumn::make('Cuota'),
                                TableColumn::make('Amortización'),
                                TableColumn::make('Intereses'),
                                TableColumn::make('Pendiente'),
                            ])
                            ->schema([
                                TextEntry::make('date')->label('Fecha'),
                                TextEntry::make('payment')->label('Cuota')->money('USD'),
                                TextEntry::make('amort')->label('Amortización')->money('USD'),
                                TextEntry::make('interest')->label('Intereses')->money('USD'),
                                TextEntry::make('balance')->label('Pendiente')->money('USD'),
                            ])
                            ->columnSpanFull(),

                        Fieldset::make('Totales')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('total_pagado')
                                    ->label('Total Pagado')
                                    ->state(fn ($record) => $record->plan->sum('payment'))
                                    ->money('USD'),
                                TextEntry::make('total_amortizacion')
                                    ->label('Total Amortización')
                                    ->state(fn ($record) => $record->plan->sum('amort'))
                                    ->money('USD'),
                                TextEntry::make('total_intereses')
                                    ->label('Total Intereses')
                                    ->state(fn ($record) => $record->plan->sum('interest'))
                                    ->money('USD'),
                                TextEntry::make('total_pendiente')
                                    ->label('Total Pendiente')
                                    ->state(fn ($record) => $record->plan->last()?->balance ?? 0)
                                    ->money('USD'),
                            ]),
                    ]),
            ]);
    }
}
