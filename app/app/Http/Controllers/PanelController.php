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
        $indicators .=          '{ type: "numeric", title: "Texto Indicador1", symbol: "'.Symbols::QUIT.'", value: "50%" },';
        $indicators .=          '{ type: "statistic", title: "Texto Indicador2", graph: "bar", data: [30], labels: ["A"]}';
        $indicators .=      '],';
        $indicators .=      '[';
        $indicators .=          '{ type: "statistic", title: "Texto Indicador3", graph: "doughnut", data: [1, 2, 3, 4, 5, 6], labels: ["Label1", "Label2", "Label3", "Label4", "Label5", "Label6"] },';
        // $indicators .=          '{ type: "statistic", title: "Texto Indicador4", graph: "pie", data: [10, 20, 30], labels: ["Red", "Yellow", "Blue"] }';
        $indicators .=      ']';
        $indicators .= ']';
        return view('panel.index', ['indicators' => $indicators]);
    }
}
