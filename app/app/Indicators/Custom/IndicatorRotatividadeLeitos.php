<?php


namespace App\Indicators\Custom;


use App\Enums\UpdateType;
use App\Indicators\IndicatorSql;
use Carbon\Carbon;

class IndicatorRotatividadeLeitos extends IndicatorSql
{

    public function __construct(?int $id, string $name, UpdateType $update_frequency)
    {
        parent::__construct($id, $name, $update_frequency, true);
    }


    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @return double|double[] valor do indicador calculado ou map por unidade
     */
    public function calculateIndicator()
    {
        #$data = Carbon::now();
        $data = Carbon::createFromDate(2019,03,3);
        $comecoMes = $data->copy()->startOfMonth();
        $fimMes = $data->copy()->endOfMonth();

        $rs_altas = $this->getHeConnection()->select(
            "select unidade.seq as unidade_id, count(*) as qtd_alta_medica
                from agh.v_ain_internacao int inner join agh.ain_internacoes int_t on int_t.seq = int.nro_internacao 
                inner join agh.agh_unidades_funcionais unidade  on unidade.seq = int.unidade_funcional
                where dt_saida_paciente>='$comecoMes' and dt_saida_paciente<='$fimMes' and unidade_funcional in (4,3,9,7,11,8,15,19,20,14) 
                group by unidade.seq;");

        $rs_leitos = $this->getHeConnection()->select(
            "select unidade.seq as unidade_id, count(*) as total_leitos from agh.agh_unidades_funcionais unidade 
            inner join agh.ain_leitos leito on leito.unf_seq = unidade.seq 
            and unidade.seq in (4,3,9,7,11,8,15,19,20,14) and leito.ind_situacao='A' 
            and lto_id not in ('01-0121-A', '01-0121-B')
            group by unidade.seq, unidade.descricao;");


        $altas = [];
        foreach ($rs_altas as $a) {
            $altas[$a->unidade_id] = $a->qtd_alta_medica;
        }
        $returnArray = [];
        foreach ($rs_leitos as $l) {
            $qtd_altas = 0;
            if (array_key_exists($l->unidade_id, $altas)) {
                $qtd_altas = $altas[$l->unidade_id];
            }
            $leitos = $l->total_leitos;
            $returnArray[$l->unidade_id] = $qtd_altas / max(1, $leitos);
        }
        return $returnArray;

    }
}