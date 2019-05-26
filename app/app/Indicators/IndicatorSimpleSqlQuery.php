<?php

namespace App\Indicators;

use App\Enums\UpdateType;
use App\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IndicatorSimpleSqlQuery extends IndicatorSql
{


    private $sqlQueryString;

    /**
     * IndicatorSimpleSqlQuery constructor.
     * @param int|null $id
     * @param string $name
     * @param UpdateType $update_frequency
     * @param string $sqlQueryString a query que irá executar para calcular o resultado
     */
    public function __construct(?int $id, string $name, UpdateType $update_frequency,string $sqlQueryString)
    {
        parent::__construct($id, $name, $update_frequency);
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
