@extends('layouts.app')



@section('content')
    <div class="row">
        <div class="col-12 d-flex justify-content-end mb-2">
            @canany(['superadmin', 'administrador','tecnico_daci'])
                <div class="flex-shrink-0">
                    <button value="" class="btn btn-primary openModal" id="mandamientosBtn">
                        <i class="ri-add-line align-middle me-1"></i> Nuevo Mandamiento
                    </button>
                @endcanany
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

                                <input type="search" id="searchMandamientos" class="form-control "
                                    placeholder="Buscar mandamiento...">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <select name="tipo_filtro" id="tipo_filtro" class="form-select">
                                <option value="">Filtrar por</option>
                                <option value="hoja_ruta">Hoja de Ruta</option>
                                <option value="nombre_persona">Nombre</option>
                                <option value="apellidos">Apellidos</option>
                                <option value="ci">C.I.</option>
                                <option value="estado">Estado</option>
                                <option value="nombre_delito">Delito</option>
                                <option value="tipo_mandamiento">Tipo de Mandamiento</option>
                                <option value="nombre_juzgado">Juzgado</option>
                                <option value="encargardo">Encargado</option>

                            </select>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text btn btn-primary"><i class="ri-calendar-line"></i></span>
                                <input type="text" id="filtroFechas" class="form-control" placeholder="Fecha de ejecución"
                                    data-toggle="daterangepicker" aria-label="Rango de fechas" autocomplete="off">
                                <button type="button" class="btn btn-outline-danger" id="btnLimpiarFechas" title="Limpiar filtro de fechas">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
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

                <div class="card-body overflow-auto" id="containerListaMandamientos" style="height: 60vh;">



                    {{-- <div class="row job-list-row grid-view-mode" id="candidate-list"> --}}
                    <div class="row job-list-row grid-view-mode" id="listadoMandamientos">


                        {{-- <div class="candidate-item col-xxl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Vista GRID -->
                                    <div class="grid-view-content">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-lg rounded"><img src="/assets/images/users/avatar-10.jpg"
                                                        alt="" class="member-img img-fluid d-block rounded"></div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <a href="pages-profile">
                                                    <h5 class="fs-16 mb-1">Tonya Noble</h5>
                                                </a>
                                                <p class="text-muted mb-2">Web Designer</p>
                                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                                    <div class="badge text-bg-success"><i class="mdi mdi-star me-1"></i>4.2
                                                    </div>
                                                    <div class="text-muted">2.2k Ratings</div>
                                                </div>
                                                <div class="d-flex gap-4 mt-2 text-muted">
                                                    <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i>
                                                        Cullera, Spain</div>
                                                    <div><i class="ri-time-line text-primary me-1 align-bottom"></i><span
                                                            class="badge badge-soft-danger">Part Time</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Vista LISTA -->
                                    <div class="list-view-content" style="display: none;">
                                        <div class="d-lg-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm rounded"><img src="/assets/images/users/avatar-10.jpg"
                                                        alt="" class="member-img img-fluid d-block rounded"></div>
                                            </div>
                                            <div class="ms-lg-3 my-3 my-lg-0">
                                                <a href="pages-profile">
                                                    <h5 class="fs-16 mb-2">Tonya Noble</h5>
                                                </a>
                                                <p class="text-muted mb-0">Web Designer</p>
                                            </div>
                                            <div class="d-flex gap-4 mt-0 text-muted mx-auto">
                                                <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i>
                                                    Cullera, Spain</div>
                                                <div><i class="ri-time-line text-primary me-1 align-bottom"></i> <span
                                                        class="badge badge-soft-danger">Part Time</span></div>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 align-items-center mx-auto my-3 my-lg-0">
                                                <div class="badge text-bg-success"><i class="mdi mdi-star me-1"></i>4.2
                                                </div>
                                                <div class="text-muted">2.2k Ratings</div>
                                            </div>
                                            <div>
                                                <a href="#!" class="btn btn-soft-success">View Details</a>
                                                <a href="#!" class="btn btn-ghost-danger btn-icon custom-toggle active"
                                                    data-bs-toggle="button">
                                                    <span class="icon-on"><i
                                                            class="ri-bookmark-line align-bottom"></i></span>
                                                    <span class="icon-off"><i
                                                            class="ri-bookmark-3-fill align-bottom"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}





                    </div>
                    <div id="loadingMandamientos" class="p-5">
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModal" data-bs-focus="false" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="miModalLabel">Registrar Mandamiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @include('mandamientos.partials._form', ['estados' => $estados])
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetalles" data-bs-focus="false"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles del Mandamiento</h5>
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


@section('css')
    <!-- DataTables CSS -->
    <link href="{{ url('/assets/libs/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('/assets/libs/datatables/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('/assets/libs/datatables/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />

    <!-- Select2 CSS -->
    <link href="{{ url('/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/assets/libs/filepond/filepond.min.css" type="text/css" />
    <link rel="stylesheet" href="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
    <link rel="stylesheet" href="{{ url('assets/css/select2-bootstrap-5-theme.min.css') }}" type="text/css" />
    <!-- DateRangePicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('page-title', 'Lista de Mandamientos')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Mandamientos</li>
        </ol>
    </div>
@endsection

@section('js')
    <!-- DataTables JS -->
    <script src="{{ url('/assets/libs/datatables/dataTables.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/dataTables.responsive.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/responsive.bootstrap5.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/dataTables.buttons.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/buttons.bootstrap5.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/buttons.html5.js') }}"></script>
    <script src="{{ url('/assets/libs/datatables/buttons.print.js') }}"></script>
    <script src="{{ url('/assets/js/select2.min.js') }}"></script>

    <!-- Custom DataTable Script -->

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
    <script src="{{ url('assets/libs/ligthbox/index.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.0-2/index.js"
        integrity="sha512-Vdge+4gAuFr0U/JCfFdR24aOl9R0c/3pCYgi5bt/nU+Hl6REetTWmOr6FYjOW/7JdyQt27U8x7XJcE+IS8vKMA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <!-- DateRangePicker Scripts -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/es.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <script src="{{ url('/assets/js/mandamientos/index.js?v=' . config('app.aplicacion.version')) }}"></script>

@endsection
