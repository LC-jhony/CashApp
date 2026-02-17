<?php

namespace App\Trait;

use Carbon\Carbon;

trait TraitFrances
{
    public function PMT($interest, $num_of_payments, $pv, $fv = 0.00, $Type = 0)
    {
        $expo = pow((1 + $interest), $num_of_payments);

        return ($pv * $interest * $expo / ($expo - 1) + $interest / ($expo - 1) * $fv) *
            ($Type == 0 ? 1 : 1 / ($interest + 1));
    }

    public function PayM($rate, $amount, $years)
    {
        $interes = (0.01 * ($rate)) / 12;
        $periods = $years * 12;

        return $this->PMT($interes, $periods, $amount);
    }

    public function PlanMensual($rate, $amount, $years)
    {
        $prestamo = $amount;
        $tipo = (0.01 * ($rate)) / 12;
        $meses = $years * 12;
        $cuota = round($this->PayM($rate, $amount, $years), 2);

        $tabla = collect();
        $INTERESES = 0;
        $TINTERESES = 0;
        $AMORTIZACION = 0;
        $TAMORTIZACION = 0;
        $TCUOTAS = 0;
        $PENDIENTE = $prestamo;

        for ($i = 1; $i <= $meses; $i++) {
            $INTERESES = round($PENDIENTE * $tipo, 2);
            $TINTERESES += $INTERESES;
            $AMORTIZACION = round($cuota - $INTERESES, 2);
            $TAMORTIZACION += $AMORTIZACION;
            $PENDIENTE = round($PENDIENTE - $AMORTIZACION, 2);

            if ($i === (int) $meses) {
                $PENDIENTE = 0;
            }
            $TCUOTAS += $cuota;

            $tabla->push([
                'FECHA' => Carbon::now()->addMonth($i)->toDateString(),
                'CUOTA' => $cuota,
                'AMORTIZACION' => $AMORTIZACION,
                'INTERESES' => $INTERESES,
                'PENDIENTE' => $PENDIENTE,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $TCUOTAS,
            'AMORTIZACION' => $TAMORTIZACION,
            'INTERESES' => $TINTERESES,
            'PENDIENTE' => $PENDIENTE,
        ]);

        return $tabla;
    }

    public function PayB($rate, $amount, $years)
    {
        $periods = ($years * 12) / 2;
        $interes = (0.01 * ($rate)) / 6;

        return $this->PMT($interes, $periods, $amount);
    }

    public function PlanBimestral($rate, $amount, $years)
    {
        $prestamo = $amount;
        $meses = ($years * 12) / 2;
        $tipo = (0.01 * ($rate)) / 6;
        $cuota = round($this->PayB($rate, $amount, $years), 2);

        $tabla = collect();
        $INTERESES = 0;
        $TINTERESES = 0;
        $AMORTIZACION = 0;
        $TAMORTIZACION = 0;
        $TCUOTAS = 0;
        $PENDIENTE = $prestamo;

        for ($i = 1; $i <= $meses; $i++) {
            $INTERESES = round($PENDIENTE * $tipo, 2);
            $TINTERESES += $INTERESES;
            $AMORTIZACION = round($cuota - $INTERESES, 2);
            $TAMORTIZACION += $AMORTIZACION;
            $PENDIENTE = round($PENDIENTE - $AMORTIZACION, 2);

            if ($i === (int) $meses) {
                $PENDIENTE = 0;
            }
            $TCUOTAS += $cuota;

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 2)->toDateString(),
                'CUOTA' => $cuota,
                'AMORTIZACION' => $AMORTIZACION,
                'INTERESES' => $INTERESES,
                'PENDIENTE' => $PENDIENTE,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $TCUOTAS,
            'AMORTIZACION' => $TAMORTIZACION,
            'INTERESES' => $TINTERESES,
            'PENDIENTE' => $PENDIENTE,
        ]);

