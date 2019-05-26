<?php

namespace App\Indicators;

use App\Enums\UpdateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IndicatorSimpleSqlQuery extends IndicatorSql
{


    private $sqlQueryString;

    /**
     * IndicatorSimpleSqlQuery constructor.
     * @param $sqlQueryString a query que irá executar para calcular o resultado
     */
    public function __construct($sqlQueryString)
    {
        $this->sqlQueryString = $sqlQueryString;
    }


    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Unit $unit
     * @return double valor do indicador calculado
     */
    public function calculateIndicator(Unit $unit = null)
    {
        $rs = $this->getHeConnection()->selectOne($this->sqlQueryString);
        // Retorna o primeiro elemento do objeto
        return reset($rs);

    }
}
