<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $fixed = [
            [
                "name" => "Indicador1",
                "text" => "Indicador Fixo1",
                "value" => "50%"
            ],
            [
                "name" => "Indicador2",
                "text" => "Indicador Fixo2",
                "value" => "72.9"
            ],
            [
                "name" => "Indicador3",
                "text" => "Indicador Fixo3",
                "value" => "30"
            ],
            [
                "name" => "Indicador4",
                "text" => "Indicador Fixo4 de Indicador de Fixo",
                "value" => "N/A"
            ],
        ];

        $indicators = [
            [
                "type" => "numeric",
                "name" => "Indicator5",
                "text" => "Texto Indicador1",
                "value" => "50%"
            ],
            [
                "type" => "statistic",
                "name" => "Indicator6",
                "text" => "Texto Indicador2",
                "graph" => "bar",
                "data" => [30, 50],
                "units" => ["A", "B"]
            ],
            [
                "type" => "statistic",
                "name" => "Indicator7",
                "text" => "Texto Indicador3",
                "graph" => "none",
                "data" => [1, 2, 3, 4, 5, 6],
                "units" => ["Label1", "Label2", "Label3", "Label4", "Label5", "Label6"]
            ],
            [
                "type" => "statistic",
                "name" => "Indicator8",
                "text" => "Texto Indicador4",
                "graph" => "pie",
                "data" => [30, 25, 10],
                "units" => ["Red", "Yellow", "Blue"]
            ],
            [
                "type" => "statistic",
                "name" => "Indicator9",
                "text" => "Texto Indicador5",
                "graph" => "pie",
                "data" => [30, 25, 10],
                "units" => ["Red", "Yellow", "Blue"]
            ],
            [
                "type" => "statistic",
                "name" => "Indicator10",
                "text" => "Texto Indicador6",
                "graph" => "pie",
                "data" => [30, 25, 10],
                "units" => ["Red", "Yellow", "Blue"]
            ],
        ];

        return view('settings.index', ['indicators' => $indicators, 'fixed' => $fixed]);
    }
}
