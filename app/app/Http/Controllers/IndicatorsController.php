<?php

namespace App\Http\Controllers\Indicators;

use App\Http\Controllers\Controller;
use App\IndicatorHistory;
use App\Indicators\Custom\IndicatorMediaPermanenciaGeral;
use App\Indicators\Indicator;
use App\Indicators\IndicatorSimpleSqlQuery;
use Illuminate\Http\Request;
use App\Enums\UpdateType;
use App\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Indicators\ModelIndicators;
use ReflectionClass;

class IndicatorsController extends Controller
{


    public function calculateAndSaveAll()
    {
        $data = Carbon::create(2019, 3, 15);
        $indicators = \App\Indicators\ModelIndicators::loadIndicators();
        foreach ($indicators as $indicator) {
            $indicator->calculateAndSave($data);
        }
    }

    public function index()
    {
        $indicators = \App\Indicators\ModelIndicators::loadIndicators();
        $units_ids = Unit::$displayUnits;
        $all_units = Unit::getAllUnits();
        $display_units = [];
        foreach ($units_ids as $id) {
            if (array_key_exists($id, $all_units)) {
                $display_units[] = $all_units[$id];
            }
        }
        return view('showindicators')
            ->with('indicators', $indicators)
            ->with('display_units', $display_units);
    }


    public function calculateIndicador()
    {

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
