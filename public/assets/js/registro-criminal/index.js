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
            // lightbox.props.sources = imagenes.map(img => '/storage/' + img);
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

        dataScroll.id_delito = $("#filtroDelito").val();
        dataScroll.estado = $("#filtroEstado").val();

        dataScroll.search = $("#searchMandamientos").val();

        return dataScroll;
    }



    let scrollPersonal = $('#listadoMandamientos').scrollPagination({
        'url': '/registro-criminal', // the url you are fetching the results
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
                            <h4 class="card-title mb-0 flex-grow-1 hr-label">NRO: ${item.id || "-"} </h4>
                            <div class="mt-2 mt-sm-0">
                                <button type="button" value="${item.id}" class="btn btn-soft-secondary btn-sm shadow-none verDetalles" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalles">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <a  href="/registro-criminal/${item.id}/edit"  class="btn btn-soft-secondary btn-sm shadow-none " data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Mandamiento">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <button type="button" class="btn btn-soft-secondary btn-sm shadow-none btnDelete" value="${item.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar Mandamiento">
                                    <i class="ri-delete-bin-2-line"></i>
                                </button>

                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Vista GRID -->
                            <div class="grid-view-content" style="display: ${gridDisplay};">
                                <div class="d-sm-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-xxl rounded">

                                            <img src="${item.foto_frente ? ('/storage/' + item.foto_frente) : '/assets/img/user-dummy-img.jpg'}" alt="imagen de la persona"
                                                class="member-img img-fluid d-block rounded ${item.foto_frente ? 'cursor-pointer image-popup-zoom' : ''} " data-img='${item.imagenes}'>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <a href="javascript:void(0);">
                                            <h5 class="fs-16 mb-1">${item.nombres} ${item.apellidos} - ${item.genero || ""}</h5>
                                            <h6 class=" mb-1">C.I.: <strong>${item.ci || "-"} ${item.complemento ? " - " + item.complemento : ""}</strong></h6>
                                        </a>
                                        <p class=" mb-1"> Alias: <strong>${item.alias || "-"}</strong></p>
                                        <p class=" mb-1">Nombre Sup: <strong>${item.nombre_supuesto || "-"}</strong></p>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">Especialidad:<strong> ${item.especialidad}</strong></div>
                                        <div class=" gap-4 mt-1 text-muted">
                                            <div>Nacionalidad: ${item.gentilicio || ""}</div>
                                        </div>
                                        <div class=" gap-4 mt-1 text-muted">
                                            <div> Edad : ${item.fecha_nacimiento ? calcularEdad(item.fecha_nacimiento) : " - "}</div>
                                        </div>
                                        <p class=" mb-1"><i class="ri-calendar-line text-primary me-1 align-bottom"></i> <strong>${item.fecha_registro ? new Date(item.fecha_registro).toLocaleDateString('es-ES') : "-"}</strong></p>

                                    </div>
                                </div>
                            </div>
                            <!-- Vista LISTA -->
                            <div class="list-view-content" style="display: ${listDisplay};">
                                <div class="d-sm-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-lg height-auto rounded"><img src="${item.foto_frente ? ('/storage/' + item.foto_frente) : '/assets/img/user-dummy-img.jpg'}" alt=""
                                                class="member-img img-fluid d-block rounded ${item.imagenes ? 'cursor-pointer image-popup-zoom' : ''} " data-img='${item.imagenes}'>
                                            </div>
                                    </div>
                                    <div class="flex-grow-1 ms-md-3 mt-3 mt-md-0 d-md-flex align-items-center">
                                        <div class="ms-lg-3 my-3 my-lg-0">
                                            <a href="pages-profile">
                                                <h5 class="fs-16 mb-1">${item.nombres} ${item.apellidos}</h5>
                                                <h6 class="text-muted mb-1">Genero: <strong>${item.genero || "-"}</strong></h6>
                                                <h6 class="text-muted mb-1">C.I.: <strong>${item.ci || "-"}</strong></h6>

                                            </a>
                                            <p class="text-muted mb-1"> Alias: <strong>${item.alias || "-"}</strong></p>
                                            <p class="text-muted mb-1"> Edad: <strong>${item.fecha_nacimiento ? calcularEdad(item.fecha_nacimiento) : " - "}</strong></p>
                                            <p class="text-muted mb-1"> Nacionalidad: <strong>${item.gentilicio || "-"}</strong></p>
                                            <!-- <p class="text-muted mb-0">${item.tipo_mandamiento || "-"}</p> -->
                                        </div>
                                        <div class="d-flex flex-wrap gap-2 align-items-center mx-auto my-1 my-lg-0">
                                            <div> Nombre sup: <strong>${item.nombre_supuesto || "-"}</strong></div>
                                        </div>
                                        <div class="d-flex gap-4 mt-0 text-muted mx-auto">
                                            <div> Especialidad: <strong>${item.especialidad || "-"}</strong></div>
                                        </div>

                                        <div>
                                            <i class="ri-calendar-line text-primary me-1 align-bottom"></i>
                                            <strong>${item.fecha_registro ? new Date(item.fecha_registro).toLocaleDateString('es-ES') : "-"}</strong>

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

    $("#searchMandamientos").on('input', function (e) {
        e.preventDefault();
        clearTimeout(timerSearch);
        if ($(this).val().trim() != '' && $(this).val().trim().length < 3) return;
        timerSearch = setTimeout(function () {

            scrollPersonal.resetScrollPagination(getDataFilter());
        }, 500);

    });

    $("#filtroDelito,#filtroEstado").on('change', function (e) {
        e.preventDefault();
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








})();
