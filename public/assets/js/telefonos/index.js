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

        if ($("#searchTelefonos").val().trim()) {
            params.search = $("#searchTelefonos").val().trim();
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
            const caso = telefono.caso || "—";
            const persona_caso = telefono.persona_caso || "—";
            const fecha = telefono.created_at
                ? new Date(telefono.created_at).toLocaleDateString("es-BO", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                })
                : "—";

            // Procesar IMEIs
            let imeis = [];
            if (telefono.imeis && Array.isArray(telefono.imeis)) {
                imeis = telefono.imeis.map(i => i.imei);
            }

            const imeisHtml = imeis.length > 0
                ? `<div class="d-flex flex-wrap gap-1">${imeis.map(imei => `<span class="badge badge-outline-secondary">${escapeHtml(imei)}</span>`).join('')}</div>`
                : '<small class="text-muted">Sin IMEIs</small>';

            // Procesar persona
            const tienePersona = telefono.persona_id && telefono.persona;
            const ci = tienePersona ? (telefono.persona.ci || "—") : "";
            const nombrePersona = tienePersona
                ? escapeHtml(telefono.persona.nombres + ' ' + telefono.persona.apellidos)
                : "No vinculado";
            const clasesPersona = tienePersona ? "" : "text-danger";

            html += /*html */`
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <span class="fw-bold">${escapeHtml(numero)}</span>
                        ${telefono.empresa ? `<br><small class="text-muted">${escapeHtml(telefono.empresa)}</small>` : ''}
                    </td>
                    <td>
                        <div class=" gap-2">
                            <div>
                                <div class="fw-bold">${escapeHtml(ci)}</div>
                                <small class="${clasesPersona}">${nombrePersona}</small>
                            </div>
                            ${!tienePersona ? `<button class="btn btn-sm btn-soft-secondary btn-vincular-persona" value="${telefono.id}" title="Vincular persona"><i class="ri-user-add-line align-bottom"></i></button>` : ''}
                        </div>
                    </td>
                    <td>${escapeHtml(caso)}</td>
                    <td>
                        ${persona_caso !== "—" ? `<span class="">${escapeHtml(persona_caso)}</span>` : '—'}
                    </td>
                    <td>
                        <div class=" gap-2">
                            <div>${imeisHtml}</div>
                            <button class="btn btn-sm btn-outline-secondary  p-1 py-0 btn-agregar-imei" value="${telefono.id}" title="Agregar IMEI">
                                <i class="ri-add-line align-bottom"></i>
                            </button>
                        </div>
                    </td>
                    <td>${fecha}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button class="btn btn-sm btn-soft-secondary btn-ver" value="${telefono.id}"
                                title="Ver detalles">
                                <i class="ri-eye-fill align-bottom"></i>
                            </button>

                            ${['superadmin', 'administrador'].includes(window.role) ?
                    /*html*/`
                            <button class="btn btn-sm btn-soft-secondary btn-editar" value="${telefono.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-secondary btn-eliminar" value="${telefono.id}" title="Eliminar">
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
     * Abrir modal para vincular persona
     */
    function abrirModalVincularPersona(telefonoId) {
        const $modal = $("#modalVincularPersona");
        const $select = $("#personaVincular");

        // Limpiar y destroyear select2 anterior
        if ($select.data('select2')) {
            $select.select2('destroy');
        }

        $select.val(null).html('<option value="">Seleccionar persona...</option>');

        // Inicializar Select2
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

        $("#btnVincularPersona").data("telef-id", telefonoId);
        $modal.modal("show");
    }

    /**
     * Vincular persona al teléfono
     */
    $(document).on("click", "#btnVincularPersona", function () {
        const telefonoId = $(this).data("telef-id");
        const personaId = $("#personaVincular").val();

        if (!personaId) {
            notification("Selecciona una persona", "Validación", 2000, "warning", "top");
            return;
        }

        const $btn = $(this);
        $btn.prop("disabled", true);
        $btn.html('<i class="ri-loader-4-line align-middle me-1"></i> Vinculando...');

        $.ajax({
            url: `/telefonos/${telefonoId}/vincular-persona`,
            type: "POST",
            dataType: "json",
            data: { persona_id: personaId },
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
                cargarTelefonos(currentPage);
                bootstrap.Modal.getInstance(document.getElementById("modalVincularPersona")).hide();
            })
            .fail(function (xhr) {
                $btn.prop("disabled", false);
                $btn.html('<i class="ri-user-add-line align-middle me-1"></i> Vincular');
                processError(xhr);
            });
    });

    /**
     * Abrir modal para agregar IMEI
     */
    /**
     * Bindear eventos del formulario
     */
    function bindFormulario() {
        const $form = $("#telefonoForm");
        const $btnGuardar = $("#btnGuardarTelefono");

        if ($form.length === 0 || $btnGuardar.length === 0) return;

        inicializarSelect2Personas();

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
    /**
     * Guardar teléfono
     */
    function guardarTelefono($form) {
        $form.find(".is-invalid").removeClass("is-invalid");

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
     * Vincular persona (click en botón vincular-persona)
     */
    $(document).on("click", ".btn-vincular-persona", function () {
        abrirModalVincularPersona($(this).val());
    });

    /**
     * Agregar IMEI (click en botón agregar-imei) - abre el módulo IMEI
     */
    $(document).on("click", ".btn-agregar-imei", function () {
        const telefonoId = $(this).val();

        console.log($(this).val());

        // Requiere que el módulo IMEI esté carguado y tenga una función llamada abrirModalIMEI
        if (typeof abrirModalIMEI === 'function') {
            abrirModalIMEI(telefonoId);
        } else {
            notification("El módulo de IMEI no está cargado", "Error", 2000, "danger", "top");
        }
    });

    /**
     * Inicialización cuando el documento está listo
     */

    $(document).on("click", "#btnNuevoTelefono", function () {
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
    });


    cargarTelefonos();

    $("#searchTelefonos").on("input", function () {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val().trim();

        // Solo buscar si tiene al menos 3 caracteres o está vacío
        if (searchValue.length >= 3 || searchValue.length === 0) {
            searchTimeout = setTimeout(() => cargarTelefonos(1), 400);
        }
    });

})();
