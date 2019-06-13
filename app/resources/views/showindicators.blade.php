@extends ('layouts.app')


@section('content')

    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nome Indicador</th>
            <th scope="col">Frequencia</th>
            <th scope="col">Ultimo Valor</th>
            <th scope="col">Ultimo Update</th>
        </tr>
        </thead>
        <tbody>

        @foreach($indicators as $indicador)
            <tr>
                <th scope="row">{{$indicador->getId()}}</th>
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
                <td>????</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection