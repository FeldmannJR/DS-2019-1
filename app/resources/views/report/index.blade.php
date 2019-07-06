@extends('layouts/app')

@section('content')
  <Report :indicators="{{json_encode($indicators)}}">
@endsection