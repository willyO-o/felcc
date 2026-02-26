@extends('layouts.app')

@section('page-title', 'Nuevo Registro')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('registro-criminal.index') }}">Registros</a></li>
            <li class="breadcrumb-item active">Nuevo Registro</li>
        </ol>
    </div>
@endsection

@section('content')
    <form id="form-registro" autocomplete="off" class="needs-validation" novalidate
        action="{{ route('registro-criminal.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Fotografias</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            <div class="text-center row px-2">

                                <div class="d-inline-block col">
                                    <div class="position-relative  ">

                                        <div class="position-absolute top-100 start-100 translate-middle ">
                                            <label for="product-image-input" class="mb-0" data-bs-toggle="tooltip"
                                                data-bs-placement="right" title="Select Image">
                                                <div class="avatar-xs">
                                                    <div
                                                        class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                        <i class="ri-image-fill"></i>
                                                    </div>
                                                </div>
                                            </label>
                                            <input class="form-control d-none" value="" id="product-image-input"
                                                data-image-input type="file" name="foto_frente"
                                                accept="image/png, image/gif, image/jpeg, image/webp">
                                        </div>
                                        <div class="avatar-xl w-100 h-auto ">
                                            <div class="avatar-title bg-light rounded w-100 " style="min-height: 150px">
                                                <img src="" id="product-img" class="avatar-xl h-auto w-100"
                                                    data-image-input-preview="product-image-input" />
                                            </div>
                                        </div>

                                    </div>

                                    <p class="text-muted">Foto de frente.</p>

                                </div>

                                <div class=" d-inline-block col">
                                    <div class="position-relative ">
                                        <div class="position-absolute top-100 start-100 translate-middle ">
                                            <label for="product-image-input2" class="mb-0" data-bs-toggle="tooltip"
                                                data-bs-placement="right" title="Select Image">
                                                <div class="avatar-xs">
                                                    <div
                                                        class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                        <i class="ri-image-fill"></i>
                                                    </div>
                                                </div>
                                            </label>
                                            <input class="form-control d-none" value="" id="product-image-input2"
                                                data-image-input type="file" name="foto_perfil"
                                                accept="image/png, image/gif, image/jpeg, image/webp">
                                        </div>
                                        <div class="avatar-xl w-100 h-auto ">
                                            <div class="avatar-title bg-light rounded w-100 " style="min-height: 150px">
                                                <img src="" id="product-img2" class="avatar-xl h-auto w-100"
                                                    data-image-input-preview="product-image-input2" />
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted">Foto de perfil.</p>

                                </div>

                            </div>
                        </div>
                        {{-- <div>
                            <h5 class="fs-14 mb-1">Product Gallery</h5>
                            <p class="text-muted">Add Product Gallery Images.</p>

                            <div class="dropzone">
                                <div class="fallback">
                                    <input name="file" type="file" multiple="multiple">
                                </div>
                                <div class="dz-message needsclick">
                                    <div class="mb-3">
                                        <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                    </div>

                                    <h5>Drop files here or click to upload.</h5>
                                </div>
                            </div>

                            <ul class="list-unstyled mb-0" id="dropzone-preview">
                                <li class="mt-2" id="dropzone-preview-list">
                                    <!-- This is used as the file preview template -->
                                    <div class="border rounded">
                                        <div class="d-flex p-2">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded">
                                                    <img data-dz-thumbnail class="img-fluid rounded d-block" src="#"
                                                        alt="Product-Image" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="pt-1">
                                                    <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                                    <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ms-3">
                                                <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <!-- end dropzon-preview -->
                        </div> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 inp">
                                    <label class="form-label" for="ci">Buscar Persona</label>
                                    <div class="input-group mb-3">

                                        <input type="search" class="form-control form-control" id="buscar_persona"
                                            value=""
                                            placeholder="Buscar por CI-complemento, Nombre, apellido, nombres o alias">
                                        <span class="input-group-text btn btn-primary" id="basic-addon1">
                                            <i class=" ri-search-line"></i></span>

                                        <input type="hidden" id="id_persona" name="id_persona" value="">
                                    </div>

                                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="fecha_registro">Fecha de Registro</label>
                                    <input type="date" class="form-control" id="fecha_registro" name="fecha_registro"
                                        value="{{ now()->toDateString() }}" required max="{{ now()->toDateString() }}">
                                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3 inp">
                                    <label class="form-label" for="ci">Nro. C.I.:</label>
                                    <div class=" mb-3">

                                        <input type="text" class="form-control form-control" id="ci"
                                            value="" placeholder="Ingrese número de C.I." name="ci" required>

                                    </div>

                                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="complemento">Complemento</label>
                                    <input type="text" class="form-control" id="complemento" name="complemento"
                                        value="" placeholder="Ingrese complemento">
                                </div>
                            </div>


                        </div>

                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="nombres">Nombres</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control i" id="nombres" value=""
                                    placeholder="Ingrese nombre(s)" name="nombres" required>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="apellidos">Apellidos</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="apellidos" value=""
                                    placeholder="Ingrese apellido(s)" name="apellidos" required>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="nombre_supuesto">Nombre
                                Supuesto</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="nombre_supuesto" value=""
                                    placeholder="(opcional)" name="nombre_supuesto">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="alias">Alias</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="alias" value=""
                                    placeholder="(opcional)" name="alias" required>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" class="btn btn-primary w-sm">
                        <i class="ri-save-3-line align-middle me-1"></i> Guardar
                    </button>
                </div>
            </div>
            <!-- end col -->

            <div class="col-lg-6">
                <div class="card d-none">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Publish</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="choices-publish-status-input" class="form-label">Status</label>

                            <select class="form-select" id="choices-publish-status-input" data-choices
                                data-choices-search-false>
                                <option value="Published" selected>Published</option>
                                <option value="Scheduled">Scheduled</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>

                        <div>
                            <label for="choices-publish-visibility-input" class="form-label">Visibility</label>
                            <select class="form-select" id="choices-publish-visibility-input" data-choices
                                data-choices-search-false>
                                <option value="Public" selected>Public</option>
                                <option value="Hidden">Hidden</option>
                            </select>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header d-none">
                        <h5 class="card-title mb-0">Publish Schedule</h5>
                    </div>
                    <!-- end card body -->
                    <div class="card-body">
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="especialidad">Especialidad</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="especialidad" value=""
                                    placeholder="(opcional)" name="especialidad" required>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="id_pais">Nacionalidad</label>
                            <div class="col-md-9">
                                <select name="id_pais" id="id_pais" class="form-select"></select>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="lugar_nacimiento">Lugar de
                                Nacimiento</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="lugar_nacimiento" value=""
                                    placeholder="(opcional)" name="lugar_nacimiento">
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="fecha_nacimiento">Fecha de
                                Nacimiento</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" id="fecha_nacimiento" value=""
                                    placeholder="(opcional)" name="fecha_nacimiento" max="{{ now()->toDateString() }}">
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="edad_aproximada">
                                Edad aproximada
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="edad_aproximada" name="edad_aproximada"
                                    value="" placeholder="(opcional)">
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="fecha_nacimiento">Genero</label>
                            <div class="col-md-9">
                                <div class="form-check mb-2 form-check-inline">
                                    <input class="form-check-input" type="radio" name="genero" value="MASCULINO"
                                        id="genero1" required>
                                    <label class="form-check-label" for="genero1">
                                        Masculino
                                    </label>
                                </div>
                                <div class="form-check mb-2 form-check-inline">
                                    <input class="form-check-input" type="radio" name="genero" value="FEMENINO"
                                        id="genero2" required>
                                    <label class="form-check-label" for="genero2">
                                        Femenino
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="estado_civil">Estado civil</label>
                            <div class="col-md-9">
                                {{-- 'SOLTERO','CASADO','DIVORCIADO','VIUDO','CONYUGUE' --}}
                                <select name="estado_civil" id="estado_civil" class="form-select" {{-- data-choices data-choices-search-false --}}>
                                    <option value="">Seleccionar estado civil</option>
                                    <option value="SOLTERO">Soltero</option>
                                    <option value="CASADO">Casado</option>
                                    <option value="DIVORCIADO">Divorciado</option>
                                    <option value="VIUDO">Viudo</option>
                                    <option value="CONYUGUE">Conyugue</option>
                                </select>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="nombre_conyuge">
                                Nombre de l a conyuge
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="nombre_conyuge" name="nombre_conyuge"
                                    value="" placeholder="(si correspondiera)">
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="domicilio">Domicilio</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="domicilio" name="domicilio"
                                    value="" placeholder="(opcional)">
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="id_division">División</label>
                            <div class="col-md-9">
                                {{-- 'SOLTERO','CASADO','DIVORCIADO','VIUDO','CONYUGUE' --}}
                                <select name="id_division" id="id_division" class="form-select">

                                </select>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="ocupacion">Ocupación</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ocupacion" name="ocupacion"
                                    value="" placeholder="(opcional)">
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="rasgos">Rasgos</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="rasgos" name="rasgos" value=""
                                    placeholder="(opcional)">
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="modus_operandi">Modus Operandi</label>
                            <div class="col-md-9">
                                <textarea name="modus_operandi" class="form-control" id="modus_operandi" rows="3"></textarea>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="zonas_opera">Zonas que Operan</label>
                            <div class="col-md-9">
                                <textarea name="zonas_opera" class="form-control" id="zonas_opera" rows="3"></textarea>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="form-label col-md-3 col-form-label" for="observaciones">Observaciones</label>
                            <div class="col-md-9">
                                <textarea name="observaciones" class="form-control" id="observaciones" rows="3"></textarea>
                                <div class="invalid-feedback">Este campo es obligatorio.</div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

    </form>
@endsection


@section('css')
    <link href="{{ url('/assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="/assets/libs/dropzone/dropzone.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        /* Contenedor principal de las sugerencias */
        .autocomplete-suggestions {
            border: 1px solid #999;
            background: #FFF;
            overflow: auto;
            cursor: default;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            /* Sombra para darle profundidad */
            margin-top: 2px;
        }

        /* Cada ítem de sugerencia */
        .autocomplete-suggestion {
            padding: 10px 15px;
            white-space: nowrap;
            overflow: hidden;
            font-size: 14px;
            color: #333;
        }

        /* Estilo cuando pasas el mouse o navegas con las flechas */
        .autocomplete-selected {
            background: #F0F0F0;
            cursor: pointer;
        }

        /* Estilo para el texto que coincide con lo que escribes (opcional) */
        .autocomplete-suggestions strong {
            font-weight: bold;
            color: #007bff;
            /* Color azul para resaltar la coincidencia */
        }

        /* Estilo cuando no hay resultados (si lo tienes configurado) */
        .autocomplete-no-suggestion {
            padding: 10px 15px;
            color: #999;
            font-style: italic;
        }
    </style>

@endsection
@section('js')
    <script src="/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>
    <script src="{{ url('/assets/js/select2.min.js') }}"></script>
    <script src="{{ url('/assets/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ url('/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ url('/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
    </script>
    <script
        src="{{ url('/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}">
    </script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

    <script src="{{ url('/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>
    <!-- dropzone js -->
    <script src="{{ url('/assets/libs/dropzone/dropzone-min.js') }}"></script>

    <script>
        // document.querySelector("#product-image-input").addEventListener("change", function() {
        //     var preview = document.querySelector("#product-img");
        //     var file = document.querySelector("#product-image-input").files[0];
        //     var reader = new FileReader();
        //     reader.addEventListener("load", function() {
        //         preview.src = reader.result;
        //     }, false);
        //     if (file) {
        //         reader.readAsDataURL(file);
        //     }
        // });


        const imageInputElements = document.querySelectorAll("[data-image-input]");
        imageInputElements.forEach(function(imageInputElement) {
            imageInputElement.addEventListener("change", function() {
                var preview = document.querySelector("[data-image-input-preview='" + this.id + "']");
                var file = this.files[0];
                var reader = new FileReader();
                reader.addEventListener("load", function() {
                    preview.src = reader.result;
                }, false);
                if (file) {
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/devbridge-autocomplete@1.5.0/dist/jquery.autocomplete.min.js"></script>

    {{-- <script src="/assets/js/pages/ecommerce-product-create.init.js"></script> --}}
@endsection
