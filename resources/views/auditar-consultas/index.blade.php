@extends('layouts.app')

@section('page-title', 'Auditoría de Consultas')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Auditoría de Consultas</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <h5 class="card-title mb-0">Registro de Auditoría</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text btn btn-primary"><i class="ri-search-line"></i></span>
                                <input type="search" id="searchAuditoria" class="form-control"
                                    placeholder="Buscar usuario o identificador...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="filtroModulo" id="filtroModulo" class="form-select">
                                <option value="">Todos los Módulos</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="filtroRol" id="filtroRol" class="form-select">
                                <option value="">Todos los Roles</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary" id="btnFiltrosAvanzados">
                                <i class="ri-filter-3-line align-middle me-1"></i> Filtros Avanzados
                            </button>
                        </div>
                    </div>

                    {{-- Filtros Avanzados (oculto por defecto) --}}
                    <div id="filtrosAvanzados" style="display: none;" class="row mb-3 p-3 bg-light rounded border">
                        <div class="col-md-6">
                            <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio">
                        </div>
                        <div class="col-md-6">
                            <label for="fechaFin" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="fechaFin">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="detalles-pagina" class="text-muted small"></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="tablaAuditoria">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Módulo</th>
                                    <th scope="col">Resultados</th>
                                    <th scope="col">Dispositivo</th>
                                    <th scope="col">Fecha/Hora</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listadoAuditoria">
                                {{-- Se llena vía JS --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="loadingAuditoria" class="text-center p-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <div id="sinResultados" class="text-center p-4" style="display: none;">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">No se encontraron registros</h5>
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-end mt-3">
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="paginacionAuditoria">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ver Detalles --}}
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content" id="modalDetallesContent">
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ url('/assets/js/auditar-consultas/index.js?v=' . config('app.aplicacion.version')) }}"></script>
@endsection
