@extends('layouts.app')


@section('page-title', 'Registrar Mandamiento')

@section('content')
    <div class="row">

        <div class="col-10">
            <div class="card">
                <div class="card-header ">
                    <h4 class="">Registrar Mandamiento</h4>
                </div>

                <div class="card-body">

                    @include('mandamientos.partials._form', ['estados' => $estados])

                </div>
            </div>
        </div>
    </div>
@endsection


@section('css')
    <link href="{{ url('/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/assets/libs/filepond/filepond.min.css" type="text/css" />
    <link rel="stylesheet" href="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
    <link rel="stylesheet" href="{{ url('assets/css/select2-bootstrap-5-theme.min.css') }}" type="text/css" />
@endsection
@section('js')

    <script src="{{ url('/assets/js/select2.min.js') }}"></script>

    <!-- Custom DataTable Script -->

    <script src="{{ url('/assets/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ url('/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ url('/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
    </script>
    <script
        src="{{ url('/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}">
    </script>
    {{-- <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script> --}}

    <script src="{{ url('assets/libs/filepond/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="{{ url('/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/es.js"></script>
    <script src="{{ url('/assets/js/mandamientos/formulario.js?v=' . config('app.aplicacion.version')) }}"></script>

@endsection
