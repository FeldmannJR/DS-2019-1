<?php


namespace App\Indicators;


use App\Console\Commands\UpdateIndicators;
use App\Enums\UpdateType;
use App\IndicatorHistory;
use App\IndicatorHistoryUnit;
use App\Indicators\Indicator;
use App\SpreadsheetFile;
use App\Unit;
use Exception;
use function foo\func;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use RuntimeException;

/* Peço perdão antecipadamente pelo vacilo feito nestá classe
 * Devido ao projeto proposto não é possível utilizar 100% da estrutura do eloquent,
 * (é necessário usar OOP nos indicadores)
 * ou eu não consegui entender
 */

class ModelIndicators
{


    public static function truncate()
    {
        IndicatorHistoryUnit::truncate();
        IndicatorHistory::truncate();
        DB::table('indicators_simple_sql')->truncate();
        DB::table('indicators')->truncate();
    }

    /**
     * Carrega os indicadores do banco, caso $updateType tenha um valor será filtrado por ele
     * @param UpdateType|null $updateType filtro de quais indicadores vai carregar
     * @return Indicator[] array com todos os indicadores
     */
    public static function loadIndicators(UpdateType $updateType = null)
    {
        // Construindo a query
        $indicators = DB::table('indicators')
            ->select(['indicators.*', 'indicators_simple_sql.sql_query'])
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
                $class = $ind->class;
                // Se a  classe não existe pula
                if (!class_exists($class)) {
                    continue;
                }
                $id = $ind->id;
                $newInstance = null;
                // Verifica se ele possuí entrada no indicators_simple_sql, assim ele terá uma query salva no banco
                if ($class === IndicatorSimpleSqlQuery::class && $ind->sql_query !== null) {
                    $newInstance = new IndicatorSimpleSqlQuery($id, $ind->name, UpdateType::getInstance($updateId), $ind->sql_query);
                } else {
                    // Instancia a classe que estava no banco
                    $newInstance = new $class($id, $ind->name, UpdateType::getInstance($updateId), $ind->per_unit);
                }
                // Se ele instanciou a classe joga na lista
                if ($newInstance !== null) {
                    if ($ind->last_update !== null)
                        $newInstance->setLastUpdate(Carbon::createFromFormat('Y-m-d H:i:s', $ind->last_update));
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
        $cl = function ($q) {
            $q->on('indicators_history.id', '=', 'indicators_history_unit.history_id')
                ->on('indicators_history.indicator_id', '=', 'indicators_history_unit.indicator_id');
        };
        // Será que essa merda vai funcionar?
        if ($unit !== null) {
            // Caso precise pegar o de uma unidade especifica, ele verifica procura pela tabela pivo
            $unit_id = $unit->id;
            $rs = DB::table('indicators_history')
                ->join('indicators_history_unit', $cl)
                ->where('unit_id', $unit_id);

        } else {
            $rs = DB::table('indicators_history')
                ->leftJoin('indicators_history_unit', $cl)
                ->where('unit_id', null);
        }

        $rs = $rs->where('indicators_history.indicator_id', $indicator->getId())
            ->orderByDesc('created_at')
            ->limit(1);
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
                    'history_id' => $id,
                    'indicator_id' => $indicatorId,
                    'unit_id' => $unit->id
                ]
            );

        }
    }

    /**
     *
     * @param \App\Indicators\Indicator $indicator
     * @return int id do indicador
     */
    public static function addIndicator(Indicator $indicator)
    {
        // Já foi adicionado
        if ($indicator->getId() !== null) {
            throw new \RuntimeException("Indicator already added!");
        }
        $id = DB::table('indicators')->insertGetId([
            'name' => $indicator->getName(),
            'update_type' => $indicator->getUpdateFrequency()->value,
            'class' => $indicator->getClassName(),
            'per_unit' => $indicator->isPerUnit(),
            'created_at' => now()
        ]);
        $indicator->setId($id);
        return $id;
    }

    /**
     * @param string $name nome do indicador
     * @param UpdateType $updateType frequencia de update do indicador
     * @param string $query query que irá executar para calcular o valor
     * @return IndicatorSimpleSqlQuery|null indicador adicionado
     */
    public static function addSimpleQueryIndicator(string $name, UpdateType $updateType, string $query)
    {
        $indicator = new IndicatorSimpleSqlQuery(null, $name, $updateType, $query);
        $id = self::addIndicator($indicator);
        if ($id === null) {
            return null;
        }
        DB::table('indicators_simple_sql')->insert([
            'id' => $id,
            'sql_query' => $query
        ]);
        return $indicator;

    }

    public static function calculateAndSaveAll(UpdateType $updateType = null, Carbon $data = null, UpdateIndicators $command = null)
    {
        if ($data === null)
            $data = Carbon::create(2019, 2, 20);
        $indicators = \App\Indicators\ModelIndicators::loadIndicators($updateType);

        // Instanciando uma vez somente pra não usar memoria desnecessária
        $spreadsheet = self::getSpreadsheetReader();
        $success = true;
        foreach ($indicators as $indicator) {
            // Se for que usa planilhas ele verifica se a planilha é valida
            if ($indicator instanceof IndicatorSpreadsheet) {
                if ($spreadsheet === null) {
                    $success = false;
                } else {
                    $indicator->spreadsheetReader = $spreadsheet;
                }
            }
            if ($success)
                $success = $indicator->calculateAndSave($data, $command !== null ? array($command, 'info') : null);

            if (!$success) {
                $msg = 'Não consegui calcular ' . $indicator->getName();
                if ($command !== null) {
                    $command->error($msg);
                } else {
                    echo($msg . '<br>');
                }
            } else {
                $indicator->setLastUpdate(now());
                DB::table('indicators')->where('id', $indicator->getId())->update(['last_update' => 'NOW()']);

            }
        }
    }

    private static function getSpreadsheetReader()
    {
        $lastFile = SpreadsheetFile::getLastPath();
        if ($lastFile === null) return null;
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        try {
            $spreadsheet = $reader->load($lastFile);
            return $spreadsheet;
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return null;
        }
    }

    /**
     * @return \Illuminate\Database\ConnectionInterface
     */
    public static function getHeConnection()
    {
        return DB::connection("pgsql_agh");
    }
}


?>