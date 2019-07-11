<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SpreadsheetController;
use App\IndicatorHistory;
use App\Indicators\Custom\MediaPermanenciaGeralCalculator;
use App\Indicators\Indicator;
use App\Indicators\IndicatorOld;
use App\Indicators\CalculateIndicatorSimpleSQL;
use App\Indicators\IndicatorOldSpreadsheet;
use Illuminate\Http\Request;
use App\Enums\UpdateType;
use App\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Indicators\IndicatorsService;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use ReflectionClass;

class IndicatorsController extends Controller
{

    private $indicatorsService;


    /**
     * IndicatorsController constructor.
     */
    public function __construct(IndicatorsService $service)
    {
        $this->indicatorsService = $service;
        $this->middleware('role:' . UserRole::Screen);
    }

    /**
     * Get the last values from the indicators
     */
    public function getLastValues()
    {
        $indicators = $this->indicatorsService->load();
        $array = [];
        foreach ($indicators as $indicator) {
            if ($indicator->isPerUnit()) {
                $per_unit = [];
                foreach (Unit::getDisplayUnits() as $unit) {
                    $per_unit[$unit->id] = $indicator->getLastValue($unit);
                }
                $array[$indicator->id] = $per_unit;
            } else {
                $array[$indicator->id] = $indicator->getLastValue();
            }
        }
        return response()->json($array);
    }

    public function getIndicators()
    {
        return $indicators = $this->indicatorsService->load();
    }


    public function calculateAndSaveAll()
    {
        $this->indicatorsService->calculateAndSaveAll();
    }

    public function index(IndicatorsService $indicators)
    {
        $indicators = $indicators->load();
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
                return $a->id - $b->id;
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


    /**
     * @param bool $all Se vai retornar todas as unidades ou somente as que são mostradas
     * @return array
     */
    public function getUnits($all = false)
    {
        if ($all == "true") {
            return Unit::all()->all();
        } else {
            return Unit::getDisplayUnits()->all();
        }
    }

}
