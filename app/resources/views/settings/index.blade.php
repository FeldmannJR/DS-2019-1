@extends('layouts/app')

@section('content')
  <Settings :fixed="{{json_encode($fixed)}}" :indicators="{{json_encode($indicators)}}"/>
@endsection