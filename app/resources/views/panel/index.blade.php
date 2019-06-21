@extends('layouts/basic')

@section('body')
  <Panel :indicators='[
    [
      {
        type: "numeric",
        title: "Texto Indicador",
        symbol: "QUIT",
        value: "50%"
      },
      {
        title: "B",
        type: "statistic",
        values: [1, 2],
        graph: "bar"
      }
    ],
    [
      {
        title: "C"
      }
    ]
  ]'>
@endsection