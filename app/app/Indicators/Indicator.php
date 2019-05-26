<?php

namespace App\Indicators;

use App\Enums\UpdateType;
use App\IndicatorHistory;
use App\Unit;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Unit $unit
     * @return double valor do indicador calculado
     */
    #abstract public function calculateIndicator(Unit $unit = null);

    /**
     * @param Unit|null $unit de qual unidade irá buscar o valor, caso seja null vai pegar o geral
     * @return double|null ultimo valor calculado
     */
    public function getLastValue(Unit $unit = null)
    {
        $rs = null;
        // Será que essa merda vai funcionar?
        if ($unit !== null) {
            // Caso precise pegar o de uma unidade especifica, ele verifica procura pela tabela pivo
            $unit_id = $unit->id;
            $rs = IndicatorHistory::with('unit')
                ->whereHas('unit', function ($q) use ($unit_id) {
                    $q->where('unit_id', $unit_id);
                });
        } else {
            // Caso não tenha unidade, ele verifica se não tem nada na tabela pivo
            $rs = IndicatorHistory::with('unit')
                ->doesntHave('unit');

        }
        $rs = $rs->where('indicator_id', $this->id)
            ->orderByDesc('created_at')
            ->limit(1);

        //Pega o unico valor que ele retornou
        $values = $rs->first();

        if ($values != null) {
            return $values->value;
        }
        return null;
    }

    public function history()
    {
        return $this->hasMany('App\IndicatorHistory');
    }
}
