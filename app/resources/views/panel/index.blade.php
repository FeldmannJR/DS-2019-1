@extends('layouts/basic')

@section('body')
  <Panel :fixed="{{json_encode($fixed)}}" :slides="{{json_encode($indicators)}}" :timers="[5, 5]">
@endsection