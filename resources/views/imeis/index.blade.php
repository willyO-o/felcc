@extends('layouts.app')

@section('page-title', 'Gestión de IMEIs')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">IMEIs</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 d-flex justify-content-end gap-2 mb-2">
            <div class="flex-shrink-0">
                <button class="btn btn-primary" id="btnNuevoImei">
                    <i class="ri-add-line align-middle me-1"></i> Nuevo IMEI
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
                                <input type="search" id="searchImeis" class="form-control"
                                    placeholder="Buscar (IMEI, características)...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="filtros" id="filtros" class="form-select">
                                <option value="">Filtrar Por</option>
                                <option value="imei">IMEI</option>
                                <option value="caracteristicas">Características</option>
                                <option value="numero">Número Telefónico</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span id="detalles-pagina" class="text-muted small"></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="tablaImeis">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">IMEI</th>
                                    <th scope="col">Características</th>
                                    <th scope="col">Teléfono Vinculado</th>
                                    <th scope="col">Fecha Registro</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listadoImeis">
                                {{-- Se llena vía JS --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="loadingImeis" class="text-center p-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <div id="sinResultados" class="text-center p-4" style="display: none;">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">No se encontraron IMEIs</h5>
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-end mt-3">
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="paginacionImeis">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar IMEI --}}
    <div class="modal fade" id="modalImei" tabindex="-1" aria-labelledby="modalImeiLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modalImeiContent">
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

    {{-- Modal Vincular Teléfono --}}
    <div class="modal fade" id="modalVincularTelefono" tabindex="-1" aria-labelledby="modalVincularTelefonoLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVincularTelefonoLabel">Vincular Teléfono al IMEI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="telefonoVincular" class="form-label">Seleccionar Teléfono *</label>
                        <select id="telefonoVincular" name="telefonoVincular" class="form-select">
                            <option value="">Seleccionar teléfono...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line align-middle me-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="btnVincularTelefono">
                        <i class="ri-phone-add-line align-middle me-1"></i> Vincular
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

    <script src="{{ url('/assets/js/imeis/index.js?v=' . config('app.aplicacion.version')) }}"></script>
@endsection
