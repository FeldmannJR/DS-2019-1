@extends('layouts.basic')


@section('content-title','Upload Tabela')


@section('content')

    @error('tabela')
        <code>Erro: {{$message}}</code>
    @enderror

    <form enctype="multipart/form-data" method="POST" action="{{action('SpreadsheetController@uploadSpreadsheet')}}">
        @csrf
        <label for="tabela">Arquivo:</label>
        <input type="file" name="tabela">
        <button type="submit">Enviar</button>
    </form>

@endsection