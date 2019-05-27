<?php


namespace App\Indicators\Custom;


use App\Enums\UpdateType;
use App\Indicators\Indicator;
use App\Indicators\IndicatorSql;
use App\Unit;
use Carbon\Carbon;

class IndicatorMediaPermanenciaGeral extends IndicatorSql
{
    public function __construct(?int $id, string $name, UpdateType $update_frequency)
    {
        parent::__construct($id, $name, $update_frequency);
    }


    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @return double valor do indicador calculado
     */
    public function calculateIndicator()
    {
        $data = Carbon::now();
        $comecoMes = $data->copy()->startOfMonth();
        $fimMes = $data->copy()->endOfMonth();

        $qry = "select extract(epoch from sum(
                   (case when dt_saida_paciente > '$fimMes' then '$fimMes'
                         when dt_saida_paciente is null then '$fimMes'
                         else dt_saida_paciente
                       end  ) -
                   (case when data < '$comecoMes' then '$comecoMes'
                         else data
                       end )))/86400
                    from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao
                              inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
                    where (dt_saida_paciente is null or dt_saida_paciente>= '$comecoMes') and data<='$fimMes' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14);";

        echo "query: $qry<br>";
        $pacientes_dia = $this->getHeConnection()->selectOne($qry);




        $numeros_saida = $this->getHeConnection()->selectOne("select count(*) 
                    from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao 
                    inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
                    where dt_saida_paciente>='$comecoMes' and dt_saida_paciente<='$fimMes' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) ;");

        $pacientes_dia = reset($pacientes_dia);
        $numeros_saida = reset($numeros_saida);
        return $pacientes_dia / max($numeros_saida, 1);

    }
}