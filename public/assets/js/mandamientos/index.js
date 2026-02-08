/**
 * DataTable para Mandamientos
 * Sistema de Gestión de Mandamientos de Aprehensión - FELCC
 */


(function () {
    'use strict';

    // Variable global para la tabla
    let mandamientosTable;

    $(document).on('click', '.image-popup-zoom', function (e) {
        e.preventDefault();
        const imagenes = $(this).data('img');

        if (imagenes.length > 0) {
            const lightbox = new FsLightbox();
            lightbox.props.sources = imagenes.map(img => '/storage/' + img);
            lightbox.open();
        }
    });

    let dataScroll = {
        'page': 1,
        'size': 25,
        'search': '',
        // '_token': crfToken,
        'id_bloque': $('#id_bloque').val() || null,
    }

    function getDataFilter() {

        dataScroll.tipo_persona = $("#filtroSelectTipoPersona").val();
        dataScroll.estado_persona = $("#filtroEstadoPersona").val();

        dataScroll.search = $("#inputBuscarPersona").val();

        return dataScroll;
    }

    let scrollPersonal = $('#listadoMandamientos').scrollPagination({
        'url': '/mandamientos', // the url you are fetching the results
        'method': 'get',
        'data': getDataFilter(),
        'dataTemplateCallback': rowHtml,
        'elementCountSelector': '#contadorListaMandamientos',
        'elementCountTemplate': '<span  class=""> Listando <b> {count}  </b>elementos de <b> {total} </b> encontrados </span>',
        'loading': '#loadingMandamientos',
        'scroller': "#containerListaMandamientos",
        'loadingText': `<div  class=" text-center"><span class="loaderHttp"></span><span class="text-muted">Cargando...</span></div>`,
        'loadingNomoreText': '<h6 class="text-danger">No se encontraron más Resultados</h6>',

    });


    function rowHtml(item, opacity = 0) {

        let html =/*html*/ `
                <div class="candidate-item mb-3 col-xxl-4 col-md-6" style='opacity:${opacity};-moz-opacity: ${opacity};filter: alpha(opacity=${opacity});'>
                    <div class="card h-100">
                        <div class="card-body">
                            <!-- Vista GRID -->
                            <div class="grid-view-content">
                                <div class="d-sm-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xxl rounded">

                                            <img src="${item.imagenes_persona ? ('/storage/'+primeraImagen(item.imagenes_persona)) : '/assets/img/user-dummy-img.jpg'}" alt="imagen de la persona"
                                                class="member-img img-fluid d-block rounded ${item.imagenes_persona ? 'cursor-pointer image-popup-zoom' : ''} " data-img='${item.imagenes_persona}'>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <a href="pages-profile">
                                            <h5 class="fs-16 mb-1">${item.nombre_completo}</h5>
                                        </a>
                                        <p class="text-muted mb-2">Web Designer</p>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <div class="badge text-bg-success"><i class="mdi mdi-star me-1"></i>4.2</div>
                                            <div class="text-muted">2.2k Ratings</div>
                                        </div>
                                        <div class="d-flex gap-4 mt-2 text-muted">
                                            <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> Cullera, Spain</div>
                                            <div><i class="ri-time-line text-primary me-1 align-bottom"></i><span
                                                    class="badge badge-soft-danger">Part Time</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Vista LISTA -->
                            <div class="list-view-content" style="display: none;">
                                <div class="d-sm-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-lg rounded"><img src="${item.imagenes_persona ? ('/storage/'+primeraImagen(item.imagenes_persona)) : '/assets/img/user-dummy-img.jpg'}" alt=""
                                                class="member-img img-fluid d-block rounded ${item.imagenes_persona ? 'cursor-pointer image-popup-zoom' : ''} " data-img='${item.imagenes_persona}'>
                                            </div>
                                    </div>
                                    <div class="flex-grow-1 ms-md-3 mt-3 mt-md-0 d-md-flex align-items-center">
                                        <div class="ms-lg-3 my-3 my-lg-0">
                                            <a href="pages-profile">
                                                <h5 class="fs-16 mb-2">Tonya Noble</h5>
                                            </a>
                                            <p class="text-muted mb-0">Web Designer</p>
                                        </div>
                                        <div class="d-flex gap-4 mt-0 text-muted mx-auto">
                                            <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> Cullera, Spain</div>
                                            <div><i class="ri-time-line text-primary me-1 align-bottom"></i> <span
                                                    class="badge badge-soft-danger">Part Time</span></div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 align-items-center mx-auto my-3 my-lg-0">
                                            <div class="badge text-bg-success"><i class="mdi mdi-star me-1"></i>4.2</div>
                                            <div class="text-muted">2.2k Ratings</div>
                                        </div>
                                        <div>
                                            <a href="#!" class="btn btn-soft-success">View Details</a>
                                            <a href="#!" class="btn btn-ghost-danger btn-icon custom-toggle active"
                                                data-bs-toggle="button">
                                                <span class="icon-on"><i class="ri-bookmark-line align-bottom"></i></span>
                                                <span class="icon-off"><i class="ri-bookmark-3-fill align-bottom"></i></span>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        `;

        return html;


        let html2 =/*html*/ `<tr data-id="${item.id_persona}" style='opacity:${opacity};-moz-opacity: ${opacity};filter: alpha(opacity=${opacity});'>

            <td class="nombre">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                        ${item.foto ? `<a class="image-popup cursor-pointer  "> <img src="${baseUrl}/storage/${item.foto}" alt="" class="avatar-lg  rounded"> </a>` : `<img src="${baseUrl}/assets/images/users/user-dummy-img.jpg" alt="" class="avatar-lg  ">`}
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fs-14 mb-1">
                        <a class="flex-grow-1 btn-update-photo" href="javascript:void(0)" data-persona="${item.id_persona}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Actualizar Foto">
                            <i class="ri-camera-fill align-bottom"></i>

                        </a>
                            <a href="javascript:void(0)" class="text-dark nombre">${item.nombre} ${item.paterno || ""} ${item.materno || ""} </a>

                        </h5>
                        <p class="text-muted mb-0">C.I.: <span class="fw-medium"> ${(item.numero_documento)} </span></p>
                        <p class="text-muted mb-0">Celular: <span class="fw-medium"> ${item.celular || "-"} </span></p>
                        <p class="text-muted mb-0"> <span class="fw-medium"> ${item.tipo_persona || "-"} </span></p>
                    </div>
                </div>

            </td>
            <td class="ci">
                ${item.numero_documento || ""}
            </td>
            <td class="celular">
                ${item.celular || ""}
            </td>
            <td class="celular">
                ${item.nombre_grupo || ""}
            </td>
            <td>
                <ul class="list-inline hstack gap-2 mb-0">
                    <li class="list-inline-item edit" >
                        <a href="javascript:void(0);" class="text-muted hover-info d-inline-block view-item-btn" tooltip="tooltip" data-bs-placement="top" title="Ver Detalles">
                            <i class="ri-eye-line fs-16"></i>
                        </a>
                    </li>
                    <li class="list-inline-item edit" >
                        <a href="javascript:void(0);" class="text-muted hover-warning d-inline-block edit-item-btn" tooltip="tooltip" data-bs-placement="top" title="Editar Persona">
                            <i class="ri-pencil-line fs-16"></i>
                        </a>
                    </li>
                    <li class="list-inline-item edit " >
                        <a href="javascript:void(0);" class="text-muted hover-danger d-inline-block remove-item-btn" tooltip="tooltip" data-bs-placement="top" title="Eliminar Persona">
                            <i class="ri-delete-bin-2-line fs-16"></i>
                        </a>
                    </li>
                </ul>
                <ul class="list-inline hstack gap-2 mb-0">
                    <li class="list-inline-item edit" >
                        <a href="javascript:void(0);" class="text-success hover-secondary d-inline-block card-btn" tooltip="tooltip" data-bs-placement="top" title="Credencial">
                            <i class=" mdi mdi-card-account-details-star-outline mdi-20px"></i>
                        </a>
                    </li>

                </ul>

            </td>
        </tr>`;


        return html;

    }


    /**
     * Inicializar botones de eliminación
     */
    function initDeleteButtons() {
        $('.btn-delete').off('click').on('click', function () {
            deleteId = $(this).data('id');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    }




    /**
     * Mostrar alertas con SweetAlert2
     */
    function showAlert(message, type) {
        const config = {
            title: type === 'success' ? '¡Éxito!' : '¡Error!',
            text: message,
            icon: type,
            confirmButtonText: 'Aceptar',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
            showCloseButton: true
        };

        Swal.fire(config);
    }

    /**
     * Función para recargar la tabla (puede ser llamada desde fuera)
     */
    window.reloadMandamientosTable = function () {
        if (mandamientosTable) {
            mandamientosTable.ajax.reload(null, false);
        }
    };


    $(document).on('submit', '#mandamientoForm', function (e) {
        e.preventDefault();

        const datos = new FormData(this);

        const form = $(this);

        datos.append('_token', $('meta[name="csrf-token"]').attr('content'));


        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: datos,
            processData: false,
            contentType: false
        }).done(function (response) {
            console.log(response);

            if (response.success) {

                // showAlert(response.message || 'Mandamiento guardado correctamente', 'success');
                // $('#miModal').modal('hide');
                // window.reloadMandamientosTable();
            } else {
                showAlert(response.message || 'Error al guardar el mandamiento', 'error');
            }
        }).fail(function (xhr) {
            console.error('Error:', xhr);
            showAlert('Error al guardar el mandamiento', 'error');
        });


    });


    $(document).on('click', '.openModal', function (e) {
        e.preventDefault();

        const url = $(this).val();
        const miModal = new bootstrap.Modal(document.getElementById('miModal'));
        miModal.show();


    })


    /* cargar datos parametricos tipo mandamiento */

    let tiposMandamientos = [];

    function cargarTiposMandamientos() {
        $.get('/tipos-mandamientos')
            .done(function (data) {
                tiposMandamientos = data;
                inicializarSelectTipoMandamiento();

            })
            .fail(function (error) {

            });
    }

    cargarTiposMandamientos();


    function inicializarSelectTipoMandamiento() {

        $('#id_tipo_mandamiento').select2({
            dropdownParent: $('#miModal'),
            placeholder: 'Seleccione un tipo de mandamiento',
            allowClear: true,
            width: '100%',
            tags: true,
            data: tiposMandamientos.map(function (tipo) {
                return {
                    id: tipo.id,
                    text: tipo.tipo_mandamiento
                };
            }),
            createTag: function (params) {
                const term = params.term.trim();
                if (term === '') {
                    return null;
                }
                // Verificar si ya existe
                const existe = tiposMandamientos.some(function (tipo) {
                    return tipo.tipo_mandamiento.toUpperCase() === term.toUpperCase();
                });
                if (existe) {
                    return null;
                }
                return {
                    id: 'new:' + term.toUpperCase(),
                    text: term.toUpperCase() + ' (Añadir nuevo)',
                    newTag: true
                };
            }
        }).on('select2:select', function (e) {
            const data = e.params.data;
            if (data.newTag) {
                const nuevoTipo = data.text.replace(' (Añadir nuevo)', '');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/tipos-mandamientos',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        tipo_mandamiento: nuevoTipo
                    },
                    success: function (response) {
                        if (response.success || response.id) {
                            const newId = response.id || response.data.id;
                            const $select = $('#id_tipo_mandamiento');

                            // Eliminar la opción temporal con el prefijo 'new:'
                            $select.find('option[value="new:' + nuevoTipo + '"]').remove();

                            // Verificar si ya existe una opción con ese ID para evitar duplicados
                            if ($select.find('option[value="' + newId + '"]').length === 0) {
                                let nuevoOption = new Option(nuevoTipo, newId, true, true);
                                $select.append(nuevoOption);
                            }

                            $select.val(newId).trigger('change');

                            // Agregar al array local solo si no existe
                            const yaExiste = tiposMandamientos.some(function (tipo) {
                                return tipo.id === newId;
                            });

                            if (!yaExiste) {
                                tiposMandamientos.push({
                                    id: newId,
                                    tipo_mandamiento: nuevoTipo
                                });
                            }

                            showAlert('Tipo de mandamiento agregado correctamente', 'success');
                        } else {
                            showAlert(response.message || 'Error al agregar el tipo de mandamiento', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        showAlert('Error al agregar el tipo de mandamiento', 'error');
                        // Limpiar la selección si falla
                        $('#id_tipo_mandamiento').val(null).trigger('change');
                    }
                });
            }
        });
    }


    let tiposDelitos = [];

    function cargarTiposDelitos() {
        $.get('/delitos')
            .done(function (data) {
                tiposDelitos = data;

                inicializarSelectDelito();

            })
            .fail(function (error) {

            });
    }

    cargarTiposDelitos();

    function inicializarSelectDelito() {

        $('#id_delito').select2({
            dropdownParent: $('#miModal'),
            placeholder: 'Seleccione un delito',
            allowClear: true,
            width: '100%',
            tags: true,
            data: tiposDelitos.map(function (delito) {
                return {
                    id: delito.id,
                    text: delito.nombre_delito
                };
            }),
            createTag: function (params) {
                const term = params.term.trim();
                if (term === '') {
                    return null;
                }
                // Verificar si ya existe
                const existe = tiposDelitos.some(function (delito) {
                    return delito.nombre_delito.toUpperCase() === term.toUpperCase();
                });
                if (existe) {
                    return null;
                }
                return {
                    id: 'new:' + term.toUpperCase(),
                    text: term.toUpperCase() + ' (añadir nuevo)',
                    newTag: true
                };
            }
        }).on('select2:select', function (e) {
            const data = e.params.data;
            if (data.newTag) {
                const nuevoDelito = data.text.replace(' (añadir nuevo)', '');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/delitos',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        nombre_delito: nuevoDelito
                    },
                    success: function (response) {
                        if (response.success || response.id) {
                            const newId = response.id || response.data.id;
                            const $select = $('#id_delito');

                            // Eliminar la opción temporal con el prefijo 'new:'
                            $select.find('option[value="new:' + nuevoDelito + '"]').remove();

                            // Verificar si ya existe una opción con ese ID para evitar duplicados
                            if ($select.find('option[value="' + newId + '"]').length === 0) {
                                let nuevoOption = new Option(nuevoDelito, newId, true, true);
                                $select.append(nuevoOption);
                            }

                            $select.val(newId).trigger('change');

                            // Agregar al array local solo si no existe
                            const yaExiste = tiposDelitos.some(function (delito) {
                                return delito.id === newId;
                            });

                            if (!yaExiste) {
                                tiposDelitos.push({
                                    id: newId,
                                    nombre_delito: nuevoDelito
                                });
                            }

                            showAlert('Delito agregado correctamente', 'success');
                        } else {
                            showAlert(response.message || 'Error al agregar el delito', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        showAlert('Error al agregar el delito', 'error');
                        // Limpiar la selección si falla
                        $('#id_delito').val(null).trigger('change');
                    }
                });
            }

        });
    }


    let juzgados = [];

    function cargarJuzgados() {
        $.get('/juzgados')
            .done(function (data) {
                juzgados = data;
                inicializarSelectJuzgado();
            })
            .fail(function (error) {

            });
    }

    cargarJuzgados();

    function inicializarSelectJuzgado() {

        $('#id_juzgado').select2({
            dropdownParent: $('#miModal'),
            placeholder: 'Seleccione un juzgado',
            allowClear: true,
            width: '100%',
            tags: true,
            data: juzgados.map(function (juzgado) {
                return {
                    id: juzgado.id,
                    text: juzgado.nombre_juzgado
                };
            }),
            createTag: function (params) {
                const term = params.term.trim();
                if (term === '') {
                    return null;
                }
                // Verificar si ya existe
                const existe = juzgados.some(function (juzgado) {
                    return juzgado.nombre_juzgado.toUpperCase() === term.toUpperCase();
                });
                if (existe) {
                    return null;
                }
                return {
                    id: 'new:' + term.toUpperCase(),
                    text: term.toUpperCase() + ' (añadir nuevo)',
                    newTag: true
                };
            }
        }).on('select2:select', function (e) {
            const data = e.params.data;
            if (data.newTag) {
                const nuevoJuzgado = data.text.replace(' (añadir nuevo)', '');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/juzgados',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        nombre_juzgado: nuevoJuzgado
                    },
                    success: function (response) {
                        if (response.success || response.id) {
                            const newId = response.id || response.data.id;
                            const $select = $('#id_juzgado');

                            // Eliminar la opción temporal con el prefijo 'new:'
                            $select.find('option[value="new:' + nuevoJuzgado + '"]').remove();

                            // Verificar si ya existe una opción con ese ID para evitar duplicados
                            if ($select.find('option[value="' + newId + '"]').length === 0) {
                                let nuevoOption = new Option(nuevoJuzgado, newId, true, true);
                                $select.append(nuevoOption);
                            }

                            $select.val(newId).trigger('change');

                            // Agregar al array local solo si no existe
                            const yaExiste = juzgados.some(function (juzgado) {
                                return juzgado.id === newId;
                            });

                            if (!yaExiste) {
                                juzgados.push({
                                    id: newId,
                                    nombre_juzgado: nuevoJuzgado
                                });
                            }

                            showAlert('Juzgado agregado correctamente', 'success');
                        } else {
                            showAlert(response.message || 'Error al agregar el juzgado', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        showAlert('Error al agregar el juzgado', 'error');
                        // Limpiar la selección si falla
                        $('#id_juzgado').val(null).trigger('change');
                    }
                });
            }
        });
    }



    inicializarSelectPersona();
    function inicializarSelectPersona() {
        $('#id_persona').select2({
            dropdownParent: $('#miModal'),
            placeholder: 'Seleccione una persona',
            allowClear: true,
            ajax: {
                url: '/personas-search',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // término de búsqueda
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (persona) {
                            return {
                                id: persona.id,
                                text: `${persona.nombre} ${persona.paterno || ""} ${persona.materno || ""} - CI: ${persona.ci || ""}`
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });

    }

    FilePond.registerPlugin(
        // encodes the file as base64 data
        FilePondPluginFileEncode,
        // validates the size of the file
        FilePondPluginFileValidateSize,
        // corrects mobile image orientation
        FilePondPluginImageExifOrientation,
        // previews dropped images
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
    );


    $("#btnPersona").on('click', function (e) {
        e.preventDefault();

        let swalInstance = Swal.fire({
            title: 'Añadir Nueva Persona',
            html: /*html */`
                <form id="swal-persona-form" enctype="multipart/form-data" action="#" method="POST">
                    <input type="text" id="swal-ci" name="ci" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Cédula de Identidad (opcional)" >
                    <input type="text" id="swal-nombre" name="nombre" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Nombre" required>
                    <input type="text" id="swal-paterno" name="paterno" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Apellido Paterno" required>
                    <input type="text" id="swal-materno" name="materno" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Apellido Materno (opcional)" >
                    <input type="number" id="swal-celular" name="celular" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Número de Celular (opcional)" >
                    <input type="date" id="swal-fecha_nacimiento" name="fecha_nacimiento" class="form-control form-control-sm mb-2" placeholder="Fecha de Nacimiento (opcional)">
                    <textarea id="swal-direccion" name="direccion" class="form-control form-control-sm mb-2" rows="3" placeholder="Dirección (opcional)"></textarea>
                    <input type="file" id="swal-foto" name="fotos" class="form-control form-control-sm mb-2" multiple accept="image/*" data-allow-reorder="true" data-max-file-size="3MB" data-max-files="3">

                </form>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            allowOutsideClick: false,   // 👈 clave
            allowEscapeKey: false,      // opcional (tecla ESC)
            allowEnterKey: false,       // opcional (ENTER)
            didOpen: () => {

                const inputElement = document.querySelector('#swal-foto');
                const confirmButton = Swal.getConfirmButton();
                FilePond.create(inputElement, {
                    storeAsFile: false,
                    allowMultiple: true,
                    labelIdle: 'Arrastra y suelta tu foto o <span class="filepond--label-action">Selecciona</span>',
                    imagePreviewHeight: 140,
                    // imageCropAspectRatio: '1:1',
                    //limitar a 3 imagenes
                    acceptedFileTypes: ['image/*'],
                    labelFileTypeNotAllowed: 'Archivo no válido',
                    labelMaxFilesExceeded: 'Demasiados archivos',
                    labelIdle: 'Arrastra o Suba hasta 3 imágenes (opcional)<br> <span class="filepond--label-action">Seleccionar</span>',
                    // data: {
                    //     type: 'avatar'
                    // },
                    // tamaño máximo de archivo 3MB
                    allowFileSizeValidation: true,
                    maxFileSize: '2MB',
                    labelMaxFileSize: 'Tamaño máximo de archivo es {filesize}',
                    labelMaxFileSizeExceeded: 'Archivo demasiado grande',
                    imageResizeTargetWidth: 200,
                    imageResizeTargetHeight: 200,
                    stylePanelLayout: 'compact stacked',
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                    //solo permitir imagenes
                    acceptedFileTypes: ['image/*'],
                    labelFileTypeNotAllowed: 'Archivo no válido. Solo se permiten imágenes.',
                    onaddfilestart: () => {
                        confirmButton.disabled = true;
                        confirmButton.innerText = 'Procesando...';
                    },

                    // 2. Cuando el archivo termina de cargarse/procesarse con éxito
                    onprocessfile: (error) => {
                        if (!error) {
                            confirmButton.disabled = false;
                            confirmButton.innerText = 'Guardar';
                        }
                    },
                    onremovefile: () => {
                        const currentFiles = FilePond.find(document.querySelector('#swal-foto')).getFiles();
                        // Si no hay archivos procesándose actualmente, rehabilitar
                        const isProcessing = currentFiles.some(f => f.status === 2 || f.status === 3);
                        if (!isProcessing) {
                            confirmButton.disabled = false;
                            confirmButton.innerText = 'Guardar';
                        }
                    },

                    // 4. En caso de error en la carga del archivo
                    onaddfile: (error, file) => {
                        // Verificamos si hay archivos pendientes de carga técnica en el pool
                        const isBusy = FilePond.find(document.querySelector('#swal-foto')).getFiles().some(f => f.status !== 2 && f.status !== 5);

                        if (!isBusy) {
                            confirmButton.disabled = false;
                            confirmButton.innerText = 'Guardar';
                        }
                    },
                });
            },
            preConfirm: async () => { // 1. Agregamos async

                const formulario = document.getElementById('swal-persona-form');
                if (!formulario.checkValidity()) {
                    formulario.classList.add('was-validated');
                    Swal.showValidationMessage('<span class="text-danger">Por favor, complete todos los campos obligatorios.</span>');
                    return false;
                }


                let formData = new FormData(document.getElementById('swal-persona-form'));
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Obtener archivos de FilePond
                let pond = FilePond.find(document.querySelector('#swal-foto'));
                let files = pond.getFiles().map(fileItem => fileItem.file);
                files.forEach((file) => formData.append('fotos[]', file));

                // deshabilitar el botón de confirmación mientras se procesa la solicitud y mostrar un texto de carga
                // const confirmButton = Swal.getConfirmButton();
                // confirmButton.disabled = true;
                // confirmButton.innerText = 'Guardando...';
                Swal.showLoading();



                try {
                    // 2. Envolvemos el AJAX en una Promesa y usamos await
                    const response = await new Promise((resolve, reject) => {
                        $.ajax({
                            url: '/personas',
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': token },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: (res) => resolve(res),
                            error: (err) => reject(err)
                        });
                    });

                    Swal.hideLoading();

                    console.log('Servidor respondió:', response);

                    // 3. Si todo salió bien, puedes mostrar el éxito dentro del mismo modal
                    Swal.update({
                        icon: 'success',
                        title: 'Persona agregada',
                        html: 'La persona se guardó correctamente.',
                        showConfirmButton: false,
                        confirmButtonText: 'Aceptar'
                    });
                    // cerrar popup de agregar persona

                    // agregar la nueva persona al select del formulario principal
                    const newOption = new Option(`${response.data.nombre} ${response.data.paterno} ${response.data.materno || ''} - ${response.data.ci || ''}`, response.data.id, true, true);
                    $('#id_persona').append(newOption).trigger('change');

                    setTimeout(() => {
                        Swal.close();
                    }, 1500);

                    // IMPORTANTE: retornamos false para que el modal NO se cierre
                    // hasta que el usuario haga clic en el nuevo botón "Aceptar"
                    return false;

                } catch (xhr) {
                    // Si hay error (422, 500, etc), el modal se queda abierto y muestra el error
                    const msg = xhr.responseJSON?.message || 'Error al guardar';
                    Swal.showValidationMessage(` <span class="text-danger">Error: ${msg}</span>`);
                    return false;
                }

            },

            // prevenir que el modal se cierre automáticamente para mostrar la alerta personalizada


        }).then((result) => {
            if (result.isConfirmed) {
                // prevenir que el swal se cierre automáticamente para mostrar la alerta personalizada


                // swalInstance.showLoading();
            }
        });


    })

})();
