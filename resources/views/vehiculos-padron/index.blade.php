@extends('layouts.app')

@section('page-title', 'Consulta de Vehículos')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Consulta de Vehículos</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        {{-- Búsqueda --}}
                        <div class="d-flex gap-2 flex-grow-1 flex-wrap">
                            <div class="input-group" style="max-width: 420px;">
                                <button type="button" class="btn btn-primary" id="btnBuscarVehiculos" title="Buscar">
                                    <i class="ri-search-line me-1"></i> Buscar
                                </button>
                                <input type="search" id="searchVehiculos" class="form-control"
                                    placeholder="Ingrese término de búsqueda...">
                            </div>
                            <select id="searchType" class="form-select" style="max-width: 210px;">
                                <option value="">Buscar en: Todos</option>
                                <option value="placa">Placa</option>
                                <option value="propietario">Propietario</option>
                                <option value="docidentidad">Doc. Identidad</option>
                                <option value="nochasis">N° Chasis</option>
                                <option value="nomotor">N° Motor</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span id="detalles-pagina" class="text-muted small"></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="tablaVehiculos">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Placa</th>
                                    <th>Placa Antigua</th>
                                    <th>Propietario</th>
                                    <th>Doc. Identidad</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Clase</th>
                                    <th>Color</th>
                                    <th>Tipo</th>
                                    <th>Servicio</th>
                                    <th class="text-center">Detalle</th>
                                </tr>
                            </thead>
                            <tbody id="listadoVehiculos">
                                {{-- Se llena vía JS --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="loadingVehiculos" class="text-center p-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <div id="sinResultados" class="text-center p-4" style="display: none;">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">No se encontraron vehículos</h5>
                        <p class="text-muted">Realice una búsqueda para ver resultados.</p>
                    </div>

                    <div id="estadoInicial" class="text-center p-4">
                        <i class="ri-car-line text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-2 text-muted">Ingrese un término de búsqueda</h5>
                        <p class="text-muted small">Busque por placa, propietario, documento de identidad, N° de chasis o N° de motor.</p>
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-center mt-3">
                        <nav>
                            <ul class="pagination mb-0" id="paginacionVehiculos"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ver Detalles --}}
    <div class="modal fade" id="modalDetallesVehiculo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="modalDetallesVehiculoContent">
                {{-- Se carga dinámicamente --}}
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="{{ url('/assets/js/vehiculos-padron/index.js?v=' . config('app.aplicacion.version')) }}"></script>
@endsection
