<?php


namespace App\Indicators;


use App\Enums\UpdateType;
use App\IndicatorHistory;
use App\Indicators\Indicator;
use App\Unit;
use Illuminate\Support\Facades\DB;

/* Peço perdão antecipadamente pelo vacilo feito nestá classe
 * Devido ao projeto proposto não é possível utilizar 100% da estrutura do eloquent,
 * (é necessário usar OOP nos indicadores)
 * ou eu não consegui entender
 */

class ModelIndicators
{

    /**
     * Carrega os indicadores do banco, caso $updateType tenha um valor será filtrado por ele
     * @param UpdateType|null $updateType filtro de quais indicadores vai carregar
     * @return Indicator[] array com todos os indicadores
     */
    public static function loadIndicators(UpdateType $updateType = null)
    {
        // Construindo a query
        $indicators = DB::table('indicators')
            ->leftJoin('indicators_simple_sql', 'indicators.id', '=', 'indicators_simple_sql.id');
        // Se possuí filtro para o update type
        if ($updateType !== null) {
            $indicators = $indicators->where('update_type', $updateType->value);
        }
        // Executando a query
        $indicators = $indicators->get();
        $returnIndicators = [];
        // Verificando indicator por indicador
        foreach ($indicators as $ind) {
            // Qual frequencia ele vai atualizar
            $updateId = $ind->update_type;
            //Validando o updateType
            if (UpdateType::hasValue($updateId)) {

                $newInstance = null;
                // Verifica se ele possuí entrada no indicators_simple_sql, assim ele terá uma query salva no banco
                if ($ind->sql_query !== null) {
                    $newInstance = new IndicatorSimpleSqlQuery($ind->id, $ind->name, UpdateType::getInstance($updateId), $ind->sql_query);
                }

                // Se ele instanciou a classe joga na lista
                if ($newInstance !== null) {
                    $returnIndicators[] = $newInstance;
                }
            }
        }
        // Retrona a lista
        return $returnIndicators;
    }


    /**
     * @param Indicator $indicator de qual indicator vai pegar o ultimo valor
     * @param Unit|null $unit de qual unidade irá buscar o valor, caso seja null vai pegar o geral
     * @return double|null ultimo valor calculado
     */
    public static function getLastValue(Indicator $indicator, Unit $unit = null)
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
        $rs = $rs->where('indicator_id', $indicator->getId())
            ->orderByDesc('created_at')
            ->limit(1);

        //Pega o unico valor que ele retornou
        $values = $rs->first();

        if ($values != null) {
            return $values->value;
        }
        return null;
    }


    /**
     * @param int $indicatorId id do indicador para adicionar um valor
     * @param float $value valor a ser adicionado
     * @param Unit|null $unit se o valor está atrelado a alguma unidade
     * @param null $timestamp modificar a hora que foi adicionado
     */
    public static function addIndicatorHistoryValue(int $indicatorId, float $value, Unit $unit = null, $timestamp = null)
    {
        $whenAdded = now();
        if ($timestamp !== null) {
            $whenAdded = $timestamp;
        }
        $id = DB::table('indicators_history')->insertGetId([
            'indicator_id' => $indicatorId,
            'value' => $value,
            'created_at' => $whenAdded
        ]);
        if ($unit !== null) {
            DB::table('indicators_history_unit')->insert([
                    'indicator_history_id' => $id,
                    'indicator_id' => $indicatorId,
                    'unit_id' => $unit->id
                ]
            );
        }
    }

    /**
     *
     * @param string $name nome do indicador
     * @param UpdateType $updateType frequencia de update do indicador
     * @return int id do indicador
     */
    private static function addBaseIndicator(string $name, UpdateType $updateType)
    {
        return DB::table('indicators')->insertGetId([
            'name' => $name,
            'update_type' => $updateType->value,
            'created_at' => now()
        ]);
    }

    /**
     * @param string $name nome do indicador
     * @param UpdateType $updateType frequencia de update do indicador
     * @param string $query query que irá executar para calcular o valor
     * @return IndicatorSimpleSqlQuery|null indicador adicionado
     */
    public static function addSimpleQueryIndicator(string $name, UpdateType $updateType, string $query)
    {
        $id = self::addBaseIndicator($name, $updateType);
        if ($id === null) {
            return null;
        }
        DB::table('indicators_simple_sql')->insert([
            'id' => $id,
            'sql_query' => $query
        ]);
        return new IndicatorSimpleSqlQuery($id, $name, $updateType, $query);

    }
}

?>