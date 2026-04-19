@extends('layouts.app')

@section('content')

@include('registro-criminal.partials._datos', ['datos' => $datos , 'identificador' => $identificador ?? null, 'isAjax' => false])

@endsection
