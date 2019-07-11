@extends ('layouts.app')

@push('scripts')
@endpush
@section('content')

    <div class="container">
        <h-indicator name="Batatinhas">Eae meu consagrado</h-indicator>
        <table class="table table-striped table-bordered table-hover">
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
                    <td>{{$indicador->getCalculator() instanceof \App\Indicators\Calculators\SpreadsheetCalculator ? ($indicador->getName() . " (planilha)") : $indicador->getName()}}</td>
                    <td>{{$indicador->getUpdateType()->key}}</td>
                    <td>
                        @if($indicador->isPerUnit())
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Valor</th>
                                </tr>

                                @foreach($display_units as $unit)
                                    <tr>
                                        <td>{{$unit->id}}</td>
                                        <td>{{$unit->name}}</td>
                                        <td>{{$indicador->getDisplayLastValue($unit)}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            {{$indicador->getDisplayLastValue()}}
                        @endif
                    </td>
                    <td>{{$indicador->getDisplayLastUpdate()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection