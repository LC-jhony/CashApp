<?php

namespace App\Trait;

use Carbon\Carbon;

trait TraitAmericano
{
    public function PlanMensualAmericano($rate, $amount, $years)
    {
        $CUOTAS = $years * 12;
        $TASA_MENSUAL = $rate / 12;
        $PRESTAMO = $amount;

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

    public function PlanBimestralAmericano($rate, $amount, $years)
    {
        $periodosAnio = 6;
        $totalPeriodos = $years * $periodosAnio;
        $tasaBimestral = $rate / 6;

        $tabla = collect();
        $CapitalVivo = $amount;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $totalPeriodos; $i++) {
            $InteresCalculado = round(($CapitalVivo * $tasaBimestral) / 100, 2);
            $Amortizacion = 0;

            if ($i < $totalPeriodos) {
                $CuotaPago = $InteresCalculado;
            } else {
                $Amortizacion = $amount;
                $CuotaPago = $InteresCalculado + $amount;
                $CapitalVivo -= $amount;
            }

            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $Amortizacion;

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 2)->toDateString(),
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

    public function PlanTrimestralAmericano($rate, $amount, $years)
    {
        $periodosAnio = 4;
        $totalPeriodos = $years * $periodosAnio;
        $tasaTrimestral = $rate / 4;

        $tabla = collect();
        $CapitalVivo = $amount;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $totalPeriodos; $i++) {
            $InteresCalculado = round(($CapitalVivo * $tasaTrimestral) / 100, 2);
            $Amortizacion = 0;

            if ($i < $totalPeriodos) {
                $CuotaPago = $InteresCalculado;
            } else {
                $Amortizacion = $amount;
                $CuotaPago = $InteresCalculado + $amount;
                $CapitalVivo -= $amount;
            }

            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $Amortizacion;

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 3)->toDateString(),
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

    public function PlanSemestralAmericano($rate, $amount, $years)
    {
        $periodosAnio = 2;
        $totalPeriodos = $years * $periodosAnio;
        $tasaSemestral = $rate / 2;

        $tabla = collect();
        $CapitalVivo = $amount;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $totalPeriodos; $i++) {
            $InteresCalculado = round(($CapitalVivo * $tasaSemestral) / 100, 2);
            $Amortizacion = 0;

            if ($i < $totalPeriodos) {
                $CuotaPago = $InteresCalculado;
            } else {
                $Amortizacion = $amount;
                $CuotaPago = $InteresCalculado + $amount;
                $CapitalVivo -= $amount;
            }

            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $Amortizacion;

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 6)->toDateString(),
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

    public function PlanAnualAmericano($rate, $amount, $years)
    {
        $totalPeriodos = $years;
        $tasaAnual = $rate;

        $tabla = collect();
        $CapitalVivo = $amount;
        $SumaIntereses = 0;
        $SumaCuotas = 0;
        $SumaAmortizacion = 0;

        for ($i = 1; $i <= $totalPeriodos; $i++) {
            $InteresCalculado = round(($CapitalVivo * $tasaAnual) / 100, 2);
            $Amortizacion = 0;

            if ($i < $totalPeriodos) {
                $CuotaPago = $InteresCalculado;
            } else {
                $Amortizacion = $amount;
                $CuotaPago = $InteresCalculado + $amount;
                $CapitalVivo -= $amount;
            }

            $SumaIntereses += $InteresCalculado;
            $SumaCuotas += $CuotaPago;
            $SumaAmortizacion += $Amortizacion;

            $tabla->push([
                'FECHA' => Carbon::now()->addYears($i)->toDateString(),
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