        return $tabla;
    }

    public function PayT($rate, $amount, $years)
    {
        $periods = ($years * 12) / 3;
        $interes = (0.01 * ($rate)) / 4;

        return $this->PMT($interes, $periods, $amount);
    }

    public function PlanTrimestral($rate, $amount, $years)
    {
        $prestamo = $amount;
        $meses = ($years * 12) / 3;
        $tipo = (0.01 * ($rate)) / 4;
        $cuota = round($this->PayT($rate, $amount, $years), 2);

        $tabla = collect();
        $INTERESES = 0;
        $TINTERESES = 0;
        $AMORTIZACION = 0;
        $TAMORTIZACION = 0;
        $TCUOTAS = 0;
        $PENDIENTE = $prestamo;

        for ($i = 1; $i <= $meses; $i++) {
            $INTERESES = round($PENDIENTE * $tipo, 2);
            $TINTERESES += $INTERESES;
            $AMORTIZACION = round($cuota - $INTERESES, 2);
            $TAMORTIZACION += $AMORTIZACION;
            $PENDIENTE = round($PENDIENTE - $AMORTIZACION, 2);

            if ($i === (int) $meses) {
                $PENDIENTE = 0;
            }
            $TCUOTAS += $cuota;

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 3)->toDateString(),
                'CUOTA' => $cuota,
                'AMORTIZACION' => $AMORTIZACION,
                'INTERESES' => $INTERESES,
                'PENDIENTE' => $PENDIENTE,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $TCUOTAS,
            'AMORTIZACION' => $TAMORTIZACION,
            'INTERESES' => $TINTERESES,
            'PENDIENTE' => $PENDIENTE,
        ]);

        return $tabla;
    }

    public function PayS($rate, $amount, $years)
    {
        $periods = $years * 2;
        $interes = (0.01 * ($rate)) / 2;

        return $this->PMT($interes, $periods, $amount);
    }

    public function PlanSemestral($rate, $amount, $years)
    {
        $prestamo = $amount;
        $periodos = $years * 2;
        $tipo = (0.01 * ($rate)) / 2;
        $cuota = round($this->PayS($rate, $amount, $years), 2);

        $tabla = collect();
        $INTERESES = 0;
        $TINTERESES = 0;
        $AMORTIZACION = 0;
        $TAMORTIZACION = 0;
        $TCUOTAS = 0;
        $PENDIENTE = $prestamo;

        for ($i = 1; $i <= $periodos; $i++) {
            $INTERESES = round($PENDIENTE * $tipo, 2);
            $TINTERESES += $INTERESES;
            $AMORTIZACION = round($cuota - $INTERESES, 2);
            $TAMORTIZACION += $AMORTIZACION;
            $PENDIENTE = round($PENDIENTE - $AMORTIZACION, 2);

            if ($i === (int) $periodos) {
                $PENDIENTE = 0;
            }
            $TCUOTAS += $cuota;

            $tabla->push([
                'FECHA' => Carbon::now()->addMonths($i * 6)->toDateString(),
                'CUOTA' => $cuota,
                'AMORTIZACION' => $AMORTIZACION,
                'INTERESES' => $INTERESES,
                'PENDIENTE' => $PENDIENTE,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $TCUOTAS,
            'AMORTIZACION' => $TAMORTIZACION,
            'INTERESES' => $TINTERESES,
            'PENDIENTE' => $PENDIENTE,
        ]);

        return $tabla;
    }

    public function PayA($rate, $amount, $years)
    {
        $periods = $years;
        $interes = 0.01 * ($rate);

        return $this->PMT($interes, $periods, $amount);
    }

    public function PlanAnual($rate, $amount, $years)
    {
        $prestamo = $amount;
        $periodos = $years;
        $tipo = 0.01 * ($rate);
        $cuota = round($this->PayA($rate, $amount, $years), 2);

        $tabla = collect();
        $INTERESES = 0;
        $TINTERESES = 0;
        $AMORTIZACION = 0;
        $TAMORTIZACION = 0;
        $TCUOTAS = 0;
        $PENDIENTE = $prestamo;

        for ($i = 1; $i <= $periodos; $i++) {
            $INTERESES = round($PENDIENTE * $tipo, 2);
            $TINTERESES += $INTERESES;
            $AMORTIZACION = round($cuota - $INTERESES, 2);
            $TAMORTIZACION += $AMORTIZACION;
            $PENDIENTE = round($PENDIENTE - $AMORTIZACION, 2);

            if ($i === (int) $periodos) {
                $PENDIENTE = 0;
            }
            $TCUOTAS += $cuota;

            $tabla->push([
                'FECHA' => Carbon::now()->addYears($i)->toDateString(),
                'CUOTA' => $cuota,
                'AMORTIZACION' => $AMORTIZACION,
                'INTERESES' => $INTERESES,
                'PENDIENTE' => $PENDIENTE,
            ]);
        }

        $tabla->prepend([
            'RESUMEN' => '',
            'TOTAL PAGADO' => $TCUOTAS,
            'AMORTIZACION' => $TAMORTIZACION,
            'INTERESES' => $TINTERESES,
            'PENDIENTE' => $PENDIENTE,
        ]);

        return $tabla;
    }
}
