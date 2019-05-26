<?php

namespace App\Http\Controllers\Indicators;

use App\Http\Controllers\Controller;
use App\IndicatorHistory;
use App\Indicators\Indicator;
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
        $indicators = Indicator::all();
        $lasti= null;
        foreach($indicators as $indicator){
            $last = $indicator->getLastValue();
            echo $indicator->name ." - ".$last."<br>";
            $lasti = $indicator;
        }

        $lasti->getLastValue(Unit::all()->first());

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
