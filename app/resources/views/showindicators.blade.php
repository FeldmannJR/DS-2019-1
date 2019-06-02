@extends ('layouts.basic')

@section('content-title', 'Indicadores');

@section('content')

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
                            {{$unit->id}}. {{$unit->name}} - {{$indicador->getDisplayLastValue($unit)}}
                            <br>
                        @endforeach
                    @else
                        {{$indicador->getDisplayLastValue()}}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endsection