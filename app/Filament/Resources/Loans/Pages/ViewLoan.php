<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use App\Models\Payment;
use App\Models\Plan;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Pages\ViewRecord;

class ViewLoan extends ViewRecord
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('registerPayment')
                ->label('Registrar Pago')
                ->modalHeading('Registrar Pago del Préstamo')
                ->form([
                    Select::make('plan_id')
                        ->label('Cuota')
                        ->options(function () {
                            $loan = $this->getRecord();

                            return Plan::where('loan_id', $loan->id)
                                ->get()
                                ->mapWithKeys(function ($plan) {
                                    return [$plan->id => "Cuota #{$plan->number} - {$plan->date} - \$".number_format($plan->payment, 2)];
                                });
                        })
                        ->required()
                        ->live(),
                    TextInput::make('amount')
                        ->label('Monto Pagado')
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, callable $set) {
                            $planId = $get('plan_id');
                            if ($planId) {
                                $plan = Plan::find($planId);
                                $set('interest', $plan?->interest ?? 0);
                                $set('amort', $plan?->amort ?? 0);
                            }
                        }),
                    TextInput::make('interest')
                        ->label('Intereses')
                        ->numeric()
                        ->required(),
                    TextInput::make('amort')
                        ->label('Amortización')
                        ->numeric()
                        ->required(),
                    Select::make('type')
                        ->label('Tipo')
                        ->options([
                            'ONTIME' => 'A Tiempo',
                            'LATE' => 'Atrasado',
                        ])
                        ->default('ONTIME')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $loan = $this->getRecord();
                    $plan = Plan::find($data['plan_id']);

                    Payment::create([
                        'user_id' => auth()->id(),
                        'loan_id' => $loan->id,
                        'amount' => $data['amount'],
                        'interest' => $data['interest'],
                        'amort' => $data['amort'],
                        'type' => $data['type'],
                    ]);

                    $plan->update(['status' => 'PAID']);
                })
                ->successNotificationTitle('Pago registrado exitosamente'),
        ];
    }
}
