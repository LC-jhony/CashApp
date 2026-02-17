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

        $tabla = collect();
        $CapitalVivo = $PRESTAMO;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $CUOTAS; $i++) {
            $InteresCalculado = round($CapitalVivo * $TASA_MENSUAL, 2);
            $CuotaPago = round($AMORTIZACION_FIJA + $InteresCalculado, 2);
            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $AMORTIZACION_FIJA;

            $pendiente = $CapitalVivo;
            $CapitalVivo = round($CapitalVivo - $AMORTIZACION_FIJA, 2);

            if ($i === (int) $CUOTAS) {
                $pendiente = 0;
            }

            $tabla->push([
                'FECHA' => Carbon::now()->addMonth($i)->toDateString(),
                'CUOTA' => $CuotaPago,
                'AMORTIZACION' => $AMORTIZACION_FIJA,
                'INTERESES' => $InteresCalculado,
                'PENDIENTE' => $pendiente,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $SumaCuotas,
            'AMORTIZACION' => $SumaAmortizacion,
            'INTERESES' => $SumaIntereses,
            'PENDIENTE' => 0,
        ]);

        return $tabla;
    }

    public function PlanBimestralAleman($rate, $amount, $years)
    {
        $periodosAnio = 6;
        $totalPeriodos = $years * $periodosAnio;
        $tasaBimestral = ($rate / 100) / 6;
        $AMORTIZACION_FIJA = round($amount / $totalPeriodos, 2);

        $tabla = collect();
        $CapitalVivo = $amount;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $totalPeriodos; $i++) {
            $InteresCalculado = round($CapitalVivo * $tasaBimestral, 2);
            $CuotaPago = round($AMORTIZACION_FIJA + $InteresCalculado, 2);
            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $AMORTIZACION_FIJA;

            $pendiente = $CapitalVivo;
            $CapitalVivo = round($CapitalVivo - $AMORTIZACION_FIJA, 2);

            if ($i === (int) $totalPeriodos) {
                $pendiente = 0;
            }

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 2)->toDateString(),
                'CUOTA' => $CuotaPago,
                'AMORTIZACION' => $AMORTIZACION_FIJA,
                'INTERESES' => $InteresCalculado,
                'PENDIENTE' => $pendiente,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $SumaCuotas,
            'AMORTIZACION' => $SumaAmortizacion,
            'INTERESES' => $SumaIntereses,
            'PENDIENTE' => 0,
        ]);

        return $tabla;
    }

    public function PlanTrimestralAleman($rate, $amount, $years)
    {
        $periodosAnio = 4;
        $totalPeriodos = $years * $periodosAnio;
        $tasaTrimestral = ($rate / 100) / 4;
        $AMORTIZACION_FIJA = round($amount / $totalPeriodos, 2);

        $tabla = collect();
        $CapitalVivo = $amount;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $totalPeriodos; $i++) {
            $InteresCalculado = round($CapitalVivo * $tasaTrimestral, 2);
            $CuotaPago = round($AMORTIZACION_FIJA + $InteresCalculado, 2);
            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $AMORTIZACION_FIJA;

            $pendiente = $CapitalVivo;
            $CapitalVivo = round($CapitalVivo - $AMORTIZACION_FIJA, 2);

            if ($i === (int) $totalPeriodos) {
                $pendiente = 0;
            }

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 3)->toDateString(),
                'CUOTA' => $CuotaPago,
                'AMORTIZACION' => $AMORTIZACION_FIJA,
                'INTERESES' => $InteresCalculado,
                'PENDIENTE' => $pendiente,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $SumaCuotas,
            'AMORTIZACION' => $SumaAmortizacion,
            'INTERESES' => $SumaIntereses,
            'PENDIENTE' => 0,
        ]);

        return $tabla;
    }

    public function PlanSemestralAleman($rate, $amount, $years)
    {
        $periodosAnio = 2;
        $totalPeriodos = $years * $periodosAnio;
        $tasaSemestral = ($rate / 100) / 2;
        $AMORTIZACION_FIJA = round($amount / $totalPeriodos, 2);

        $tabla = collect();
        $CapitalVivo = $amount;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $totalPeriodos; $i++) {
            $InteresCalculado = round($CapitalVivo * $tasaSemestral, 2);
            $CuotaPago = round($AMORTIZACION_FIJA + $InteresCalculado, 2);
            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $AMORTIZACION_FIJA;

            $pendiente = $CapitalVivo;
            $CapitalVivo = round($CapitalVivo - $AMORTIZACION_FIJA, 2);

            if ($i === (int) $totalPeriodos) {
                $pendiente = 0;
            }

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 6)->toDateString(),
                'CUOTA' => $CuotaPago,
                'AMORTIZACION' => $AMORTIZACION_FIJA,
                'INTERESES' => $InteresCalculado,
                'PENDIENTE' => $pendiente,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $SumaCuotas,
            'AMORTIZACION' => $SumaAmortizacion,
            'INTERESES' => $SumaIntereses,
            'PENDIENTE' => 0,
        ]);

        return $tabla;
    }

    public function PlanAnualAleman($rate, $amount, $years)
    {
        $totalPeriodos = $years;
        $tasaAnual = $rate / 100;
        $AMORTIZACION_FIJA = round($amount / $totalPeriodos, 2);

        $tabla = collect();
        $CapitalVivo = $amount;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $totalPeriodos; $i++) {
            $InteresCalculado = round($CapitalVivo * $tasaAnual, 2);
            $CuotaPago = round($AMORTIZACION_FIJA + $InteresCalculado, 2);
            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $AMORTIZACION_FIJA;

            $pendiente = $CapitalVivo;
            $CapitalVivo = round($CapitalVivo - $AMORTIZACION_FIJA, 2);

            if ($i === (int) $totalPeriodos) {
                $pendiente = 0;
            }

            $tabla->push([
                'FECHA' => Carbon::now()->addYears($i)->toDateString(),
                'CUOTA' => $CuotaPago,
                'AMORTIZACION' => $AMORTIZACION_FIJA,
                'INTERESES' => $InteresCalculado,
                'PENDIENTE' => $pendiente,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $SumaCuotas,
            'AMORTIZACION' => $SumaAmortizacion,
            'INTERESES' => $SumaIntereses,
            'PENDIENTE' => 0,
        ]);

        return $tabla;
    }
}
