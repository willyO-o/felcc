@extends('layouts.app')



@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">
                        <i class="ri-file-list-line align-middle me-1"></i> Listado de Mandamientos
                    </h5>
                    <div class="flex-shrink-0">
                        <button value="" class="btn btn-primary openModal"
                            id="mandamientosBtn">
                            <i class="ri-add-line align-middle me-1"></i> Nuevo Mandamiento
                        </button>
                    </div>

                </div>
                <div class="card-header">
                    <div class="d-flex justify-content-end ">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" id="btn-grid-view">
                                <i class="ri-grid-fill"></i> Grid
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="btn-list-view">
                                <i class="ri-list-check"></i> Lista
                            </button>
                        </div>
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
                <form method="POST" id="mandamientoForm" action="/mandamientos">

                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div>
                                    <label for="hoja_ruta" class="form-label">Hoja de Ruta</label>
                                    <input type="text" class="form-control" id="hoja_ruta"
                                        placeholder="Ingrese Nro. de  hoja de ruta" name="hoja_ruta" value="">
                                </div>
                            </div><!--end col-->
                            <div class="col-md-6">
                                <div>
                                    <label for="tipo_documento" class="form-label">Tipo </label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tipo_documento" id="rado-origina"
                                                value="ORIGINAL" >
                                            <label class="form-check-label" for="rado-origina">ORIGINAL</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tipo_documento" id="radio-fotocopia"
                                                value="FOTOCOPIA" checked>
                                            <label class="form-check-label" for="radio-fotocopia">FOTOCOPIA</label>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-xxl-6">
                                <div>
                                    <label for="id_persona" class="form-label">Persona <span id="btnPersona"
                                            class="btn btn-sm btn-primary">+ Añadir</span></label>

                                    <select name="id_persona" id="id_persona" class="form-select" required></select>

                                </div>
                            </div><!--end col-->


                            <div class="col-xxl-6">
                                <div>
                                    <label for="id_juzgado" class="form-label">Juzgado</label>
                                    <select name="id_juzgado" id="id_juzgado" class="form-select" required></select>
                                </div>
                            </div><!--end col-->

                            <div class="col-xxl-6">
                                <div>
                                    <label for="id_delito" class="form-label">Delito</label>
                                    <select name="id_delito" id="id_delito" class="form-select" required></select>
                                </div>
                            </div><!--end col-->
                            <div class="col-xxl-6">

                                <div>
                                    <label for="id_tipo_mandamiento" class="form-label" >Tipo Mandamiento</label>

                                    <select name="id_tipo_mandamiento" id="id_tipo_mandamiento" class="form-select" required>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-12">
                                <label for="estado" class="form-label">Estado</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado" id="inlineRadio2"
                                            value="PENDIENTE" checked>
                                        <label class="form-check-label" for="inlineRadio2">PENDIENTE</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado" id="inlineRadio1"
                                            value="EJECUTADO">
                                        <label class="form-check-label" for="inlineRadio1">EJECUTADO</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado" id="inlineRadio3"
                                            value="CANCELADO">
                                        <label class="form-check-label" for="inlineRadio3">CANCELADO</label>
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="col-md-12">
                                <div>
                                    <label for="asignado" class="form-label">Asignado A</label>
                                    <input type="text" class="form-control" id="asignado"
                                        placeholder="Ingrese asignado" name="asignado" value="">
                                </div>
                            </div><!--end col-->

                            <div class="col-12">
                                <label for="imagen_mandamiento" class="form-label">Adjuntar Foto del Mandamiento</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="inputGroupFile02"
                                        accept=".jpg,.png,.jpeg,.webp" name="imagen_mandamiento">
                                    <label class="input-group-text" for="inputGroupFile02">Seleccionar</label>
                                </div>
                            </div>


                        </div><!--end row-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="confirmSave">
                            <i class="ri-save-3-line align-middle me-1"></i>
                            Guardar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('title', 'Listado de Mandamientos')

@section('css')
    <!-- DataTables CSS -->
    <link href="{{ url('/assets/libs/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('/assets/libs/datatables/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('/assets/libs/datatables/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />

    <!-- Select2 CSS -->
    <link href="{{ url('/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/assets/libs/filepond/filepond.min.css" type="text/css" />
    <link rel="stylesheet" href="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
    {{-- <link href="{{ url('/assets/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" /> --}}
@endsection

@section('page-title', 'Mandamientos de Aprehensión')

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
    {{-- <script src="{{ url('/assets/js/mandamientos/index.js') }}"></script> --}}

    <script src="/assets/libs/filepond/filepond.min.js"></script>
    <script src="/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
    <script src="/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
    <script src="/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

    <script src="/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.0-2/index.js"
        integrity="sha512-Vdge+4gAuFr0U/JCfFdR24aOl9R0c/3pCYgi5bt/nU+Hl6REetTWmOr6FYjOW/7JdyQt27U8x7XJcE+IS8vKMA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
