<?php

namespace App\Http\Controllers\Indicators;

use App\Http\Controllers\Controller;
use App\IndicatorHistory;
use App\Indicators\custom\IndicatorMediaPermanenciaGeral;
use App\Indicators\Indicator;
use App\Indicators\IndicatorSimpleSqlQuery;
use Illuminate\Http\Request;
use App\Enums\UpdateType;
use App\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Indicators\ModelIndicators;

class IndicatorsController extends Controller
{


    private $indicators = [];

    private function initIndicators()
    {


    }

    public function addUnits()
    {
        $unit = new Unit;
        $unit->name = "Centro";
        $unit->code = "CNT";
        $unit->save();

    }

    public function calculateIndicador()
    {
        $i = new IndicatorMediaPermanenciaGeral(null, '', UpdateType::Monthly());
        echo  $i->calculateIndicator();

    }


    public function showUnits()
    {
        $units = Unit::all();
        foreach ($units as $unit) {
            echo $unit->code . " " . $unit->name . "<br/>";
        }
    }


    public function updateAll($updateType)
    {

    }
}
