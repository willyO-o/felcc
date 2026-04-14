/**
 * Módulo de Gestión de Vehículos
 * CRUD completo con paginación AJAX, búsqueda y filtros
 */
(function () {
    "use strict";

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let currentPage = 1;
    let pageSize = 10;
    let searchTimeout = null;
    let totalPages = 0;
    let filePondInstance = null;

    // Registrar plugins de FilePond
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
    );

    const $listado = $("#listadoVehiculos");
    const $loading = $("#loadingVehiculos");
    const $sinResultados = $("#sinResultados");
    const $paginacion = $("#paginacionVehiculos");
    const $detallesPagina = $("#detalles-pagina");
    const $searchInput = $("#searchVehiculos");

    /**
     * Cargar listado de vehículos con paginación y filtros
     */
    function cargarVehiculos(page = 1) {
        currentPage = page;
        $loading.show();
        $sinResultados.hide();
        $listado.empty();

        const params = {
            page: currentPage,
            size: pageSize,
        };

        if ($searchInput.val().trim()) {
            params.search = $searchInput.val().trim();
        }

        if ($('#filtros').val()) {
            params.filtro = $('#filtros').val();
        }

        $.ajax({
            url: "/vehiculos",
            type: "GET",
            dataType: "json",
            data: params,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        })
            .done(function (data) {
                $loading.hide();

                if (!data.datos || data.datos.length === 0) {
                    $sinResultados.show();
                    $paginacion.empty();
                    $detallesPagina.text("");
                    return;
                }

                renderVehiculos(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                $detallesPagina.text(`Mostrando ${desde} a ${hasta} de ${data.total} vehículos`);
            })
            .fail(function (err) {
                $loading.hide();
                console.error("Error cargando vehículos:", err);
                processError(err);
            });
    }

    /**
     * Renderizar tabla de vehículos
     */
    function renderVehiculos(vehiculos) {
        let html = "";
        vehiculos.forEach((vehiculo, index) => {
            const placa = vehiculo.placa || "—";
            const descripcion = vehiculo.descripcion || "—";
            const responsable = vehiculo.responsable || "—";
            const casoRelacionado = vehiculo.caso_relacionado || "—";
            const fecha = vehiculo.created_at
                ? new Date(vehiculo.created_at).toLocaleDateString("es-BO", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                })
                : "—";

            // Procesar personas vinculadas
            let personasHtml = "";
            if (vehiculo.personas && vehiculo.personas.length > 0) {
                personasHtml = vehiculo.personas.map(persona => {
                    const pivot = persona.pivot;
                    return `
                        <div class="mb-2">
                            <div class="fw-bold">${escapeHtml(persona.ci || "—")}</div>
                            <small>${escapeHtml(persona.nombres + ' ' + persona.apellidos)}</small><br>
                            <small><span class="badge badge-outline-secondary">${escapeHtml(pivot.tipo)}</span></small>
                            ${pivot.caso ? `<small><span class="badge badge-outline-info">${escapeHtml(pivot.caso)}</span></small>` : ''}
                        </div>
                    `;
                }).join('');
            } else {
                personasHtml = '<small class="text-muted">Sin personas vinculadas</small>';
            }

            html += /*html */`
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <span class="fw-bold">${escapeHtml(placa)}</span>
                    </td>
                    <td>${escapeHtml(descripcion)}</td>
                    <td>
                        <div style="max-height: 150px; overflow-y: auto;">
                            ${personasHtml}
                        </div>
                    </td>
                    <td>${escapeHtml(responsable)}</td>
                    <td>${escapeHtml(casoRelacionado)}</td>
                    <td>${fecha}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button class="btn btn-sm btn-soft-secondary btn-ver" value="${vehiculo.id}"
                                title="Ver detalles">
                                <i class="ri-eye-fill align-bottom"></i>
                            </button>

                            <button class="btn btn-sm btn-soft-success btn-vincular-persona" value="${vehiculo.id}"
                                title="Vincular persona">
                                <i class="ri-links-line align-bottom"></i>
                            </button>

                            ${['superadmin', 'administrador'].includes(window.role) ?
                    /*html*/`
                            <button class="btn btn-sm btn-soft-secondary btn-editar" value="${vehiculo.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-secondary btn-eliminar" value="${vehiculo.id}" title="Eliminar">
                                <i class="ri-delete-bin-fill align-bottom"></i>
                            </button>` : ""
                            }
                        </div>
                    </td>
                </tr>
            `;
        });
        $listado.html(html);
    }

    /**
     * Escaper HTML para evitar XSS
     */
    function escapeHtml(text) {
        if (!text) return "";
        return $("<div>").text(text).html();
    }

    /**
     * Renderizar paginación
     */
    function renderPaginacion(total, page, size) {
        totalPages = Math.ceil(total / size);
        let html = "";

        html += `<li class="page-item ${page <= 1 ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page - 1}">&laquo;</a>
        </li>`;

        let start = Math.max(1, page - 2);
        let end = Math.min(totalPages, page + 2);

        if (start > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (start > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }

        for (let i = start; i <= end; i++) {
            html += `<li class="page-item ${i === page ? "active" : ""}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        if (end < totalPages) {
            if (end < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }

        html += `<li class="page-item ${page >= totalPages ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page + 1}">&raquo;</a>
        </li>`;

        $paginacion.html(html);
    }

    /**
     * Ver detalles del vehículo
     */
    window.mostrarDetalles = function (id) {
        $.ajax({
            url: `/vehiculos/${id}`,
            type: "GET",
        }).done(function (data) {
            $("#modalDetallesContent").html(data);
            const modal = new bootstrap.Modal(document.getElementById("modalDetalles"));
            modal.show();
        }).fail(function (err) {
            console.error("Error:", err);
            processError(err);
        });
    };

    /**
     * Editar vehículo
     */
    window.editarVehiculo = function (id) {
        $.ajax({
            url: `/vehiculos/${id}/edit`,
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .done(function (html) {
                $("#modalVehiculoContent").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalVehiculo"));
                modal.show();

                setTimeout(() => {
                    bindFormulario();
                }, 100);

                $("#modalVehiculo").on("hidden.bs.modal", limpiarFilePondVehiculos);
            })
            .fail(function (err) {
                console.error("Error:", err);
                processError(err);
            });
    };

    /**
     * Agregar persona a la lista
     */
    function agregarPersonaFormulario() {
        const personaId = $("#personaBuscar").val();
        const tipo = $("#tipoPersona").val();
        const caso = $("#casoPersona").val().trim();

        if (!personaId) {
            notification("Selecciona una persona", "Validación", 2000, "warning", "top");
            return;
        }

        if (!tipo) {
            notification("Selecciona un tipo", "Validación", 2000, "warning", "top");
            return;
        }

        // Evitar duplicados
        const $listaPersonas = $("#listaPersonasVehiculo");
        const existe = $listaPersonas.find(`[data-persona-id="${personaId}"][data-tipo="${tipo}"]`).length > 0;

        if (existe) {
            notification("Esta persona ya está agregada con este tipo", "Validación", 2000, "warning", "top");
            return;
        }

        // Obtener nombre de la persona desde Select2
        const $select = $("#personaBuscar");
        const personaNombre = $select.find('option:selected').text() || "Persona";

        const card = `
            <div class="card mb-2" data-persona-id="${personaId}" data-tipo="${tipo}" data-caso="${caso}">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="fw-bold d-block">${escapeHtml(personaNombre)}</small>
                        <small class="badge badge-outline-secondary">${escapeHtml(tipo)}</small>
                        ${caso ? `<small class="badge badge-outline-info">Caso: ${escapeHtml(caso)}</small>` : ''}
                    </div>
                    <button type="button" class="btn btn-sm btn-soft-danger btn-remove-persona" title="Eliminar">
                        <i class="ri-close-line align-bottom"></i>
                    </button>
                </div>
            </div>
        `;

        $listaPersonas.append(card);
        $("#personaBuscar").val(null).trigger('change');
        $("#tipoPersona").val("");
        $("#casoPersona").val("");
        actualizarCampoPersonas();
    }

    /**
     * Actualizar campo oculto con todas las personas
     */
    function actualizarCampoPersonas() {
        const personas = [];
        $("#listaPersonasVehiculo [data-persona-id]").each(function () {
            const casoVal = $(this).data("caso");
            personas.push({
                persona_id: $(this).data("persona-id"),
                tipo: $(this).data("tipo"),
                caso: casoVal && typeof casoVal === 'string' && casoVal.trim() ? casoVal.trim() : null
            });
        });
        $("#personas_asociadas").val(JSON.stringify(personas));
    }


    /**
     * Bindear eventos del formulario
     */
    function inicializarFilePondVehiculos() {
        const inputElement = document.querySelector('#fotosVehiculos');
        if (!inputElement) return;

        if (filePondInstance) {
            FilePond.destroy(inputElement);
        }

        filePondInstance = FilePond.create(inputElement, {
            storeAsFile: false,
            allowMultiple: true,
            labelIdle: 'Arrastra o sube fotos del vehículo<br><span class="filepond--label-action">Seleccionar</span>',
            imagePreviewHeight: 140,
            acceptedFileTypes: ['image/*'],
            labelFileTypeNotAllowed: 'Archivo no válido. Solo se permiten imágenes.',
            allowFileSizeValidation: true,
            maxFiles: 10,
            maxFileSize: '2MB',
            labelMaxFileSize: 'Tamaño máximo de archivo es {filesize}',
            labelMaxFileSizeExceeded: 'Archivo demasiado grande',
            imageResizeTargetWidth: 400,
            imageResizeTargetHeight: 300,
            stylePanelLayout: 'compact stacked',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
        });
    }

    function limpiarFilePondVehiculos() {
        if (filePondInstance) {
            FilePond.destroy(document.querySelector('#fotosVehiculos'));
            filePondInstance = null;
        }
        $("#modalVehiculo").off("hidden.bs.modal", limpiarFilePondVehiculos);
    }

    /**
     * Bindear eventos del formulario
     */
    function bindFormulario() {
        const $form = $("#vehiculoForm");
        const $btnGuardar = $("#btnGuardarVehiculo");

        if ($form.length === 0 || $btnGuardar.length === 0) return;

        inicializarSelect2Personas();
        inicializarFilePondVehiculos();
        actualizarCampoPersonas();

        $("#btnAgregarPersona").off("click").on("click", function () {
            agregarPersonaFormulario();
        });

        $(document).on("click", "#listaPersonasVehiculo .btn-remove-persona", function (e) {
            e.preventDefault();
            $(this).closest(".card").remove();
            actualizarCampoPersonas();
        });

        $btnGuardar.off("click").on("click", function () {
            guardarVehiculo($form);
        });
    }

    /**
     * Inicializar Select2 para búsqueda de personas
     */
    function inicializarSelect2Personas() {
        const $personaSelect = $("#personaBuscar");

        if ($personaSelect.length === 0) return;

        if ($personaSelect.data('select2')) {
            $personaSelect.select2('destroy');
        }

        $personaSelect.select2({
            placeholder: "Buscar persona (3+ caracteres)",
            allowClear: true,
            minimumInputLength: 3,
            ajax: {
                url: "/personas-search",
                type: "GET",
                dataType: "json",
                delay: 300,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (persona) {
                            return {
                                id: persona.id,
                                text: persona.nombres + ' ' + persona.apellidos +
                                      (persona.ci ? ' (' + persona.ci + ')' : '')
                            };
                        })
                    };
                },
                cache: true
            },
            dropdownParent: $("#modalVehiculo"),
            width: "100%",
            theme: "bootstrap-5",
            language: {
                inputTooShort: () => "Escribe al menos 3 caracteres",
                noResults: () => "No se encontraron personas",
                searching: () => "Buscando..."
            }
        });
    }

    /**
     * Guardar vehículo
     */
    function guardarVehiculo($form) {
        $form.find(".is-invalid").removeClass("is-invalid");

        actualizarCampoPersonas();

        const formData = new FormData($form[0]);
        const url = $form.attr("action");
        const $btnGuardar = $("#btnGuardarVehiculo");

        // Agregar fotos de FilePond
        if (filePondInstance) {
            const files = filePondInstance.getFiles().map(fileItem => fileItem.file);
            files.forEach((file) => formData.append('fotosVehiculos[]', file));
        }

        $btnGuardar.prop("disabled", true);
        $btnGuardar.html('<i class="ri-loader-4-line align-middle me-1"></i> Guardando...');

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        })
            .done(function (body) {
                $btnGuardar.prop("disabled", false);
                $btnGuardar.html('<i class="ri-save-3-line align-middle me-1"></i> Guardar');

                notification(body.success, "Éxito", 2000, "success", "top");
                cargarVehiculos(currentPage);

                const modal = bootstrap.Modal.getInstance(document.getElementById("modalVehiculo"));
                if (modal) modal.hide();
            })
            .fail(function (xhr) {
                $btnGuardar.prop("disabled", false);
                $btnGuardar.html('<i class="ri-save-3-line align-middle me-1"></i> Guardar');

                const body = xhr.responseJSON;

                if (xhr.status === 422 && body.errors) {
                    Object.keys(body.errors).forEach((field) => {
                        const $input = $form.find(`[name="${field}"]`);
                        const $errorDiv = $form.find(`#error-${field}`);
                        if ($input.length) $input.addClass("is-invalid");
                        if ($errorDiv.length) $errorDiv.text(body.errors[field][0]);
                    });
                    return;
                }

                processError(xhr);
            });
    }

    /**
     * Eliminar vehículo
     */
    $(document).on("click", ".btn-eliminar", async function (e) {
        e.preventDefault();

        const vehiculoId = $(this).val();

        const confirmacion = await confirmarEnvio("Sí, Eliminar", "¿Estás seguro de eliminar este vehículo? Esta acción no se puede deshacer.");

        if (!confirmacion) {
            return;
        }

        const btn = $(this);

        $.ajax({
            url: `/vehiculos/${vehiculoId}`,
            type: "DELETE",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        }).done(function (body) {
            notification(body.success, "Éxito", 2000, "success", "top");
            btn.closest("tr").remove();
        })
            .fail(function (xhr) {
                processError(xhr);
            });
    });

    /**
     * Eliminar foto del vehículo
     */
    window.eliminarFotoVehiculo = function (fotoId) {
        Swal.fire({
            title: "¿Eliminar foto?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/multimedia/${fotoId}`,
                    type: "DELETE",
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json",
                    }
                })
                    .done(function (data) {
                        if (data.success) {
                            $(`[data-foto-id="${fotoId}"]`).remove();
                            notification(data.success, "Éxito", 2000, "success", "top");
                        }
                    })
                    .fail(function (xhr) {
                        processError(xhr);
                    });
            }
        });
    };

    /**
     * Ver detalles del vehículo (click en botón ver)
     */
    $(document).on("click", ".btn-ver", function () {
        mostrarDetalles($(this).val());
    });

    /**
     * Editar vehículo (click en botón editar)
     */
    $(document).on("click", ".btn-editar", function () {
        editarVehiculo($(this).val());
    });

    /**
     * Vincular persona (mostrar modal)
     */
    $(document).on("click", ".btn-vincular-persona", function () {
        const vehiculoId = $(this).val();
        abrirModalVincularPersona(vehiculoId);
    });

    /**
     * Abrir modal para vincular persona
     */
    function abrirModalVincularPersona(vehiculoId) {
        const $modal = $("#modalVincularPersona");
        const $form = $("#formVincularPersona");

        // Limpiar formulario
        $form[0].reset();
        $form.find(".is-invalid").removeClass("is-invalid");
        $form.find(".invalid-feedback").text("");

        // Guardar ID del vehículo en el formulario
        $form.data("vehiculo-id", vehiculoId);

        // Inicializar Select2
        inicializarSelect2VincularPersona();

        // Mostrar modal
        const modal = new bootstrap.Modal($modal[0]);
        modal.show();
    }

    /**
     * Inicializar Select2 para vincular persona
     */
    function inicializarSelect2VincularPersona() {
        const $personaSelect = $("#vincularPersonaBuscar");

        if ($personaSelect.data('select2')) {
            $personaSelect.select2('destroy');
        }

        $personaSelect.select2({
            placeholder: "Buscar persona (3+ caracteres)",
            allowClear: true,
            minimumInputLength: 3,
            ajax: {
                url: "/personas-search",
                type: "GET",
                dataType: "json",
                delay: 300,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (persona) {
                            return {
                                id: persona.id,
                                text: persona.nombres + ' ' + persona.apellidos +
                                    (persona.ci ? ' (' + persona.ci + ')' : '')
                            };
                        })
                    };
                },
                cache: true
            },
            dropdownParent: $("#modalVincularPersona"),
            width: "100%",
            theme: "bootstrap-5",
            language: {
                inputTooShort: () => "Escribe al menos 3 caracteres",
                noResults: () => "No se encontraron personas",
                searching: () => "Buscando..."
            }
        });
    }

    /**
     * Enviar formulario de vincular persona
     */
    $(document).on("submit", "#formVincularPersona", function (e) {
        e.preventDefault();

        const $form = $(this);
        const vehiculoId = $form.data("vehiculo-id");
        const personaId = $("#vincularPersonaBuscar").val();
        const tipo = $("#vincularTipo").val();
        const caso = $("#vincularCaso").val().trim();

        // Limpiar errores previos
        $form.find(".is-invalid").removeClass("is-invalid");
        $form.find(".invalid-feedback").text("");

        // Validar
        if (!personaId) {
            $("#vincularPersonaBuscar").addClass("is-invalid");
            $("#error-persona_id").text("Selecciona una persona");
            return;
        }

        if (!tipo) {
            $("#vincularTipo").addClass("is-invalid");
            $("#error-tipo").text("Selecciona un tipo");
            return;
        }

        const $btnVincular = $("#btnVincularPersona");
        $btnVincular.prop("disabled", true);
        $btnVincular.html('<i class="ri-loader-4-line align-middle me-1"></i> Vinculando...');

        const data = {
            persona_id: personaId,
            tipo: tipo,
            caso: caso || null,
            _token: csrfToken
        };

        $.ajax({
            url: `/vehiculos/${vehiculoId}/vincular-persona`,
            type: "POST",
            data: data,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        })
            .done(function (body) {
                $btnVincular.prop("disabled", false);
                $btnVincular.html('<i class="ri-link align-middle me-1"></i> Vincular');

                notification(body.success, "Éxito", 2000, "success", "top");
                cargarVehiculos(currentPage);

                const modal = bootstrap.Modal.getInstance(document.getElementById("modalVincularPersona"));
                if (modal) modal.hide();
            })
            .fail(function (xhr) {
                $btnVincular.prop("disabled", false);
                $btnVincular.html('<i class="ri-link align-middle me-1"></i> Vincular');

                const body = xhr.responseJSON;

                if (xhr.status === 422 && body.errors) {
                    Object.keys(body.errors).forEach((field) => {
                        const $input = $form.find(`[name="${field}"]`);
                        const $errorDiv = $form.find(`#error-${field}`);
                        if ($input.length) $input.addClass("is-invalid");
                        if ($errorDiv.length) $errorDiv.text(body.errors[field]?.[0] || "Error");
                    });

                    // Si hay error de validación de campo, mostrar el mensaje
                    if (body.error) {
                        notification(body.error, "Error", 3000, "danger", "top");
                    }
                    return;
                }

                if (body.error) {
                    notification(body.error, "Error", 3000, "danger", "top");
                } else {
                    processError(xhr);
                }
            });
    });

    /**
     * Desvincular persona de la tabla de detalles
     */
    $(document).on("click", ".btn-desvincular-persona-tabla", function (e) {
        e.preventDefault();

        const $row = $(this).closest("tr");
        const vehiculoId = $row.data("vehiculo-id");
        const personaId = $(this).data("persona-id");
        const personaNombre = $(this).data("persona-nombre");

        confirmarEnvio("Sí, Desvincular", `¿Desvinculars a ${personaNombre}?`).then(confirmacion => {
            if (confirmacion) {
                $.ajax({
                    url: `/vehiculos/${vehiculoId}/desvincular-persona/${personaId}`,
                    type: "DELETE",
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json",
                    }
                })
                    .done(function (body) {
                        notification(body.success, "Éxito", 2000, "success", "top");
                        cargarVehiculos(currentPage);
                    })
                    .fail(function (xhr) {
                        processError(xhr);
                    });
            }
        });
    });

    /**
     * Crear nuevo vehículo
     */
    $(document).on("click", "#btnNuevoVehiculo", function () {
        $.ajax({
            url: "/vehiculos/create",
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .done(function (html) {
                $("#modalVehiculoContent").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalVehiculo"));
                modal.show();

                setTimeout(() => {
                    bindFormulario();
                }, 100);

                $("#modalVehiculo").on("hidden.bs.modal", limpiarFilePondVehiculos);
            })
            .fail(function (err) {
                console.error("Error:", err);
                processError(err);
            });
    });

    /**
     * Paginación - Solo se registra una vez
     */
    $(document).on("click", "#paginacionVehiculos .page-link[data-page]", function (e) {
        e.preventDefault();
        const p = parseInt($(this).data("page"));
        if (p >= 1 && p <= totalPages) {
            cargarVehiculos(p);
        }
    });

    /**
     * Inicialización cuando el documento está listo
     */
    $(document).ready(function () {
        cargarVehiculos();

        if ($searchInput.length) {
            $searchInput.on("input", function () {
                clearTimeout(searchTimeout);
                const searchValue = $(this).val().trim();

                // Solo buscar si tiene al menos 3 caracteres o está vacío
                if (searchValue.length >= 3 || searchValue.length === 0) {
                    searchTimeout = setTimeout(() => cargarVehiculos(1), 400);
                }
            });
        }
    });
})();
