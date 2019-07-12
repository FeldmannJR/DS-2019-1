@extends('layouts/basic')

@section('body')
  <Panel :fixed="{{json_encode($fixed)}}" :presentation="{{json_encode($presentation)}}" :indicators="{{json_encode($indicators)}}">
@endsection