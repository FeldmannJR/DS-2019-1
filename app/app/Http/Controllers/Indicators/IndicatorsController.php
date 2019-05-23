<?php

namespace App\Http\Controllers\Indicators;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\UpdateType;
use App\Unit;
use Illuminate\Support\Facades\DB;

class IndicatorsController extends Controller
{

    
    private $indicators = [];

    private function initIndicators(){


    }
    public function addUnits(){
        $unit = new Unit;
        $unit->name = "Centro";
        $unit->code = "CNT";
        $unit->save();

    }

    public function calculateIndicador(){
        # Testando Pacientes-Dia
        $query = "select count(*) 
         from agh.v_ain_internacao int inner join agh.agh_unidades_funcionais unidade on unidade.seq = int.unidade_funcional 
        inner join agh.ain_leitos leito on leito.qrt_numero = int.qrt_numero and leito.leito = int.leito 
        where int.ind_paciente_internado='S' and unidade.seq in (4,3,9,7,11,8,15,19,20,14);
        ";
        $rs = DB::connection("pgsql_agh")->selectOne($query);
        echo $rs->count;
    }


    public function showUnits(){
      $units= Unit::all();
      foreach($units as $unit){
        echo $unit->code." ".$unit->name."<br/>";
      }
     }


    public function updateAll($updateType){

    }
}
