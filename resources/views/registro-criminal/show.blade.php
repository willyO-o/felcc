@extends('layouts.app')

@section('page-title', 'Detalles del Registro Criminal ')

@section('content')

@include('registro-criminal.partials._datos', ['datos' => $datos , 'identificador' => $identificador ?? null, 'isAjax' => false])

@endsection
