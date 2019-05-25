<?php

namespace App;

use App\Enums\UpdateType;
use Illuminate\Database\Eloquent\Model;

abstract  class Indicator extends Model
{
    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Unit $unit
     * @return double valor do indicador calculado
     */
    abstract public function calculateIndicator(Unit $unit = null);

    /**
     * @param Unit $unidade
     */
    public function getLastValue(Unit $unit = null){

    }

}
