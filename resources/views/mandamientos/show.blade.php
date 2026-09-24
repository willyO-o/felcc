@extends('layouts.app')

@section('page-title', 'Detalles del Mandamiento ')

@section('content')

@include('mandamientos.partials._datos',[ 'mandamiento' => $mandamiento, 'identificador' => $identificador ?? null, 'isAjax' => false ])

@endsection
