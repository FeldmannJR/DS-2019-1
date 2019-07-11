<?php

use App\Enums\UpdateType;
use App\Indicators\Calculators\MediaPermanenciaGeralCalculator;
use App\Indicators\Calculators\NumeroPartosNaturaisCalculator;
use App\Indicators\Calculators\OcupacaoPorUnidadeCalculator;
use App\Indicators\Calculators\RotatividadeLeitosCalculator;
use App\Indicators\Calculators\TaxaDeSuspensaoDeCirurgiasCalculator;
use App\Indicators\Custom\NumeroCirurgiasRealizadasCalculator;
use App\Indicators\Custom\NumeroPartosCesareosCalculator;
use App\Indicators\Indicator;
use App\Indicators\IndicatorsService;
use App\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IndicatorTableSeeder extends Seeder
{
    /** @var IndicatorsService */
    private $service = null;

    /**
     * IndicatorTableSeeder constructor.
     */
    public function __construct()
    {
        $this->service = resolve(IndicatorsService::class);;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Remove os indicadores antigos pra não dar conflito
        $this->service->truncate();
        // Copia as unidades para a nossa tabela
        $this->populateUnits();

        $this->addSimple('Número de pacientes internados', UpdateType::RealTime(),
            "select count(*) 
            from agh.v_ain_internacao int inner join agh.agh_unidades_funcionais unidade on unidade.seq = int.unidade_funcional 
            where int.ind_paciente_internado='S' and unidade.seq in (4,3,9,7,11,8,15,19,20,14);"
        );
        $this->addSimple('Taxa de Ocupação Geral', UpdateType::RealTime(),
            "SELECT cast(A.paciente_dia as float) / greatest(B.total_leitos,1) AS RESULT
                    FROM
                      (SELECT count(*) AS paciente_dia
                       FROM agh.v_ain_internacao int
                       INNER JOIN agh.agh_unidades_funcionais unidade ON unidade.seq = int.unidade_funcional
                       INNER JOIN agh.ain_leitos leito ON leito.qrt_numero = int.qrt_numero
                       AND leito.leito = int.leito
                       WHERE int.ind_paciente_internado='S'
                         AND unidade.seq IN (4,
                                             3,
                                             9,
                                             7,
                                             11,
                                             8,
                                             15,
                                             19,
                                             20,
                                             14)) A,
                    
                      (SELECT count(*) AS total_leitos
                       FROM agh.agh_unidades_funcionais unidade
                       INNER JOIN agh.ain_leitos leito ON leito.unf_seq = unidade.seq
                       AND unidade.seq IN (4,
                                           3,
                                           9,
                                           7,
                                           11,
                                           8,
                                           15,
                                           19,
                                           20,
                                           14)
                       AND leito.ind_situacao='A'
                       AND lto_id NOT IN ('01-0121-A',
                                          '01-0121-B')) B;");

        $this->addSimple('Numero de leitos bloqueados para limpeza', UpdateType::RealTime(), "select count(*) from agh.v_ain_leitos_limpeza;");

        $this->addSimple('Número de Internações', UpdateType::Daily(),
            "select count(*) as qtd_nova_internacao
                from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
                where dthr_internacao >= '{LAST_24H}'   and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;");

        $this->addSimple('Número de altas', UpdateType::Daily(),
            "select count(*) as qtd_alta_medica
             from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
            inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
            where dt_saida_paciente >= '{LAST_24H}' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;");

        $this->addSimple("Taxa de Mortalidade Geral", UpdateType::RealTime(),
            "select count(*)
            from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao 
            inner join agh.v_ain_internacao_paciente pac on int.nro_internacao = pac.seq 
            inner join agh.agh_unidades_funcionais unidade on unidade.seq = int.unidade_funcional 
            inner join agh.ain_leitos leito on leito.qrt_numero = int.qrt_numero and leito.leito = int.leito 
            inner join agh.ain_tipos_alta_medica motivo on motivo.codigo = int_t.tam_codigo 
            where motivo.codigo in ('C', 'D', 'O') and unidade.seq in (4,3,9,7,11,8,15,19,20,14)
            and int_t.dthr_alta_medica>= '{LAST_24H}';");

        $this->addSimple('Número de leitos desocupados', UpdateType::RealTime(),
            "select (select count(*) from agh.ain_leitos leitos where ind_situacao='A' and lto_id not in ('01-0121-A', '01-0121-B')) - (select count(distinct trim(int_t.lto_lto_id)) from agh.ain_internacoes int_t inner join agh.v_ain_internacao int
            on int_t.seq = int.nro_internacao where int.ind_paciente_internado='S' and lto_lto_id is not null) - (select sum(leitos_indisp) as total
            from agh.v_ain_leitos_indisp) ;");

        $this->addIndicator('Média de permanência geral', UpdateType::Monthly(), MediaPermanenciaGeralCalculator::class);
        $this->addIndicator('Ocupação por Unidade', UpdateType::RealTime(), OcupacaoPorUnidadeCalculator::class, true);
        $this->addIndicator('Rotatividade de Leitos', UpdateType::Monthly(), RotatividadeLeitosCalculator::class, true);

        $this->addIndicator('Número de Cirurgias Realizadas', UpdateType::Daily(), NumeroCirurgiasRealizadasCalculator::class);
        $this->addIndicator('Taxa de suspensão de cirurgias', UpdateType::Daily(), TaxaDeSuspensaoDeCirurgiasCalculator::class);

        $this->addIndicator('Número de partos naturais', UpdateType::Daily(), NumeroPartosNaturaisCalculator::class);
        $this->addIndicator('Número de partos cesáreos', UpdateType::Daily(), NumeroPartosCesareosCalculator::class);

        /*

        // Planilhas
        $this->addIndicator(new IndicatorNumeroCirurgiasRealizadas(null, "Número de Cirurgias realizadas", UpdateType::Daily()));
        $this->addIndicator(new IndicatorTaxaDeSuspensaoDeCirurgias(null, "Taxa de suspensão de cirurgias", UpdateType::Daily()));
        $this->addIndicator(new IndicatorNumeroPartosNaturais(null, "Número de partos naturais", UpdateType::Daily()));
        $this->addIndicator(new IndicatorNumeroPartosCesareos(null, "Número de partos cesáreos", UpdateType::Daily()));
        */

        if ($this->command->confirm('Você deseja popular o historico com valores aleatorios?', false)) {
            foreach ($this->service->load() as $indicator) {
                $this->addExampleData($indicator);
            }
        }
    }

    /**
     * Copia as unidades do banco do HE para a nossa tabela no datamart
     */
    private function populateUnits()
    {
        Unit::truncate();
        $units = $this->service->getHeConnection()->table("agh.agh_unidades_funcionais")->get();
        foreach ($units as $unit) {
            $newUnit = new Unit();
            $newUnit->id = $unit->seq;
            $newUnit->code = $unit->sigla;
            $newUnit->name = $unit->descricao;
            $newUnit->save();
        }
    }

    /**
     * @param $name
     * @param UpdateType $updateType
     * @param string $query
     */
    private function addSimple($name, UpdateType $updateType, string $query)
    {
        $indicador = $this->service->addSingleQueryIndicator($name, $updateType, $query);

    }

    /**
     * Adicionado um indicador ao banco
     * @param string $name
     * @param UpdateType $updateType
     * @param string $calculatorClass
     * @param bool $per_unit
     */
    private function addIndicator(string $name, UpdateType $updateType, string $calculatorClass, bool $per_unit = false)
    {
        $id = $this->service->addIndicator($name, $updateType, $calculatorClass, $per_unit);
    }

    /**
     * @param IndicatorOld $indicator
     */
    private function addExampleData(Indicator $indicator)
    {
        // Inserting sample data
        $rnd = rand(30, 100);
        $this->command->info("Inserting $rnd values into indicator " . $indicator->getName());
        for ($i = 0; $i < $rnd; $i++) {
            $value = $this->random_float(0, 500000);
            $timestamp = Carbon::now()->subMinutes(rand(1, 60 * 24 * 30 * 12 * 3));
            $unit = null;
            if ($indicator->isPerUnit()) {
                $unit = Unit::getById(Unit::$displayUnits[array_rand(Unit::$displayUnits)]);
            }
            $this->service->addHistoryValue($indicator, $value, $unit, $timestamp);
        }
    }

    private function random_float($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }


}
