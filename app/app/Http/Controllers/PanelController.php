<?php

namespace App\Http\Controllers;

use App\Enums\Symbols;

use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function index()
    {
        $indicators = '[';
        $indicators .=      '[';
        $indicators .=          '{ type: "numeric", title: "Texto Indicador", symbol: "'.Symbols::QUIT.'", value: "50%" },';
        $indicators .=          '{ type: "statistic", title: "C", graph: "bar", datasets: [{ label: "Texto indicador", data: [30]}]}';
        $indicators .=      '],[';
        $indicators .=          '{ type: "statistic", title: "B", graph: "bar", datasets: [{ label: "Texto indicador", data: [10]},{ label: "Texto indicador", data: [20]},{ label: "Texto indicador", data: [30]},{ label: "Texto indicador", data: [25]},{ label: "Texto indicador", data: [5]},{ label: "Texto indicador", data: [15]},]}';
        $indicators .=      ']';
        $indicators .= ']';
        return view('panel.index', ['indicators' => $indicators]);
    }
}
