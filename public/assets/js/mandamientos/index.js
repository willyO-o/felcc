/**
 * DataTable para Mandamientos
 * Sistema de Gestión de Mandamientos de Aprehensión - FELCC
 */


(function () {
    'use strict';

    const coloresEstados = {
        "PENDIENTE": "info",
        "EJECUTADO": "success",
        "CANCELADO": "danger",

    }

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

        $(this).removeData('img'); // Evitar que se vuelva a abrir el lightbox con la misma imagen
    });
    $(document).on('click', '.btn-ver-img', function (e) {
        e.preventDefault();
        const imagen = $(this).data('img');

        const lightbox = new FsLightbox();
        lightbox.props.sources = ['/storage/' + imagen];
        lightbox.open();

        $(this).removeData('img'); // Evitar que se vuelva a abrir el lightbox con la misma imagen
    });

    let dataScroll = {
        'page': 1,
        'size': 6,
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
        // Obtener el modo de vista desde localStorage
        const savedView = localStorage.getItem('mandamientosViewMode') || 'grid';

        // Definir clases y estilos según el modo
        const isGridMode = savedView === 'grid';
        const itemClasses = isGridMode ? 'candidate-item mb-3 col-xxl-4 col-md-6' : 'candidate-item col-lg-12';
        const gridDisplay = isGridMode ? 'block' : 'none';
        const listDisplay = isGridMode ? 'none' : 'block';
        const cardClasses = isGridMode ? 'card h-100' : 'card h-100 mb-0';

        let html =/*html*/ `
                <div data-id="${item.id}" class="${itemClasses}" style='opacity:${opacity};-moz-opacity: ${opacity};filter: alpha(opacity=${opacity});'>
                    <div class="${cardClasses}">
                        <div class="card-header border-0 pb-0 pt-3 align-items-center d-sm-flex">
                            <h4 class="card-title mb-0 flex-grow-1 hr-label">HR: ${item.hoja_ruta || "-"} </h4>
                            <div class="mt-2 mt-sm-0">
                                <button type="button" value="${item.id}" class="btn btn-soft-secondary btn-sm shadow-none verDetalles" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalles">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <button type="button" value="${item.id}"  class="btn btn-soft-secondary btn-sm shadow-none openModal" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Mandamiento">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm shadow-none btnDelete" value="${item.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar Mandamiento">
                                    <i class="ri-delete-bin-2-line"></i>
                                </button>
                                ${item.ruta ? `
                                    <button type="button" class="btn btn-soft-secondary btn-sm shadow-none btn-ver-img" data-img='${item.ruta}' data-bs-toggle="tooltip" data-bs-placement="top" title="Ver imagen mandamiento">
                                        <i class="ri-image-line"></i>
                                    </button>` : ''}
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Vista GRID -->
                            <div class="grid-view-content" style="display: ${gridDisplay};">
                                <div class="d-sm-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xxl rounded">

                                            <img src="${item.imagenes_persona ? ('/storage/' + primeraImagen(item.imagenes_persona)) : '/assets/img/user-dummy-img.jpg'}" alt="imagen de la persona"
                                                class="member-img img-fluid d-block rounded ${item.imagenes_persona ? 'cursor-pointer image-popup-zoom' : ''} " data-img='${item.imagenes_persona}'>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <a href="pages-profile">
                                            <h5 class="fs-16 mb-1">${item.nombre_completo}</h5>
                                            <h6 class="text-muted mb-2">C.I.: <strong>${item.ci || "-"}</strong></h6>
                                        </a>
                                        <p class="text-muted mb-2"> Delito: <strong>${item.nombre_delito || "-"}</strong></p>
                                        <p class="text-muted mb-2"> <strong>${item.tipo_mandamiento || "-"}</strong></p>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">Estado:
                                            <div class="badge text-bg-${coloresEstados[item.estado] || 'secondary'}">${item.estado}</div>
                                            <!-- <div class="text-muted">2.2k Ratings</div> -->
                                        </div>
                                        <div class=" gap-4 mt-2 text-muted">
                                            <div><i class="ri-scales-line text-primary me-1 align-bottom"></i> ${item.nombre_juzgado || ""}</div>
                                            <!-- <div><i class="ri-time-line text-primary me-1 align-bottom"></i><span
                                                    class="badge badge-soft-danger">Part Time</span></div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Vista LISTA -->
                            <div class="list-view-content" style="display: ${listDisplay};">
                                <div class="d-sm-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-lg height-auto rounded"><img src="${item.imagenes_persona ? ('/storage/' + primeraImagen(item.imagenes_persona)) : '/assets/img/user-dummy-img.jpg'}" alt=""
                                                class="member-img img-fluid d-block rounded ${item.imagenes_persona ? 'cursor-pointer image-popup-zoom' : ''} " data-img='${item.imagenes_persona}'>
                                            </div>
                                    </div>
                                    <div class="flex-grow-1 ms-md-3 mt-3 mt-md-0 d-md-flex align-items-center">
                                        <div class="ms-lg-3 my-3 my-lg-0">
                                            <a href="pages-profile">
                                                <h5 class="fs-16 mb-2">${item.nombre_completo}</h5>
                                                <h6 class="text-muted mb-2">C.I.: <strong>${item.ci || "-"}</strong></h6>

                                            </a>
                                            <p class="text-muted mb-2"> Delito: <strong>${item.nombre_delito || "-"}</strong></p>
                                            <!-- <p class="text-muted mb-0">${item.tipo_mandamiento || "-"}</p> -->
                                        </div>
                                        <div class="d-flex gap-4 mt-0 text-muted mx-auto">
                                            <div><i class="ri-auction-line text-primary me-1 align-bottom"></i> ${item.tipo_mandamiento || "-"}</div>
                                            <!-- <div><i class="ri-map-pin-2-line text-primary me-1 align-bottom"></i> Cullera, Spain</div>
                                            <div><i class="ri-time-line text-primary me-1 align-bottom"></i> <span
                                                    class="badge badge-soft-danger">Part Time</span></div> -->
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 align-items-center mx-auto my-3 my-lg-0">
                                            <!-- <div class="badge text-bg-success"><i class="mdi mdi-star me-1"></i>4.2</div>
                                            <div class="text-muted">2.2k Ratings</div> -->
                                            <div><i class="ri-scales-line text-primary me-1 align-bottom"></i> ${item.nombre_juzgado || ""}</div>
                                        </div>
                                        <div>
                                            Estado:
                                            <div class="badge text-bg-${coloresEstados[item.estado] || 'secondary'}"> ${item.estado}</div>
                                            <!-- <div class="text-muted">${item.estado}</div> -->
                                            <!-- <a href="#!" class="btn btn-soft-success">View Details</a>
                                            <a href="#!" class="btn btn-ghost-danger btn-icon custom-toggle active"
                                                data-bs-toggle="button">
                                                <span class="icon-on"><i class="ri-bookmark-line align-bottom"></i></span>
                                                <span class="icon-off"><i class="ri-bookmark-3-fill align-bottom"></i></span>
                                            </a> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        `;

        return html;
    }


    const btnGridView = document.getElementById('btn-grid-view');
    const btnListView = document.getElementById('btn-list-view');
    const candidateList = document.getElementById('listadoMandamientos');

    // Función para aplicar la vista
    function applyView(viewMode) {
        if (!candidateList) return;

        if (viewMode === 'grid') {
            // Cambiar a vista Grid
            candidateList.classList.remove('list-view-mode');
            candidateList.classList.add('grid-view-mode');

            // Cambiar clases de columnas
            document.querySelectorAll('.candidate-item').forEach(item => {
                item.className = 'candidate-item mb-3 col-xxl-4 col-md-6';
            });

            // Mostrar/ocultar contenido
            document.querySelectorAll('.grid-view-content').forEach(el => el.style.display = 'block');
            document.querySelectorAll('.list-view-content').forEach(el => el.style.display = 'none');

            // Remover mb-0 de los cards en vista grid
            document.querySelectorAll('.candidate-item .card').forEach(card => {
                card.classList.remove('mb-0');
            });

            // Cambiar botones activos
            if (btnGridView && btnListView) {
                btnGridView.classList.add('active');
                btnListView.classList.remove('active');
            }
        } else if (viewMode === 'list') {
            // Cambiar a vista Lista
            candidateList.classList.remove('grid-view-mode');
            candidateList.classList.add('list-view-mode');

            // Cambiar clases de columnas
            document.querySelectorAll('.candidate-item').forEach(item => {
                item.className = 'candidate-item col-lg-12';
            });

            // Mostrar/ocultar contenido
            document.querySelectorAll('.grid-view-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.list-view-content').forEach(el => el.style.display = 'block');

            // Cambiar los cards a mb-0
            document.querySelectorAll('.candidate-item .card').forEach(card => {
                card.classList.add('mb-0');
            });

            // Cambiar botones activos
            if (btnGridView && btnListView) {
                btnListView.classList.add('active');
                btnGridView.classList.remove('active');
            }
        }
    }

    // Recuperar la configuración guardada en localStorage y aplicar al cargar
    const savedView = localStorage.getItem('mandamientosViewMode') || 'grid';

    // Aplicar la clase correspondiente al contenedor principal
    if (candidateList) {
        if (savedView === 'grid') {
            candidateList.classList.add('grid-view-mode');
            candidateList.classList.remove('list-view-mode');
        } else {
            candidateList.classList.add('list-view-mode');
            candidateList.classList.remove('grid-view-mode');
        }
    }

    applyView(savedView);

    if (btnGridView && btnListView) {
        btnGridView.addEventListener('click', function () {
            applyView('grid');
            localStorage.setItem('mandamientosViewMode', 'grid');
        });

        btnListView.addEventListener('click', function () {
            applyView('list');
            localStorage.setItem('mandamientosViewMode', 'list');
        });
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

            notification(response.success, "Mandamiento Registrado")

            const rowHtmlContent = rowHtml(response.datos, 1);

            if ($("#formMethod").val() === 'PUT') {
                $("#listadoMandamientos").find(`[data-id="${response.datos.id}"]`).replaceWith(rowHtmlContent);
            } else {
                $('#listadoMandamientos').prepend(rowHtmlContent);

            }
            $('#miModal').modal('hide');



        }).fail(function (xhr) {
            console.error('Error:', xhr);
            processError(xhr);
        });


    });

    $(document).on('change', 'input[name="estado"]', function () {

        ($(this).val() === 'EJECUTADO' || $(this).val() === 'CANCELADO') ? $('#fecha_ejecucion').closest('.caja').removeClass('d-none') : $('#fecha_ejecucion').closest('.caja').addClass('d-none');
    });

    $(document)
        .on('click', '.openModal', function (e) {
            e.preventDefault();

            const id = $(this).val();
            $("#mandamientoForm")[0].reset();
            // seleccionar todos los select2 y limpiar su selección
            $('#id_tipo_mandamiento').val(null).trigger('change');
            $('#id_delito').val(null).trigger('change');
            $('#id_juzgado').val(null).trigger('change');
            $('#id_persona').val(null).trigger('change');

            $("#mandamientoForm").attr('action', id ? `/mandamientos/${id}` : '/mandamientos');
            $("#formMethod").val('POST');



            const miModal = new bootstrap.Modal(document.getElementById('miModal'));
            miModal.show();


            if (id) {

                $.get(`/mandamientos/${id}/edit`)
                    .done(function (response) {
                        const datos = response.datos;



                        // iterar el objeto de datos y asignar los valores a los campos correspondientes

                        Object.entries(datos).forEach(function ([key, value]) {



                            $(`#${key}`).val(value).trigger('change');
                            // para loc checkboxes o radio buttons
                            if ($(`input[name="${key}"]`).attr('type') == 'radio') {
                                $(`input[name="${key}"][value="${value}"]`).prop('checked', true).trigger('change');
                            }

                            if (key === 'id_persona') {
                                $(`#${key}`).append(new Option(datos.nombre_completo + "- Ci:" + (datos.ci || ''), value, true, true)).trigger('change');
                            }

                            if (key === 'fecha_ejecucion' && value) {
                                $(`#${key}`).val(fechaInput(value));
                            }


                        });

                        $("#formMethod").val('PUT');
                    })
                    .fail(function (error) {
                        console.error('Error al cargar los datos del mandamiento:', error);
                        showAlert('Error al cargar los datos del mandamiento', 'error');
                    });


            }



        })
        .on('click', '.verDetalles', function (e) {
            e.preventDefault();
            const id = $(this).val();
            // Aquí puedes agregar la lógica para mostrar los detalles del mandamiento con el ID proporcionado
            console.log('Ver detalles del mandamiento con ID:', id);
            $("#modalDetalles").modal('show');
            $("#modalDetalles .modal-body").html('<div class="text-center"><span class="loaderHttp"></span><span class="text-muted">Cargando detalles...</span></div>');

            $.get(`/mandamientos/${id}`)
                .done(function (response) {
                    $("#modalDetalles .modal-body").html(response);
                })
                .fail(function (error) {
                    console.error('Error al cargar los detalles del mandamiento:', error);
                    $("#modalDetalles .modal-body").html('<p class="text-danger">Error al cargar los detalles del mandamiento.</p>');
                });
        })
        .on('click', '.btnDelete', async function (e) {
            e.preventDefault();
            const id = $(this).val();
            const hrLabel = $(this).closest('.card').find('.hr-label').text();

            const confirmacion = await confirmarEnvio( "Si, Eliminar", `¿Estás seguro de eliminar este mandamiento? <br> <strong>${hrLabel}</strong>`, "¡Sí, eliminar!", "Cancelar", "warning");


            console.log(confirmacion, hrLabel);

            if(confirmacion){

            }

        });

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
            placeholder: {
                id: '',
                text: 'Seleccione un juzgado'
            },
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
