@extends('layouts.app')

@section('page-title', 'Gestión de Ciudadanos')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ciudadanos</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 d-flex justify-content-end gap-2 mb-2">
            <div class="flex-shrink-0">
                <button class="btn btn-primary" id="btnNuevoCiudadano">
                    <i class="ri-add-line align-middle me-1"></i> Nuevo Ciudadano
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-center gap-2">
                        <div class="d-flex gap-2 flex-grow-1">
                            <div class="input-group flex-grow-1" style="max-width: 400px;">
                                <button type="button" class="input-group-text btn btn-primary" id="btnBuscarCiudadanos" title="Buscar">
                                    <i class="ri-search-line"></i>
                                    Buscar
                                </button>
                                <input type="search" id="searchCiudadanos" class="form-control"
                                    placeholder="Ingrese el término de búsqueda...">
                            </div>
                            <select name="searchType" id="searchType" class="form-select" style="max-width: 200px;">
                                <option value="">Buscar en: Todos</option>
                                <option value="nombre_completo">Nombre Completo</option>
                                <option value="cedula">Cédula de Identidad</option>
                                <option value="ap_paterno">Apellido Paterno</option>
                                <option value="ap_esposo">Esposo/a</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <select name="filtroDepartamento" id="filtroDepartamento" class="form-select" style="min-width: 200px;">
                                <option value="">Todos los Departamentos</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}">{{ $departamento->departamento }}</option>
                                @endforeach
                            </select>
                            <select name="filtroSexo" id="filtroSexo" class="form-select" style="min-width: 150px;">
                                <option value="">Todos los Generos</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span id="detalles-pagina" class="text-muted small"></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="tablaCiudadanos">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col">Cédula</th>
                                    <th scope="col">Genero</th>
                                    <th scope="col">Estado Civil</th>
                                    <th scope="col">Ocupación</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Ubicación</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listadoCiudadanos">
                                {{-- Se llena vía JS --}}
                            </tbody>
                        </table>
                    </div>

                    <div id="loadingCiudadanos" class="text-center p-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <div id="sinResultados" class="text-center p-4" style="display: none;">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">No se encontraron ciudadanos</h5>
                    </div>

                    {{-- Paginación --}}
                    <div class="d-flex justify-content-center mt-3">
                        <nav>
                            <ul class="pagination  mb-0" id="paginacionCiudadanos">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar Ciudadano --}}
    <div class="modal fade" id="modalCiudadano" tabindex="-1" aria-labelledby="modalCiudadanoLabel" data-bs-focus="false" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="modalCiudadanoContent">
                {{-- Se carga dinámicamente --}}
            </div>
        </div>
    </div>

    {{-- Modal Ver Detalles --}}
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content" id="modalDetallesContent">
                {{-- Se carga dinámicamente --}}
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="{{ url('/assets/js/ciudadanos/index.js?v=' . config('app.aplicacion.version')) }}"></script>
@endsection
