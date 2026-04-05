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
        'fecha_inicio': null,
        'fecha_fin': null,
    }

    function getDataFilter() {

        dataScroll.tipo_filtro = $("#tipo_filtro").val();
        dataScroll.search = $("#searchMandamientos").val();

        return dataScroll;
    }



    let scrollPersonal = $('#listadoMandamientos').scrollPagination({
        'url': '/consultas/mandamientos', // the url you are fetching the results
        'method': 'get',
        'data': getDataFilter(),
        'dataTemplateCallback': rowHtml,
        'elementCountSelector': '#detalles-pagina',
        'elementCountTemplate': '<span  class=""> Listando <b> {count}  </b>elementos de <b> {total} </b> encontrados </span>',
        'loading': '#loadingMandamientos',
        'scroller': "#containerListaMandamientos",
        'loadingText': `<div  class=" text-center"><i class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i><span class="text-muted">Cargando...</span></div>`,
        'loadingNomoreText': '<h6 class="text-danger text-center">No se encontraron más Resultados</h6>',

    });

    // Inicializar DateRangePicker
    $('#filtroFechas').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            firstDay: 1
        },
        autoUpdateInput: false,
        timePicker: false,
        timePickerIncrement: 1,
        startDate: moment().subtract(30, 'days'),
        endDate: moment(),
        ranges: {
            'Hoy': [moment(), moment()],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Últimos 90 días': [moment().subtract(89, 'days'), moment()]
        }
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

        const archivoMandamiento = item.archivo_mandamiento ? JSON.parse(item.archivo_mandamiento) : null;
        const actaEjecucion = item.acta_ejecucion ? JSON.parse(item.acta_ejecucion) : null;

        let html =/*html*/ `
                <div data-id="${item.id}" class="${itemClasses}" style='opacity:${opacity};-moz-opacity: ${opacity};filter: alpha(opacity=${opacity});'>
                    <div class="${cardClasses}">
                        <div class="card-header border-0 pb-0 pt-3 align-items-center d-sm-flex">
                            <h4 class="card-title mb-0 flex-grow-1 hr-label">HR: ${item.hoja_ruta || "-"} </h4>
                            <div class="mt-2 mt-sm-0">
                                <button type="button" value="${item.id}" class="btn btn-soft-secondary btn-sm shadow-none verDetalles" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalles">
                                    <i class="ri-eye-line"></i>
                                </button>

                                ${archivoMandamiento && archivoMandamiento.tipo_archivo !== 'pdf' ? /*html */`
                                    <button type="button" class="btn btn-soft-secondary btn-sm shadow-none btn-ver-img" data-img='${archivoMandamiento.ruta}' data-bs-toggle="tooltip" data-bs-placement="top" title="Ver imagen mandamiento">
                                        <i class="ri-image-line"></i>
                                    </button>` : ''}
                                ${archivoMandamiento && archivoMandamiento.tipo_archivo == 'pdf' ? /*html */`
                                    <a href="${window.location.origin}/storage/${archivoMandamiento.ruta}" target="_blank" class="btn btn-soft-secondary btn-sm shadow-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver imagen mandamiento">
                                        <i class="ri-image-line"></i>
                                    </a>` : ''}
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
                                        <a href="javascript:void(0);" class="text-reset " >
                                            <h5 class="fs-16 mb-1">${item.nombre_completo}</h5>
                                            <h6 class="text-muted mb-2">C.I.: <strong>${item.ci || "-"}</strong></h6>
                                        </a>
                                        <p class="text-muted mb-2"> Delito: <strong>${item.nombre_delito || "-"}</strong></p>
                                        <p class="text-muted mb-2"> <strong>${item.tipo_mandamiento || "-"}</strong></p>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">Estado:
                                            <div class="badge text-bg-${coloresEstados[item.estado] || 'secondary'}">${item.estado || "-"}</div>
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
                                            <a href="javascript:void(0);" class="text-reset " >
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
                                            <div class="badge text-bg-${coloresEstados[item.estado] || 'secondary'}"> ${item.estado || "-"}</div>
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

    let timerSearch;

    $("#btnBuscar").on('click', function (e) {
        e.preventDefault();
        if ($("#tipo_filtro").val().trim() == '' || $("#searchMandamientos").val().trim().length < 4) return;

        scrollPersonal.resetScrollPagination(getDataFilter());

    });

    $("#filtroFechas").on('apply.daterangepicker', function (ev, picker) {
        // Capturar las fechas seleccionadas
        dataScroll.fecha_inicio = picker.startDate.format('YYYY-MM-DD');
        dataScroll.fecha_fin = picker.endDate.format('YYYY-MM-DD');
    });

    $("#filtroFechas").on('cancel.daterangepicker', function (ev, picker) {
        // Limpiar los filtros de fecha cuando se cancela
        dataScroll.fecha_inicio = null;
        dataScroll.fecha_fin = null;
        $(this).val('');
    });

    // Evento para el botón de limpiar filtro de fechas
    $("#btnLimpiarFechas").on('click', function (e) {
        e.preventDefault();
        dataScroll.fecha_inicio = null;
        dataScroll.fecha_fin = null;
        $("#filtroFechas").val('');
        scrollPersonal.resetScrollPagination(getDataFilter());
    });

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




    $(document).on('submit', '#mandamientoForm', function (e) {
        e.preventDefault();

        const datos = new FormData(this);

        const form = $(this);

        datos.append('_token', $('meta[name="csrf-token"]').attr('content'));

        const boton = form.find('button[type="submit"]');

        boton.prop('disabled', true).find('i').removeClass('ri-save-line').addClass('fa-solid fa-spinner fa-spin');

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
        }).always(function () {
            boton.prop('disabled', false).find('i').removeClass('fa-solid fa-spinner fa-spin').addClass('ri-save-line');
        })


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

            $(".requerido_ejecutado").hide().find('input').attr('required', false);


            const miModal = new bootstrap.Modal(document.getElementById('miModal'));
            miModal.show();


            if (id) {

                $.get(`/mandamientos/${id}/edit`)
                    .done(function (response) {
                        const datos = response.datos;



                        // iterar el objeto de datos y asignar los valores a los campos correspondientes

                        Object.entries(datos).forEach(function ([key, value]) {


                            if (key == 'acta_ejecucion') {
                                //saltar campo

                                return;
                            }

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

                        if ($('#estado').val().includes('EJECUTADO')) {
                            $(".requerido_ejecutado").show().find('input').attr('required', true);

                        }

                        if (datos.acta_ejecucion) {
                            $(".requerido_ejecutado").show().find('input[type="file"]').attr('required', false);
                        }

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

            const btn = $(this);
            const hrLabel = $(this).closest('.card').find('.hr-label').text();

            const confirmacion = await confirmarEnvio("Si, Eliminar", `¿Estás seguro de eliminar este mandamiento? <br> <strong>${hrLabel}</strong>`, "¡Sí, eliminar!", "Cancelar", "warning");

            if (confirmacion) {

                $.ajax({
                    url: `/mandamientos/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function (response) {
                    notification(response.success, "Mandamiento Eliminado");

                    // Eliminar la tarjeta del DOM
                    btn.closest('div[data-id="' + id + '"]').fadeOut(500, function () {
                        $(this).remove();
                    });

                }).fail(function (xhr) {
                    processError(xhr);

                })

            }

        });

    /* cargar datos parametricos tipo mandamiento */










    $("#btnPersona").on('click', function (e) {
        e.preventDefault();

        let swalInstance = Swal.fire({
            title: 'Añadir Nueva Persona',
            html: /*html */`
                <form id="swal-persona-form" enctype="multipart/form-data" action="#" method="POST">
                    <input type="text" id="swal-ci" name="ci" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Cédula de Identidad (opcional)" >
                    <input type="text" id="swal-nombres" name="nombres" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Nombres" required>
                    <input type="text" id="swal-apellidos" name="apellidos" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Apellidos" required>
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
                    const newOption = new Option(`${response.data.nombres} ${response.data.apellidos || ''} - ${response.data.ci || ''}`, response.data.id, true, true);
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


    $(document).on('input', '#estado', function () {
        const valor = $(this).val();

        // verificar si en la cadena ingresada se encuentra la palabra "EJECUTADO" o "CANCELADO"

        if (!valor) {
            return;
        }

        if (valor.toUpperCase().includes('EJECUTADO')) {

            $(".requerido_ejecutado").show().find('input').attr('required', true);
        } else {
            $(".requerido_ejecutado").hide().find('input').attr('required', false);
        }


    })



})();
