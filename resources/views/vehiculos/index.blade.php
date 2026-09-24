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
                        <div class="col-auto">
                            <select name="filtros" id="filtros" class="form-select">
                                <option value="">Filtrar Por</option>
                                <option value="placa">Placa</option>
                                <option value="descripcion">Descripción</option>
                                <option value="responsable">Responsable</option>
                                <option value="caso_relacionado">Caso Relacionado</option>
                                <option value="ci_persona">CI de Persona</option>
                                <option value="nombres">Nombre de Persona</option>
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
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content" id="modalDetallesContent">
            </div>
        </div>
    </div>

    {{-- Modal Vincular Persona --}}
    <div class="modal fade" id="modalVincularPersona" tabindex="-1" aria-labelledby="modalVincularPersonaLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVincularPersonaLabel">Vincular Persona al Vehículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formVincularPersona">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="vincularPersonaBuscar" class="form-label">Buscar Persona *</label>
                            <select id="vincularPersonaBuscar" class="form-select" style="width: 100%;" required>
                                <option value="">Seleccionar persona</option>
                            </select>
                            <div id="error-persona_id" class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vincularTipo" class="form-label">Tipo de Información *</label>
                                    <input type="text" id="vincularTipo" class="form-control txtMayuscula"
                                        list="datalistTipo" placeholder="Buscar tipo..." required>
                                    <datalist id="datalistTipo">
                                        <option value="BSISA">
                                        <option value="RUAT">
                                        <option value="SOAT">
                                        <option value="ANH">
                                        <option value="ITB">
                                    </datalist>

                                    <div id="error-tipo" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vincularCaso" class="form-label">Caso (Opcional)</label>
                                    <input type="text" class="form-control txtMayuscula" id="vincularCaso"
                                        placeholder="Ingresa el caso si aplica">
                                    <div id="error-caso" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line align-middle me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnVincularPersona">
                            <i class="ri-link align-middle me-1"></i> Vincular
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link href="{{ url('/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ url('assets/css/select2-bootstrap-5-theme.min.css') }}" type="text/css" />

    <link rel="stylesheet" href="/assets/libs/filepond/filepond.min.css" type="text/css" />
    <link rel="stylesheet" href="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
@endsection

@section('js')

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
    <script src="{{ url('/assets/js/select2.min.js') }}"></script>
    <script src="{{ url('/assets/js/vehiculos/index.js?v=' . config('app.aplicacion.version')) }}"></script>
@endsection
