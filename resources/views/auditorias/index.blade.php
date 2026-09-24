@extends('layouts.app')

@section('page-title', 'Auditoría de Cambios')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Auditoría de Cambios</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <h5 class="card-title mb-0">
                            <i class="ri-shield-check-line align-middle me-1 text-primary"></i>
                            Registro de Cambios del Sistema
                        </h5>
                    </div>

                    {{-- Filtros --}}
                    <div class="row mt-3 g-2">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text btn btn-primary">
                                    <i class="ri-search-line"></i>
                                </span>
                                <input type="search" id="searchAuditoria" class="form-control"
                                    placeholder="Buscar por usuario, IP o ID...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filtroModelo" class="form-select">
                                <option value="">Todos los Módulos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filtroEvento" class="form-select">
                                <option value="">Todos los Eventos</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" id="btnFiltrosAvanzados">
                                <i class="ri-filter-3-line align-middle me-1"></i> Fechas
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="btnLimpiarFiltros" title="Limpiar filtros">
                                <i class="ri-refresh-line align-middle"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Filtros de fecha --}}
                    <div id="filtrosAvanzados" style="display: none;" class="row mt-2 p-3 bg-light rounded border g-2">
                        <div class="col-md-6">
                            <label for="fechaInicio" class="form-label small fw-semibold">Fecha Inicio</label>
                            <input type="date" class="form-control form-control-sm" id="fechaInicio">
                        </div>
                        <div class="col-md-6">
                            <label for="fechaFin" class="form-label small fw-semibold">Fecha Fin</label>
                            <input type="date" class="form-control form-control-sm" id="fechaFin">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span id="detalles-pagina" class="text-muted small"></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="tablaAuditorias">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Evento</th>
                                    <th scope="col">Módulo</th>
                                    <th scope="col">ID Registro</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">IP</th>
                                    <th scope="col">Cambios</th>
                                    <th scope="col">Fecha / Hora</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listadoAuditorias">
                                {{-- Se llena vía AJAX --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="loadingAuditorias" class="text-center p-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <div id="sinResultados" class="text-center p-4" style="display: none;">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">No se encontraron registros de auditoría</h5>
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-end mt-3">
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="paginacionAuditorias"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ver Detalles --}}
    <div class="modal fade" id="modalDetallesAuditoria" tabindex="-1" aria-labelledby="modalDetallesAuditoriaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesAuditoriaLabel">
                        <i class="ri-shield-check-line me-1"></i> Detalle de Auditoría
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetallesAuditoriaContent">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line align-middle me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ url('/assets/js/auditorias/index.js?v=' . config('app.aplicacion.version', '1.0')) }}"></script>
@endsection
