@extends('layouts.app')

@section('page-title', 'Listado de Registros Criminales')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Registro </li>
        </ol>
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-12 d-flex justify-content-end mb-2">
            <div class="flex-shrink-0">
                <a  href="{{route('registro-criminal.create')}}" class="btn btn-primary " id="">
                    <i class="ri-add-line align-middle me-1"></i> Nuevo Registro
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-center gap-2">
                        <div class="col-md-4 mt-2 mt-md-0 ">
                            <div class="input-group">
                                <span class="input-group-text btn btn-primary"><i class="ri-search-line"></i></span>

                                <input type="search" id="searchRegistros" class="form-control "
                                    placeholder="Buscar registro...">
                            </div>

                        </div>
                        <div class="col-md-3">
                            <select name="filtro" id="filtro" class="form-select">
                                <option value="">Filtrar por </option>
                                <option value="ci">C.I.</option>
                                <option value="nombres">Nombre completo</option>
                                <option value="apellidos">Apellidos</option>
                                <option value="alias">Alias</option>
                                <option value="nombre_supuesto">Nombre Supuesto</option>
                                <option value="celular">Telefono/Celular</option>
                                <option value="cud">CUD</option>
                                <option value="padre">Padre</option>
                                <option value="madre">Madre</option>
                                <option value="hijos">Hijos</option>
                                <option value="conyuge">Cónyuge</option>
                                <option value="nacimiento">F. de Nacimiento (dd-mm-yyyy)</option>
                            </select>
                        </div>
                        <div class="col">
                            <select name="filtroEstado" id="filtroEstado" class="form-select d-none">
                                <option value="">Filtrar por Estado</option>
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="EJECUTADO">EJECUTADO</option>
                                <option value="CANCELADO">CANCELADO</option>
                            </select>
                        </div>
                        <div class="btn-group " role="group">
                            <button type="button" class="btn btn-outline-primary active" id="btn-grid-view">
                                <i class="ri-grid-fill"></i> Grid
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="btn-list-view">
                                <i class="ri-list-check"></i> Lista
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span id="detalles-pagina"></span>
                    </div>

                </div>
                <!-- Botones para cambiar de vista -->

                <div class="card-body overflow-auto" id="containerListaRegistros" style="height: 60vh;">



                    {{-- <div class="row job-list-row grid-view-mode" id="candidate-list"> --}}
                    <div class="row job-list-row grid-view-mode" id="listadoRegistros">




                    </div>
                    <div id="loadingRegistros" class="p-5">
                    </div>
                </div>
            </div>
        </div>
    </div>







    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetalles" data-bs-focus="false"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles del Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>



@endsection




@section('js')
    <script src="{{ url('assets/libs/ligthbox/index.js') }}"></script>

    <script src="{{url('assets/js/registro-criminal/index.js?v='.config('app.aplicacion.version'))}}"></script>
@endsection
