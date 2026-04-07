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

    const $listado = $("#listadoVehiculos");
    const $loading = $("#loadingVehiculos");
    const $sinResultados = $("#sinResultados");
    const $paginacion = $("#paginacionVehiculos");
    const $detallesPagina = $("#detalles-pagina");
    const $searchInput = $("#searchVehiculos");
    const $btnNuevo = $("#btnNuevoVehiculo");

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
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <div class="fw-bold">${escapeHtml(persona.ci || "—")}</div>
                                <small>${escapeHtml(persona.nombres + ' ' + persona.apellidos)}</small><br>
                                <small><span class="badge bg-secondary">${escapeHtml(pivot.tipo)}</span></small>
                                ${pivot.caso ? `<small><span class="badge bg-info">${escapeHtml(pivot.caso)}</span></small>` : ''}
                            </div>
                            <button class="btn btn-sm btn-soft-danger btn-desvincular-persona" data-Vehicle-id="${vehiculo.id}" data-case-id="${pivot.id}" title="Desvincular">
                                <i class="ri-close-line align-bottom"></i>
                            </button>
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
                        <button class="btn btn-sm btn-soft-primary btn-vincular-persona mt-2" value="${vehiculo.id}" title="Vincular persona">
                            <i class="ri-user-add-line align-bottom"></i> Vincular
                        </button>
                    </td>
                    <td>${escapeHtml(responsable)}</td>
                    <td>${escapeHtml(casoRelacionado)}</td>
                    <td>${fecha}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button class="btn btn-sm btn-soft-info btn-ver" value="${vehiculo.id}"
                                title="Ver detalles">
                                <i class="ri-eye-fill align-bottom"></i>
                            </button>

                            ${['superadmin', 'administrador'].includes(window.role) ?
                    /*html*/`
                            <button class="btn btn-sm btn-soft-warning btn-editar" value="${vehiculo.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-danger btn-eliminar" value="${vehiculo.id}" title="Eliminar">
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
        const totalPages = Math.ceil(total / size);
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

        $paginacion.on("click", ".page-link[data-page]", function (e) {
            e.preventDefault();
            const p = parseInt($(this).data("page"));
            if (p >= 1 && p <= totalPages) {
                cargarVehiculos(p);
            }
        });
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
            })
            .fail(function (err) {
                console.error("Error:", err);
                processError(err);
            });
    };

    /**
     * Abrir modal para vincular persona
     */
    function abrirModalVincularPersona(vehiculoId) {
        const $modal = $("#modalVincularPersona");
        const $select = $("#personaVincular");

        if ($select.data('select2')) {
            $select.select2('destroy');
        }

        $select.val(null).html('<option value="">Seleccionar persona...</option>');

        $select.select2({
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
            dropdownParent: $modal,
            width: "100%",
            theme: "bootstrap-5",
            language: {
                inputTooShort: () => "Escribe al menos 3 caracteres",
                noResults: () => "No se encontraron personas",
                searching: () => "Buscando..."
            }
        });

        $("#btnVincularPersona").data("vehiculo-id", vehiculoId);
        $("#tipoVinculacion").val("");
        $("#casoVinculacion").val("");
        $modal.modal("show");
    }

    /**
     * Vincular persona al vehículo
     */
    $(document).on("click", "#btnVincularPersona", function () {
        const vehiculoId = $(this).data("vehiculo-id");
        const personaId = $("#personaVincular").val();
        const tipo = $("#tipoVinculacion").val();

        if (!personaId) {
            notification("Selecciona una persona", "Validación", 2000, "warning", "top");
            return;
        }

        if (!tipo) {
            notification("Selecciona un tipo de información", "Validación", 2000, "warning", "top");
            return;
        }

        const $btn = $(this);
        $btn.prop("disabled", true);
        $btn.html('<i class="ri-loader-4-line align-middle me-1"></i> Vinculando...');

        $.ajax({
            url: `/vehiculos/${vehiculoId}/vincular-persona`,
            type: "POST",
            dataType: "json",
            data: {
                persona_id: personaId,
                tipo: tipo,
                caso: $("#casoVinculacion").val(),
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        })
            .done(function (data) {
                $btn.prop("disabled", false);
                $btn.html('<i class="ri-user-add-line align-middle me-1"></i> Vincular');

                notification(data.success, "Éxito", 2000, "success", "top");
                cargarVehiculos(currentPage);
                bootstrap.Modal.getInstance(document.getElementById("modalVincularPersona")).hide();
            })
            .fail(function (xhr) {
                $btn.prop("disabled", false);
                $btn.html('<i class="ri-user-add-line align-middle me-1"></i> Vincular');
                processError(xhr);
            });
    });

    /**
     * Desvincular persona
     */
    $(document).on("click", ".btn-desvincular-persona", async function (e) {
        e.preventDefault();
        const vehiculoId = $(this).data("vehicle-id");
        const caseId = $(this).data("case-id");

        const confirmacion = await confirmarEnvio("Sí, Desvincular", "¿Desvinculár esta persona del vehículo?");

        if (!confirmacion) {
            return;
        }

        $.ajax({
            url: `/vehiculos/${vehiculoId}/desvincular-persona/${caseId}`,
            type: "DELETE",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        })
            .done(function (data) {
                notification(data.success, "Éxito", 2000, "success", "top");
                cargarVehiculos(currentPage);
            })
            .fail(function (xhr) {
                processError(xhr);
            });
    });

    /**
     * Bindear eventos del formulario
     */
    function bindFormulario() {
        const $form = $("#vehiculoForm");
        const $btnGuardar = $("#btnGuardarVehiculo");

        if ($form.length === 0 || $btnGuardar.length === 0) return;

        $btnGuardar.off("click").on("click", function () {
            guardarVehiculo($form);
        });
    }

    /**
     * Guardar vehículo
     */
    function guardarVehiculo($form) {
        $form.find(".is-invalid").removeClass("is-invalid");

        const formData = new FormData($form[0]);
        const url = $form.attr("action");
        const $btnGuardar = $("#btnGuardarVehiculo");

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
     * Vincular persona (click en botón vincular-persona)
     */
    $(document).on("click", ".btn-vincular-persona", function () {
        abrirModalVincularPersona($(this).val());
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
            })
            .fail(function (err) {
                console.error("Error:", err);
                processError(err);
            });
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
