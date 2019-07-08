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
                "title" => "Indicador Fixo1",
                "value" => "50%"
            ],
            [
                "title" => "Indicador Fixo2",
                "value" => "72.9"
            ],
            [
                "title" => "Indicador Fixo3",
                "value" => "30"
            ],
            [
                "title" => "Indicador Fixo4 de Indicador de Fixo",
                "value" => "N/A"
            ],
        ];

        $indicators = [
            [
                [
                    [
                        "type" => "numeric",
                        "title" => "Texto Indicador1",
                        "value" => "50%"
                    ],
                    [
                        "type" => "statistic", 
                        "title" => "Texto Indicador2", 
                        "graph" => "bar",
                        "data" => [30, 50],
                        "units" => ["A", "B"]
                    ],
                ],
                [
                    [
                        "type" => "multiple",
                        "title" => "Texto Indicador3", 
                        "graph" => "doughnut",
                        "data" => [1, 2, 3, 4, 5, 6], 
                        "units" => ["Label1", "Label2", "Label3", "Label4", "Label5", "Label6"]
                    ],
                ]
            ],
            [
                [
                   [ 
                    "type" => "statistic", 
                    "title" => "Texto Indicador4",
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
