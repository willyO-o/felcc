@extends('layouts.app')

@section('page-title', 'Gestión de Vehículos')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Vehículos</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 d-flex justify-content-end gap-2 mb-2">
            <div class="flex-shrink-0">
                <button class="btn btn-primary" id="btnNuevoVehiculo">
                    <i class="ri-add-line align-middle me-1"></i> Nuevo Vehículo
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column-reverse flex-md-row align-items-center gap-2">
                        <div class="col-md-4 mt-2 mt-md-0">
                            <div class="input-group">
                                <span class="input-group-text btn btn-primary"><i class="ri-search-line"></i></span>
                                <input type="search" id="searchVehiculos" class="form-control"
                                    placeholder="Buscar (Placa, descripción, responsable)...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="filtros" id="filtros" class="form-select">
                                <option value="">Filtrar Por</option>
                                <option value="placa">Placa</option>
                                <option value="descripcion">Descripción</option>
                                <option value="responsable">Responsable</option>
                                <option value="caso_relacionado">Caso Relacionado</option>
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
                                    <th scope="col">#</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Personas Vinculadas</th>
                                    <th scope="col">Responsable</th>
                                    <th scope="col">Caso Relacionado</th>
                                    <th scope="col">Fecha Registro</th>
                                    <th scope="col" class="text-center">Acciones</th>
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
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-end mt-3">
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="paginacionVehiculos">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar Vehículo --}}
    <div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="modalVehiculoLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modalVehiculoContent">
                {{-- Se carga dinámicamente --}}
            </div>
        </div>
    </div>

    {{-- Modal Ver Detalles --}}
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="modalDetallesContent">
            </div>
        </div>
    </div>

    {{-- Modal Vincular Persona --}}
    <div class="modal fade" id="modalVincularPersona" tabindex="-1" aria-labelledby="modalVincularPersonaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vincular Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="personaVincular" class="form-label">Buscar Persona</label>
                        <select id="personaVincular" class="form-select" style="width: 100%;"></select>
                    </div>
                    <div class="mb-3">
                        <label for="tipoVinculacion" class="form-label">Tipo de Información</label>
                        <select id="tipoVinculacion" class="form-select">
                            <option value="">Seleccionar tipo</option>
                            <option value="bsisa">BSISA</option>
                            <option value="ruat">RUAT</option>
                            <option value="soat">SOAT</option>
                            <option value="anh">ANH</option>
                            <option value="itb">ITB</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="casoVinculacion" class="form-label">Caso (Opcional)</label>
                        <input type="text" class="form-control" id="casoVinculacion" placeholder="Ingresa el caso si aplica">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line align-middle me-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="btnVincularPersona">
                        <i class="ri-user-add-line align-middle me-1"></i> Vincular
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ url('/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ url('assets/css/select2-bootstrap-5-theme.min.css') }}" type="text/css" />
@endsection

@section('js')
    <script src="{{ url('/assets/js/select2.min.js') }}"></script>
    <script src="{{ url('/assets/js/vehiculos/index.js?v=' . config('app.aplicacion.version')) }}"></script>
@endsection
