<?php

namespace App\Http\Controllers\Indicators;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\UpdateType;
use App\Unit;

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

    public function showUnits(){
      $units= Unit::all();
      foreach($units as $unit){
        echo $unit->code." ".$unit->name."<br/>";
      }
     }


    public function updateAll($updateType){

    }
}
