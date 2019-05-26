<?php

use App\Enums\UpdateType;
use App\Indicators\ModelIndicators;
use App\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IndicatorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
                where dthr_internacao >= NOW() - INTERVAL '24 HOURS'   and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;");

        $this->addSimple('Número de altas', UpdateType::Daily(),
            "select count(*) as qtd_alta_medica
             from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
            inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
            where dt_saida_paciente >= NOW() - INTERVAL '24 HOURS' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;");
    }


    /**
     * @param $name
     * @param UpdateType $updateType
     * @param string $query
     */
    private function addSimple($name, UpdateType $updateType, string $query)
    {
        $indicador = ModelIndicators::addSimpleQueryIndicator($name, $updateType, $query);

        // Inserting sample data
        $rnd = rand(30, 500);
        for ($i = 0; $i < $rnd; $i++) {
            $value = $this->random_float(0, 500000);
            $timestamp = Carbon::now()->subMinutes(rand(1, 60 * 24 * 30 * 12 * 3));
            ModelIndicators::addIndicatorHistoryValue($indicador->getId(), $value, null, $timestamp);
        }
    }

    private function random_float($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }


}
