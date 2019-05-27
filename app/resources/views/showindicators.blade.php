<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Indicadores</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: top;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        table tr:nth-child(odd) td {
            background-color: #8080805e;
        }

        table th {
            background-color: #1b1b1b75;
            color: black;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 100px;
        }
    </style>
</head>
<body>
<?php
/**
 * @var \App\Indicators\Indicator[] $indicators
 * @var \App\Unit[] $display_units
 */


?>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            Indicadores
        </div>
        <table>
            <tr>
                <th>Nome Indicador</th>
                <th>Frequencia</th>
                <th>Ultimo Valor</th>

            </tr>
            @foreach($indicators as $indicador)
                <tr>
                    <td>{{$indicador->getName()}}</td>
                    <td>{{$indicador->getUpdateFrequency()->key}}</td>
                    <td>
                        @if($indicador->isPerUnit())
                            @foreach($display_units as $unit)
                                {{$unit->id}}. {{$unit->name}} - {{number_format($indicador->getLastValue($unit),2)}}
                                <br>
                            @endforeach
                        @else
                            {{number_format($indicador->getLastValue(),2)}}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
</div>
</body>
</html>
