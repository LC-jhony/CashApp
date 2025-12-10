<?php

namespace App\Filament\Resources\Loans\Schemas;

use App\Models\Rate;
use App\Models\User;
use App\Models\Frecuencie;
use App\Trait\TraitAleman;
use App\Trait\TraitFrances;
use Filament\Schemas\Schema;
use App\Trait\TraitAmericano;
use Laravel\Prompts\SelectPrompt;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Container\Attributes\Auth;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class LoanForm
{
    use TraitFrances;
    use TraitAleman;
    use TraitAmericano;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Loan Details')
                    ->columnSpanFull()
                    ->columns([
                        'sm' => 2,
                        'lg' => 5,
                    ])
                    ->schema([
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),
                        Select::make('frecuency_id')
                            ->options(Frecuencie::all()->pluck('name', 'id'))
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),
                        Select::make('user_id')
                            ->options(User::all()->pluck('name', 'id'))
                            ->default(auth()->id())
                            ->required()
                            ->native(false),
                        Select::make('rate_id')
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
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),
                        Select::make('amort method')
                            ->options(['FRANCES' => 'FRANCES', 'ALEMAN' => 'ALEMAN', 'AMERICANO' => 'AMERICANO'])
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateAmortization($set, $get);
                            }),
                    ]),
                TableRepeater::make('plans')
                    ->columns(5)
                    ->schema([
                        TextInput::make('FECHA'),
                        TextInput::make('CUOTA'),
                        TextInput::make('AMORTIZACION'),
                        TextInput::make('INTERESES'),
                        TextInput::make('PENDIENTE'),
                    ])
                    ->defaultItems(0)
                    ->deletable(false)
                    ->addable(false)
                    ->reorderable(false)
                    ->columnSpanFull()
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
        if (!$amount || !$frecuencyId || !$rateId || !$years || !$method) {
            $set('plans', []);
            return;
        }

        // Obtener los datos necesarios
        $frecuency = Frecuencie::find($frecuencyId);
        $rate = Rate::find($rateId);

        if (!$frecuency || !$rate) {
            $set('plans', []);
            return;
        }

        // Crear instancia para usar los traits
        $calculator = new static();

        try {
            // Llamar al método correspondiente según el tipo de amortización y frecuencia
            $tabla = collect();

            switch ($method) {
                case 'FRANCES':
                    // Determinar el método según la frecuencia
                    $tabla = match (strtoupper($frecuency->name)) {
                        'MENSUAL' => $calculator->PlanMensual($rate->percent, $amount, $years),
                        'BIMESTRAL' => $calculator->PlanBimestral($rate->percent, $amount, $years),
                        'TRIMESTRAL' => $calculator->PlanTrimestral($rate->percent, $amount, $years),
                        default => $calculator->PlanMensual($rate->percent, $amount, $years),
                    };
                    break;

                case 'ALEMAN':
                    $tabla = $calculator->PlanMensualAleman($rate->percent, $amount, $years);
                    break;

                case 'AMERICANO':
                    $tabla = $calculator->PlanMensualAmericano($rate->percent, $amount, $years);
                    break;

                default:
                    $set('plans', []);
                    return;
            }

            // Filtrar el resumen (primer elemento) y convertir a array
            $plans = $tabla
                ->filter(function ($item) {
                    // Excluir el elemento de resumen que tiene la clave 'RESUMEN'
                    return !isset($item['RESUMEN']);
                })
                ->values()
                ->toArray();

            // Actualizar el TableRepeater con los planes calculados
            $set('plans', $plans);
        } catch (\Exception $e) {
            // En caso de error, limpiar la tabla
            $set('plans', []);
            \Log::error('Error calculando amortización: ' . $e->getMessage());
        }
    }
}
