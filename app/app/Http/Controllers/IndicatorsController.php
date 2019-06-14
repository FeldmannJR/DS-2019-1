<?php

namespace App\Http\Controllers\Indicators;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SpreadsheetController;
use App\IndicatorHistory;
use App\Indicators\Custom\IndicatorMediaPermanenciaGeral;
use App\Indicators\Indicator;
use App\Indicators\IndicatorSimpleSqlQuery;
use App\Indicators\IndicatorSpreadsheet;
use Illuminate\Http\Request;
use App\Enums\UpdateType;
use App\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Indicators\ModelIndicators;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use ReflectionClass;

class IndicatorsController extends Controller
{


    /**
     * IndicatorsController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:' . UserRole::Root);
    }

    public function calculateAndSaveAll()
    {
        ModelIndicators::calculateAndSaveAll();
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

        usort($indicators, function (Indicator $a, Indicator $b) {
            if ($a->isPerUnit() == $b->isPerUnit()) {
                return $a->getId() - $b->getId();
            } else {
                if ($a->isPerUnit()) {
                    return 1;
                }
                if ($b->isPerUnit()) {
                    return -1;
                }
            }
        });
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
