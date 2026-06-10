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
    {{-- Acordeón de Búsqueda Avanzada --}}
    <div class="row mb-2">
        <div class="col-12">
            <div class="accordion" id="accordionBusquedaVehiculos">
                <div class="accordion-item border-0 shadow-sm">
                    <h2 class="accordion-header" id="headingBusquedaVehiculos">
                        <button class="accordion-button py-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseBusquedaVehiculos"
                            aria-expanded="true" aria-controls="collapseBusquedaVehiculos">
                            <i class="ri-filter-3-line me-2 text-primary"></i>
                            <strong>Búsqueda Avanzada por Campo</strong>
                            <span class="badge bg-primary ms-2 small" id="badgeFiltrosVehiculos" style="display:none;">0 filtros</span>
                        </button>
                    </h2>
                    <div id="collapseBusquedaVehiculos" class="accordion-collapse collapse show"
                        aria-labelledby="headingBusquedaVehiculos">
                        <div class="accordion-body pb-3">
                            <form id="formBusquedaVehiculos" autocomplete="off">
                                <div class="row g-2">
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Placa</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_placa" name="adv_placa" placeholder="Placa o placa antigua...">
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Propietario</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_propietario" name="adv_propietario" placeholder="Nombre del propietario...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Doc. Identidad</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_docidentidad" name="adv_docidentidad" placeholder="CI / pasaporte...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">N° Chasis</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_nochasis" name="adv_nochasis" placeholder="N° de chasis...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">N° Motor</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_nomotor" name="adv_nomotor" placeholder="N° de motor...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Marca</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_marca" name="adv_marca" placeholder="Marca...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Modelo</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_modelo" name="adv_modelo" placeholder="Modelo...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Clase</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_clase" name="adv_clase" placeholder="Clase...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Color</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_color" name="adv_color" placeholder="Color...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Tipo</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_tipo" name="adv_tipo" placeholder="Tipo...">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Servicio</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_servicio" name="adv_servicio" placeholder="Servicio...">
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-label small mb-1 fw-semibold">Dirección Propietario</label>
                                        <input type="text" class="form-control form-control-sm adv-field"
                                            id="adv_dom" name="adv_dom" placeholder="Dirección...">
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-3 align-items-center">
                                    <button type="button" class="btn btn-primary btn-sm" id="btnBuscarAvanzado">
                                        <i class="ri-search-line me-1"></i> Buscar
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLimpiarAvanzado">
                                        <i class="ri-refresh-line me-1"></i> Limpiar
                                    </button>
                                    <span class="text-muted small ms-2">Ingrese uno o más campos para filtrar. Se aplica LIKE en cada campo.</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
