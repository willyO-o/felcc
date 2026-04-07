/**
 * Módulo de Gestión de Teléfonos
 * CRUD completo con paginación AJAX, búsqueda y filtros
 */
(function () {
    "use strict";

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let currentPage = 1;
    let pageSize = 10;
    let searchTimeout = null;

    const $listado = $("#listadoTelefonos");
    const $loading = $("#loadingTelefonos");
    const $sinResultados = $("#sinResultados");
    const $paginacion = $("#paginacionTelefonos");
    const $detallesPagina = $("#detalles-pagina");
    const $searchInput = $("#searchTelefonos");
    const $btnNuevo = $("#btnNuevoTelefono");

    /**
     * Cargar listado de teléfonos con paginación y filtros
     */
    function cargarTelefonos(page = 1) {
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
            url: "/telefonos",
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

                renderTelefonos(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                $detallesPagina.text(`Mostrando ${desde} a ${hasta} de ${data.total} teléfonos`);
            })
            .fail(function (err) {
                $loading.hide();
                console.error("Error cargando teléfonos:", err);
                processError(err);
            });
    }

    /**
     * Renderizar tabla de teléfonos
     */
    function renderTelefonos(telefonos) {
        let html = "";
        telefonos.forEach((telefono, index) => {
            const numero = telefono.numero_celular || "—";
            const personaCaso = telefono.persona_caso || "—";
            const empresa = telefono.empresa || "—";
            const caso = telefono.caso || "—";
            const respuesta = telefono.respuesta_requerimiento || "—";
            const fecha = telefono.created_at
                ? new Date(telefono.created_at).toLocaleDateString("es-BO", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                })
                : "—";

            html += /*html */`
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <span class="fw-bold">${escapeHtml(numero)}</span>
                        ${telefono.empresa ? `<br><small class="text-muted">${escapeHtml(telefono.empresa)}</small>` : ''}
                    </td>
                    <td>${escapeHtml(personaCaso)}</td>
                    <td>${escapeHtml(empresa)}</td>
                    <td>${escapeHtml(caso)}</td>
                    <td>
                        ${respuesta !== "—" ? `<span class="badge bg-info">${escapeHtml(respuesta)}</span>` : '—'}
                    </td>
                    <td>${fecha}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-soft-info btn-ver" value="${telefono.id}"
                                title="Ver detalles">
                                <i class="ri-eye-fill align-bottom"></i>
                            </button>

                            ${['superadmin', 'administrador'].includes(window.role) ?
                    /*html*/`
                            <button class="btn btn-sm btn-soft-warning btn-editar" value="${telefono.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-danger btn-eliminar" value="${telefono.id}" title="Eliminar">
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
                cargarTelefonos(p);
            }
        });
    }

    /**
     * Ver detalles del teléfono
     */
    window.mostrarDetalles = function (id) {
        $.ajax({
            url: `/telefonos/${id}`,
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
     * Abrir modal para crear teléfono
     */
    function abrirModalCrear() {
        $.ajax({
            url: "/telefonos/create",
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .done(function (html) {
                $("#modalTelefonoContent").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalTelefono"));
                modal.show();

                setTimeout(() => {
                    bindFormulario();
                }, 100);
            })
            .fail(function (err) {
                console.error("Error:", err);
                processError(err);
            });
    }

    /**
     * Editar teléfono
     */
    window.editarTelefono = function (id) {
        $.ajax({
            url: `/telefonos/${id}/edit`,
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .done(function (html) {
                $("#modalTelefonoContent").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalTelefono"));
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
     * Bindear eventos del formulario
     */
    function bindFormulario() {
        const $form = $("#telefonoForm");
        const $btnGuardar = $("#btnGuardarTelefono");

        if ($form.length === 0 || $btnGuardar.length === 0) return;

        inicializarSelect2Personas();
        actualizarCampoIMEIs();
        $("#nuevoIMEI").focus();

        $btnGuardar.off("click").on("click", function () {
            guardarTelefono($form);
        });
    }

    /**
     * Inicializar Select2 para búsqueda de personas
     */
    function inicializarSelect2Personas() {
        const $personaSelect = $("#persona_id");

        if ($personaSelect.length === 0) return;

        // Destruir select2 anterior si existe
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
                    return {
                        q: params.term
                    };
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
            templateResult: formatPersonaResult,
            templateSelection: formatPersonaSelection,
            dropdownParent: $("#modalTelefono"),
            width: "100%",
            theme: "bootstrap-5",
            language: {
                inputTooShort: function () {
                    return "Escribe al menos 3 caracteres";
                },
                noResults: function () {
                    return "No se encontraron personas";
                },
                searching: function () {
                    return "Buscando...";
                },
                errorLoading: function () {
                    return "Error al cargar las personas";
                }
            }
        });
    }

    /**
     * Formatear resultado en el dropdown
     */
    function formatPersonaResult(persona) {
        if (!persona.id) return persona.text;
        return $('<span>' + escapeHtml(persona.text) + '</span>');
    }

    /**
     * Formatear selección
     */
    function formatPersonaSelection(persona) {
        if (!persona.id) return persona.text;
        return escapeHtml(persona.text);
    }

    /**
     * Agregar IMEI a la lista
     */
    function agregarIMEI() {
        const nuevoIMEI = $("#nuevoIMEI").val().trim();

        if (!nuevoIMEI) {
            notification("Ingresa un IMEI válido", "Validación", 2000, "warning", "top");
            return;
        }

        const $listaIMEIs = $("#listaIMEIs");
        const imeiExiste = $listaIMEIs.find(`[data-imei="${nuevoIMEI}"]`).length > 0;

        if (imeiExiste) {
            notification("Este IMEI ya existe en la lista", "Validación", 2000, "warning", "top");
            return;
        }

        const badge = `
            <span class="badge badge-outline-primary d-flex align-items-center gap-2" data-imei="${escapeHtml(nuevoIMEI)}">
                ${escapeHtml(nuevoIMEI)}
                <button type="button" value="${escapeHtml(nuevoIMEI)}" class="btn-remove-imei btn btn-primary py-0 px-1 border-0 text-white cursor-pointer" title="Eliminar">
                    <i class="ri-close-line"></i>
                </button>
            </span>
        `;

        $listaIMEIs.append(badge);
        $("#nuevoIMEI").val("").focus();
        actualizarCampoIMEIs();
    }

    /**
     * Actualizar campo oculto con todos los IMEIs
     */
    function actualizarCampoIMEIs() {
        const imeis = [];
        $("#listaIMEIs [data-imei]").each(function () {
            imeis.push($(this).data("imei"));
        });
        $("#imeis_asociados").val(imeis.join(","));
    }

    /**
     * Delegación de eventos para agregar IMEI (botón y Enter)
     */
    $(document).on("click", "#btnAgregarIMEI", function () {
        agregarIMEI();
    });

    $(document).on("keypress", "#nuevoIMEI", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            agregarIMEI();
        }
    });

    /**
     * Delegación de eventos para eliminar IMEI
     */
    $(document).on("click", ".btn-remove-imei", function (e) {
        e.preventDefault();
        $(this).closest(".badge").remove();
        actualizarCampoIMEIs();
    });

    /**
     * Guardar teléfono
     */
    function guardarTelefono($form) {
        $form.find(".is-invalid").removeClass("is-invalid");

        // Actualizar IMEIs antes de enviar
        actualizarCampoIMEIs();

        const formData = new FormData($form[0]);
        const url = $form.attr("action");
        const $btnGuardar = $("#btnGuardarTelefono");

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
                cargarTelefonos(currentPage);

                const modal = bootstrap.Modal.getInstance(document.getElementById("modalTelefono"));
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
     * Eliminar teléfono
     */
    $(document).on("click", ".btn-eliminar", async function (e) {
        e.preventDefault();

        const telefonoId = $(this).val();

        const confirmacion = await confirmarEnvio("Sí, Eliminar", "¿Estás seguro de eliminar este teléfono? Esta acción no se puede deshacer.");

        if (!confirmacion) {
            return;
        }

        const btn = $(this);

        $.ajax({
            url: `/telefonos/${telefonoId}`,
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
     * Ver detalles del teléfono (click en botón ver)
     */
    $(document).on("click", ".btn-ver", function () {
        mostrarDetalles($(this).val());
    });

    /**
     * Editar teléfono (click en botón editar)
     */
    $(document).on("click", ".btn-editar", function () {
        editarTelefono($(this).val());
    });

    /**
     * Inicialización cuando el documento está listo
     */
    $(document).ready(function () {
        cargarTelefonos();

        if ($btnNuevo.length) {
            $btnNuevo.on("click", abrirModalCrear);
        }

        if ($searchInput.length) {
            $searchInput.on("input", function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => cargarTelefonos(1), 400);
            });
        }
    });
})();
