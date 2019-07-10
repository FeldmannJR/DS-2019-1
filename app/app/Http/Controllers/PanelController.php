<?php

namespace App\Http\Controllers;

use App\Enums\Symbols;

use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function index()
    {
        $fixed = [
            [
                "name" => "Indicador Fixo1",
                "text" => "Indicador Fixo1",
                "value" => "50%"
            ],
            [
                "name" => "Indicador Fixo2",
                "text" => "Indicador Fixo2",
                "value" => "72.9"
            ],
            [
                "name" => "Indicador Fixo3",
                "text" => "Indicador Fixo3",
                "value" => "30"
            ],
            [
                "name" => "Indicador Fixo4 de Indicador de Fixo",
                "text" => "Indicador Fixo4 de Indicador de Fixo",
                "value" => "N/A"
            ],
        ];

        $indicators = [
            [
                [
                    [
                        "type" => "numeric",
                        "name" => "Texto Indicador1",
                        "text" => "Texto Indicador1",
                        "value" => "50%"
                    ],
                    [
                        "type" => "statistic",
                        "name" => "Texto Indicador2",
                        "text" => "Texto Indicador2",
                        "graph" => "bar",
                        "data" => [30, 50],
                        "units" => ["A", "B"]
                    ],
                ],
                [
                    [
                        "type" => "multiple",
                        "name" => "Texto Indicador3",
                        "text" => "Texto Indicador3",
                        "graph" => "none",
                        "data" => [1, 2, 3, 4, 5, 6],
                        "units" => ["Label1", "Label2", "Label3", "Label4", "Label5", "Label6"]
                    ],
                ]
            ],
            [
                [
                   [
                    "type" => "statistic",
                    "name" => "Texto Indicador4",
                    "text" => "Texto Indicador4",
                    "graph" => "pie",
                    "data" => [30, 25, 10],
                    "units" => ["Red", "Yellow", "Blue"]
                ]
                ]
            ]
        ];

        return view('panel.index', ['indicators' => $indicators, 'fixed' => $fixed]);
    }
}