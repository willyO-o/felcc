@extends('layouts.app')



@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">
                        <i class="ri-file-list-line align-middle me-1"></i> Listado de Mandamientos
                    </h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('mandamientos.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i> Nuevo Mandamiento
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mandamientos-table"
                            class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Hoja de Ruta</th>
                                    <th>Nombre Completo</th>
                                    <th>CI</th>
                                    <th>Tipo Mandamiento</th>
                                    <th>Copia</th>
                                    <th>Delito</th>
                                    <th>Juzgado</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro que desea eliminar este mandamiento? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('title', 'Listado de Mandamientos')

@section('css')
    <!-- DataTables CSS -->
    <link href="{{ url('/assets/libs/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/assets/libs/datatables/responsive.bootstrap5.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/assets/libs/datatables/buttons.bootstrap5.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('page-title', 'Mandamientos de Aprehensión')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Mandamientos</li>
        </ol>
    </div>
@endsection

@section('js')
    <!-- DataTables JS -->
    <script src="{{ url('/assets/libs/datatables/dataTables.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/dataTables.responsive.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/responsive.bootstrap5.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/dataTables.buttons.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/buttons.bootstrap5.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/buttons.html5.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/buttons.print.js') }}"></script>

    <!-- Custom DataTable Script -->
    <script src="{{ url('/assets/js/mandamientos/index.js') }}"></script>
@endsection
