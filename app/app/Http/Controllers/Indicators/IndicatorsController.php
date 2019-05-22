<?php

namespace App\Http\Controllers\Indicators;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\UpdateType;

class IndicatorsController extends Controller
{

    
    private $indicators = [];

    private function initIndicators(){


    }


    public function showUnits(){
      $units=  App\Unit::all();
      echo var_dump($units);
    }


    public function updateAll($updateType){

    }
}
