<?php

namespace App\Indicators;

use App\Enums\UpdateType;
use App\Unit;
use Carbon\Carbon;
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
    public function __construct(?int $id, string $name, UpdateType $update_frequency, string $sqlQueryString)
    {
        parent::__construct($id, $name, $update_frequency);
        $this->sqlQueryString = $sqlQueryString;
    }


    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Carbon|null $data
     * @return double valor do indicador calculado
     */
    public function calculateIndicator(Carbon $data = null)
    {
        if (!$this->isPerUnit()) {
            $rs = $this->getHeConnection()->selectOne($this->replaceDates($this->sqlQueryString, $data));
            // Retorna o primeiro elemento do objeto
            return reset($rs);
        } else {
            
        }
    }
}
