<?php


namespace App\Indicators\Custom;


use App\Indicators\IndicatorSpreadsheet;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class IndicatorNumeroPartosNaturais extends IndicatorSpreadsheet
{

    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Carbon $timeToCalculate
     * @return double|double[] valor do indicador calculado ou map por unidade onde a key é o id da unidade e o valor é o numero calculado
     */
    public function calculateIndicator(Carbon $timeToCalculate = null)
    {
        $sum = 0;
        $executed = $this->forEachRowInPartos(function ($data, $tipo) use (&$sum, $timeToCalculate) {
            if ($timeToCalculate->isSameDay($data)) {
                if ($tipo === 'PV') {
                    $sum++;
                }
            }
        });
        if ($executed) {
            return $sum;
        } else {
            return null;
        }
    }
}