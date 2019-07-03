@extends('layouts/basic')

@section('body')
  <Panel :slides="{{json_encode($indicators)}}" :timers="[5, 5]">
@endsection