@extends('layouts.app')

@section('content')

@include('mandamientos.partials._datos',[ 'mandamiento' => $mandamiento, 'identificador' => $identificador ?? null, 'isAjax' => false ])

@endsection
