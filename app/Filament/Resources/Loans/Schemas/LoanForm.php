<?php

namespace App\Filament\Resources\Loans\Schemas;

use App\Models\Customer;
use App\Models\Frecuencie;
use App\Models\Rate;
use App\Models\User;
use App\Trait\TraitAleman;
use App\Trait\TraitAmericano;
use App\Trait\TraitFrances;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Log;

class LoanForm
{
    use TraitAleman;
    use TraitAmericano;
    use TraitFrances;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Borrower Information')
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 2,
                        'lg' => 2,
                    ])
                    ->schema([
                        Select::make('customer_id')
                            ->label('Nombre del Solicitante')
                            ->options(Customer::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('user_id')
                            ->label('Usuario')
                            ->options(User::all()->pluck('name', 'id'))
                            ->default(auth()->id())
                            ->required()
                            ->native(false),
                    ]),
                Fieldset::make('Loan Details')
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 2,
                        'lg' => 5,
                    ])
                    ->schema([
                        TextInput::make('amount')
                            ->label('Monto')
                            ->required()
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),
                        Select::make('frecuency_id')
                            ->label('Frecuencia')
                            ->options(Frecuencie::all()->pluck('name', 'id'))
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),

                        Select::make('rate_id')
                            ->label('Tarifa')
                            ->hint('%')
                            ->hintColor('info')
                            // ->helperText('Texto de ayuda')
                            // ->hintIcon(Heroicon::PercentBadge)
                            ->options(Rate::all()->pluck('percent', 'id'))
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),

                        TextInput::make('years')
                            ->label('Años')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            // ->extraInputAttributes(['style' => 'max-width: 10px'])
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),
                        Select::make('amort_method')
                            ->label('Amortización')
                            ->options([
                                'FRANCES' => 'FRANCES',
                                'ALEMAN' => 'ALEMAN',
                                'AMERICANO' => 'AMERICANO',
                            ])
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),
                    ]),

                Repeater::make('plans')
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
                        TextInput::make('date')->label('Fecha')->readonly(),
                        TextInput::make('number')->hidden(),
                        TextInput::make('payment')->label('Cuota')->readonly(),
                        TextInput::make('amort')->label('Amortización')->readonly(),
                        TextInput::make('interest')->label('Intereses')->readonly(),
                        TextInput::make('balance')->label('Pendiente')->readonly(),
                    ])
                    ->defaultItems(0)
                    ->deletable(false)
                    ->addable(false)
                    ->reorderable(false)
                    ->disabled()
                    ->columnSpanFull(),

                Fieldset::make('Totales')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('total_pagado')
                            ->label('Total Pagado')
                            ->readonly()
                            ->live(),
                        TextInput::make('total_amortizacion')
                            ->label('Total Amortización')
                            ->readonly()
                            ->live(),
                        TextInput::make('total_intereses')
                            ->label('Total Intereses')
                            ->readonly()
                            ->live(),
                        TextInput::make('total_pendiente')
                            ->label('Total Pendiente')
                            ->readonly()
                            ->live(),
                    ]),
                // ->colStyles(function () {
                //     return [
                //         'FECHA' => 'background-color: #fafafa; width: 250px; font-weight: bold;',
                //         'CUOTA' => 'background-color: #fafafa; width: 250px;',
                //         'AMORTIZACION' => 'background-color: #fafafa; width: 250px;',
                //         'INTERESES' => 'background-color: #fafafa; width: 250px;',
                //         'PENDIENTE' => 'background-color: #fafafa; width: 250px;',
                //     ];
                // }),

            ]);
    }

    /**
     * Calcula la tabla de amortizacion usando los traita existentes
     * */
    protected static function calculateAmortization(Set $set, Get $get): void
    {
        $amount = $get('amount');
        $frecuencyId = $get('frecuency_id');
        $rateId = $get('rate_id');
        $years = $get('years');
        $method = $get('amort_method');

        // Validar que todos los campos necesarios estén completos
        if (! $amount || ! $frecuencyId || ! $rateId || ! $years || ! $method) {
            $set('plans', []);
            $set('total_pagado', null);
            $set('total_amortizacion', null);
            $set('total_intereses', null);
            $set('total_pendiente', null);

            return;
        }

        // Obtener los datos necesarios
        $frecuency = Frecuencie::find($frecuencyId);
        $rate = Rate::find($rateId);

        if (! $frecuency || ! $rate) {
            $set('plans', []);
            $set('total_pagado', null);
            $set('total_amortizacion', null);
            $set('total_intereses', null);
            $set('total_pendiente', null);

            return;
        }

        // Crear instancia para usar los traits
        $calculator = new static;

        try {
            // Llamar al método correspondiente según el tipo de amortización y frecuencia
            $tabla = collect();

            switch ($method) {
                case 'FRANCES':
                    $tabla = match (strtoupper($frecuency->name)) {
                        'MENSUAL' => $calculator->PlanMensual($rate->percent, $amount, $years),
                        'BIMESTRAL' => $calculator->PlanBimestral($rate->percent, $amount, $years),
                        'TRIMESTRAL' => $calculator->PlanTrimestral($rate->percent, $amount, $years),
                        'SEMESTRAL' => $calculator->PlanSemestral($rate->percent, $amount, $years),
                        'ANUAL' => $calculator->PlanAnual($rate->percent, $amount, $years),
                        default => $calculator->PlanMensual($rate->percent, $amount, $years),
                    };
                    break;

                case 'ALEMAN':
                    $tabla = match (strtoupper($frecuency->name)) {
                        'MENSUAL' => $calculator->PlanMensualAleman($rate->percent, $amount, $years),
                        'BIMESTRAL' => $calculator->PlanBimestralAleman($rate->percent, $amount, $years),
                        'TRIMESTRAL' => $calculator->PlanTrimestralAleman($rate->percent, $amount, $years),
                        'SEMESTRAL' => $calculator->PlanSemestralAleman($rate->percent, $amount, $years),
                        'ANUAL' => $calculator->PlanAnualAleman($rate->percent, $amount, $years),
                        default => $calculator->PlanMensualAleman($rate->percent, $amount, $years),
                    };
                    break;

                case 'AMERICANO':
                    $tabla = match (strtoupper($frecuency->name)) {
                        'MENSUAL' => $calculator->PlanMensualAmericano($rate->percent, $amount, $years),
                        'BIMESTRAL' => $calculator->PlanBimestralAmericano($rate->percent, $amount, $years),
                        'TRIMESTRAL' => $calculator->PlanTrimestralAmericano($rate->percent, $amount, $years),
                        'SEMESTRAL' => $calculator->PlanSemestralAmericano($rate->percent, $amount, $years),
                        'ANUAL' => $calculator->PlanAnualAmericano($rate->percent, $amount, $years),
                        default => $calculator->PlanMensualAmericano($rate->percent, $amount, $years),
                    };
                    break;

                default:
                    $set('plans', []);
                    $set('total_pagado', null);
                    $set('total_amortizacion', null);
                    $set('total_intereses', null);
                    $set('total_pendiente', null);

                    return;
            }

            // Extraer el resumen (primer elemento) y los planes
            $summary = $tabla->firstWhere('RESUMEN', '');
            $plans = $tabla
                ->filter(fn ($item) => ! isset($item['RESUMEN']))
                ->map(fn ($item, $index) => [
                    'date' => $item['FECHA'],
                    'number' => $index + 1,
                    'payment' => number_format($item['CUOTA'], 2, '.', ''),
                    'amort' => number_format($item['AMORTIZACION'], 2, '.', ''),
                    'interest' => number_format($item['INTERESES'], 2, '.', ''),
                    'balance' => number_format($item['PENDIENTE'], 2, '.', ''),
                ])
                ->values()
                ->toArray();

            // Actualizar el Repeater con los planes calculados
            $set('plans', $plans);

            // Actualizar los campos de totales
            if ($summary) {
                $set('total_pagado', number_format($summary['TOTAL PAGADO'] ?? 0, 2, '.', ''));
                $set('total_amortizacion', number_format($summary['AMORTIZACION'] ?? 0, 2, '.', ''));
                $set('total_intereses', number_format($summary['INTERESES'] ?? 0, 2, '.', ''));
                $set('total_pendiente', number_format($summary['PENDIENTE'] ?? 0, 2, '.', ''));
            }
        } catch (\Exception $e) {
            $set('plans', []);
            Log::error('Error calculando amortización: '.$e->getMessage());
        }
    }
}
