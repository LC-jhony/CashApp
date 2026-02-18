<?php

namespace App\Observers;

use App\Models\Frecuencie;
use App\Models\Loan;
use App\Models\Plan;
use App\Models\Rate;
use App\Trait\TraitAleman;
use App\Trait\TraitAmericano;
use App\Trait\TraitFrances;

class LoanObserver
{
    use TraitAleman;
    use TraitAmericano;
    use TraitFrances;

    public function created(Loan $loan): void
    {
        $this->generatePlans($loan);
    }

    public function updated(Loan $loan): void
    {
        if ($loan->isDirty(['amount', 'frecuency_id', 'rate_id', 'years', 'amort_method'])) {
            $loan->plan()->delete();
            $this->generatePlans($loan);
        }
    }

    public function deleted(Loan $loan): void
    {
        $loan->plan()->delete();
    }

    protected function generatePlans(Loan $loan): void
    {
        $frecuency = Frecuencie::find($loan->frecuency_id);
        $rate = Rate::find($loan->rate_id);

        if (! $frecuency || ! $rate) {
            return;
        }

        $tabla = $this->calculateAmortizationTable(
            $loan->amort_method,
            $frecuency->name,
            $rate->percent,
            $loan->amount,
            $loan->years
        );

        if ($tabla->isEmpty()) {
            return;
        }

        $plans = $tabla
            ->filter(fn ($item) => ! isset($item['RESUMEN']))
            ->values()
            ->map(fn ($item, $index) => [
                'loan_id' => $loan->id,
                'date' => $item['FECHA'],
                'number' => $index + 1,
                'payment' => $item['CUOTA'],
                'interest' => $item['INTERESES'],
                'amort' => $item['AMORTIZACION'],
                'balance' => $item['PENDIENTE'],
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->toArray();

        Plan::insert($plans);
    }

    protected function calculateAmortizationTable(string $method, string $frecuencyName, float $ratePercent, float $amount, int $years)
    {
        return match ($method) {
            'FRANCES' => match (strtoupper($frecuencyName)) {
                'MENSUAL' => $this->PlanMensual($ratePercent, $amount, $years),
                'BIMESTRAL' => $this->PlanBimestral($ratePercent, $amount, $years),
                'TRIMESTRAL' => $this->PlanTrimestral($ratePercent, $amount, $years),
                'SEMESTRAL' => $this->PlanSemestral($ratePercent, $amount, $years),
                'ANUAL' => $this->PlanAnual($ratePercent, $amount, $years),
                default => collect(),
            },
            'ALEMAN' => match (strtoupper($frecuencyName)) {
                'MENSUAL' => $this->PlanMensualAleman($ratePercent, $amount, $years),
                'BIMESTRAL' => $this->PlanBimestralAleman($ratePercent, $amount, $years),
                'TRIMESTRAL' => $this->PlanTrimestralAleman($ratePercent, $amount, $years),
                'SEMESTRAL' => $this->PlanSemestralAleman($ratePercent, $amount, $years),
                'ANUAL' => $this->PlanAnualAleman($ratePercent, $amount, $years),
                default => collect(),
            },
            'AMERICANO' => match (strtoupper($frecuencyName)) {
                'MENSUAL' => $this->PlanMensualAmericano($ratePercent, $amount, $years),
                'BIMESTRAL' => $this->PlanBimestralAmericano($ratePercent, $amount, $years),
                'TRIMESTRAL' => $this->PlanTrimestralAmericano($ratePercent, $amount, $years),
                'SEMESTRAL' => $this->PlanSemestralAmericano($ratePercent, $amount, $years),
                'ANUAL' => $this->PlanAnualAmericano($ratePercent, $amount, $years),
                default => collect(),
            },
            default => collect(),
        };
    }
}
