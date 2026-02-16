<?php

namespace App\Trait;

use Carbon\Carbon;

trait TraitAleman
{
    public function PlanMensualAleman($rate, $amount, $years)
    {
        $CUOTAS = $years * 12;
        $TASA_MENSUAL = ($rate / 100) / 12;
        $PRESTAMO = $amount;
        $AMORTIZACION_FIJA = round($PRESTAMO / $CUOTAS, 2);

        $InteresCalculado = 0;
        $CapitalVivo = $PRESTAMO;
        $CuotaPago = 0;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $sumaAmortizaciones = 0;
        $tabla = collect();

        for ($i = 1; $i <= $CUOTAS; $i++) {
            $InteresCalculado = round($CapitalVivo * $TASA_MENSUAL, 2);
            $CuotaPago = round($AMORTIZACION_FIJA + $InteresCalculado, 2);
            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $sumaAmortizaciones += $AMORTIZACION_FIJA;

            $pendiente = $CapitalVivo;
            $CapitalVivo = round($CapitalVivo - $AMORTIZACION_FIJA, 2);

            if ($i === (int) $CUOTAS) {
                $pendiente = 0;
            }

            $payDate = Carbon::now()->addMonth($i);

            if ($i == 1) {
                $tabla = collect([[
                    'FECHA' => $payDate->toDateString(),
                    'CUOTA' => $CuotaPago,
                    'AMORTIZACION' => $AMORTIZACION_FIJA,
                    'INTERESES' => $InteresCalculado,
                    'PENDIENTE' => $pendiente,
                ]]);
            } else {
                $tabla->push([
                    'FECHA' => $payDate->toDateString(),
                    'CUOTA' => $CuotaPago,
                    'AMORTIZACION' => $AMORTIZACION_FIJA,
                    'INTERESES' => $InteresCalculado,
                    'PENDIENTE' => $pendiente,
                ]);
            }
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $SumaCuotas,
            'AMORTIZACION' => $sumaAmortizaciones,
            'INTERESES' => $SumaIntereses,
            'PENDIENTE' => 0,
        ]);

        return $tabla;
    }
}
