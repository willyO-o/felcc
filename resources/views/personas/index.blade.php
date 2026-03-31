@extends('layouts.app')

@section('page-title', 'Gestión de Personas')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Personas</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 d-flex justify-content-end mb-2">
            <div class="flex-shrink-0">
                <button class="btn btn-primary" id="btnNuevaPersona">
                    <i class="ri-add-line align-middle me-1"></i> Nueva Persona
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-center gap-2">
                        <div class="col-md-4 mt-2 mt-md-0">
                            <div class="input-group">
                                <span class="input-group-text btn btn-primary"><i class="ri-search-line"></i></span>
                                <input type="search" id="searchPersonas" class="form-control"
                                    placeholder="Buscar (Nombre, apellido, CI)...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="filtroGenero" id="filtroGenero" class="form-select">
                                <option value="">Todos los Géneros</option>
                                <option value="MASCULINO">Masculino</option>
                                <option value="FEMENINO">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="filtroEstadoCivil" id="filtroEstadoCivil" class="form-select">
                                <option value="">Todos los Estados</option>
                                <option value="SOLTERO">Soltero</option>
                                <option value="CASADO">Casado</option>
                                <option value="DIVORCIADO">Divorciado</option>
                                <option value="VIUDO">Viudo</option>
                                <option value="CONYUGUE">Cónyuge</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span id="detalles-pagina" class="text-muted small"></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="tablaPersonas">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col">Cédula de Identidad</th>
                                    <th scope="col">Género</th>
                                    <th scope="col">Estado Civil</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Fecha Registro</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listadoPersonas">
                                {{-- Se llena vía JS --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="loadingPersonas" class="text-center p-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <div id="sinResultados" class="text-center p-4" style="display: none;">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">No se encontraron personas</h5>
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-end mt-3">
                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="paginacionPersonas">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar Persona --}}
    <div class="modal fade" id="modalPersona" tabindex="-1" aria-labelledby="modalPersonaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-height: 90vh; display: flex; align-items: flex-start; padding-top: 2rem;">
            <div class="modal-content" id="modalPersonaContent" style="max-height: calc(90vh - 2rem); overflow-y: auto;">
                {{-- Se carga dinámicamente --}}
            </div>
        </div>
    </div>

    {{-- Modal Confirmar Eliminación --}}
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                    </lord-icon>
                    <div class="mt-4">
                        <h4>¿Está seguro?</h4>
                        <p class="text-muted mb-4">Está a punto de eliminar esta persona. Esta acción no se puede deshacer.</p>
                        <div class="hstack gap-2 justify-content-center">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Sí, Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ver Detalles --}}
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Persona</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetallesContent">
                    {{-- Se carga dinámicamente --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="{{ url('/assets/js/personas/index.js') }}"></script>
@endsection
