<?php

namespace App\Trait;

use Carbon\Carbon;

trait TraitAmericano
{
    public function PlanMensualAmericano($rate, $amount, $years)
    {
        // cálculos base
        $CUOTAS = $years * 12;
        $TASA_MENSUAL = $rate / 12;
        $PRESTAMO = $amount;
        // NO HAY AMORTIZACION

        $tabla = collect();
        $CapitalVivo = $PRESTAMO;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $CUOTAS; $i++) {
            $InteresCalculado = round(($CapitalVivo * $TASA_MENSUAL) / 100, 2);
            $Amortizacion = 0;

            if ($i < $CUOTAS) {
                $CuotaPago = $InteresCalculado;
            } else {
                $Amortizacion = $PRESTAMO;
                $CuotaPago = $InteresCalculado + $PRESTAMO;
                $CapitalVivo -= $PRESTAMO;
            }

            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $Amortizacion;

            $tabla->push([
                'FECHA' => Carbon::now()->addMonth($i)->toDateString(),
                'CUOTA' => round($CuotaPago, 2),
                'AMORTIZACION' => round($Amortizacion, 2),
                'INTERESES' => $InteresCalculado,
                'PENDIENTE' => round($CapitalVivo, 2),
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => round($SumaCuotas, 2),
            'AMORTIZACION' => round($SumaAmortizacion, 2),
            'INTERESES' => round($SumaIntereses, 2),
            'PENDIENTE' => round($CapitalVivo, 2),
        ]);

        return $tabla;
    }
}
