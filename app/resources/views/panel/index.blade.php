@extends('layouts/basic')

@section('body')
  <Panel :slides="{{$indicators}}" :timers="[5, 5]">
@endsection