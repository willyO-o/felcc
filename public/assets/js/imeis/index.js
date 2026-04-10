/**
 * Módulo de Gestión de IMEIs
 * CRUD completo con paginación AJAX, búsqueda y filtros
 */
(function () {
    "use strict";

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let currentPage = 1;
    let pageSize = 10;
    let searchTimeout = null;

    const $listado = $("#listadoImeis");
    const $loading = $("#loadingImeis");
    const $sinResultados = $("#sinResultados");
    const $paginacion = $("#paginacionImeis");
    const $detallesPagina = $("#detalles-pagina");

    /**
     * Cargar listado de IMEIs con paginación y filtros
     */
    function cargarImeis(page = 1) {
        currentPage = page;
        $loading.show();
        $sinResultados.hide();
        $listado.empty();

        const params = {
            page: currentPage,
            size: pageSize,
        };

        if ($("#searchImeis").val().trim()) {
            params.search = $("#searchImeis").val().trim();
        }

        if ($('#filtros').val()) {
            params.filtro = $('#filtros').val();
        }

        $.ajax({
            url: "/imeis",
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

                renderImeis(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                $detallesPagina.text(`Mostrando ${desde} a ${hasta} de ${data.total} IMEIs`);
            })
            .fail(function (err) {
                $loading.hide();
                console.error("Error cargando IMEIs:", err);
                processError(err);
            });
    }

    /**
     * Renderizar tabla de IMEIs
     */
    function renderImeis(imeis) {
        let html = "";
        imeis.forEach((imei, index) => {
            const numeroImei = imei.imei || "—";
            const caracteristicas = imei.caracteristicas || "—";
            const fecha = imei.created_at
                ? new Date(imei.created_at).toLocaleDateString("es-BO", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                })
                : "—";

            // Procesar teléfono vinculado
            let telefonoVinculado = "Sin vincular";
            let claseTelefono = "text-danger";
            let botonesVinculacion = '';


            const telefonos = imei.telefonos || [];

            const telefonosHtml = telefonos.length > 0
                ? `<div class="d-flex flex-wrap gap-1">${telefonos.map(tel => `<span class="badge badge-outline-secondary">${escapeHtml(tel.numero_celular)}</span>`).join('')}</div>`
                : '<small class="text-muted">Sin teléfonos</small>';

            // telefonoVinculado

            html += /*html */`
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td><span class="fw-bold">${escapeHtml(numeroImei)}</span></td>
                    <td>
                        ${caracteristicas !== "—" ? escapeHtml(caracteristicas) : '—'}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            ${telefonosHtml}
                            <small class="${claseTelefono}">${telefonoVinculado}</small>
                            ${botonesVinculacion}
                        </div>
                    </td>
                    <td>${fecha}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button class="btn btn-sm btn-soft-secondary btn-ver" value="${imei.id}"
                                title="Ver detalles">
                                <i class="ri-eye-fill align-bottom"></i>
                            </button>

                            ${['superadmin', 'administrador'].includes(window.role) ?
                    /*html*/`
                            <button class="btn btn-sm btn-soft-secondary btn-editar" value="${imei.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-secondary btn-eliminar" value="${imei.id}" title="Eliminar">
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
                cargarImeis(p);
            }
        });
    }

    /**
     * Ver detalles del IMEI
     */
    window.mostrarDetalles = function (id) {
        $.ajax({
            url: `/imeis/${id}`,
            type: "GET",
        }).done(function (data) {
            $("#modalDetallesContent").html(data);

            // Poblar detalles del modal
            const imei = data.imei || {};

            $("#detalleImei").text(escapeHtml(imei.imei || "—"));
            $("#detalleCaracteristicas").text(escapeHtml(imei.caracteristicas || "—"));
            $("#detalleUltActualizacion").text(
                imei.updated_at
                    ? new Date(imei.updated_at).toLocaleDateString("es-BO", {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                        hour: "2-digit",
                        minute: "2-digit",
                    })
                    : "—"
            );
            $("#detalleFechaRegistro").text(
                imei.created_at
                    ? new Date(imei.created_at).toLocaleDateString("es-BO", {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                    })
                    : "—"
            );

            // Procesar teléfono vinculado
            let vinculacionHtml = '<p class="text-muted">Sin vincular</p>';
            if (imei.telefono) {
                vinculacionHtml = `
                    <div class="alert alert-info mb-0">
                        <div class="fw-bold">${escapeHtml(imei.telefono.numero_celular || "—")}</div>
                        ${imei.telefono.persona ? `<small>${escapeHtml(imei.telefono.persona.nombres + ' ' + imei.telefono.persona.apellidos)}</small>` : '<small class="text-muted">Sin persona vinculada</small>'}
                    </div>
                `;
            }
            $("#detalleVinculacion").html(vinculacionHtml);

            // Evento para editar desde los detalles
            $("#btnEditarImeiDetalle").off("click").on("click", function () {
                bootstrap.Modal.getInstance(document.getElementById("modalDetalles")).hide();
                editarImei(id);
            });

            const modal = new bootstrap.Modal(document.getElementById("modalDetalles"));
            modal.show();
        }).fail(function (err) {
            console.error("Error:", err);
            processError(err);
        });
    };

    /**
     * Editar IMEI
     */
    window.editarImei = function (id) {
        $.ajax({
            url: `/imeis/${id}/edit`,
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .done(function (html) {
                $("#modalImeiContent").html(html);
                const modalEl = document.getElementById("modalImei");
                const modal = new bootstrap.Modal(modalEl);

                // Esperar a que el modal esté completamente visible antes de inicializar Select2
                modalEl.addEventListener('shown.bs.modal', function onShown() {
                    bindFormulario();
                    modalEl.removeEventListener('shown.bs.modal', onShown);
                }, { once: true });

                modal.show();
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
        const $form = $("#imeiForm");
        const $btnGuardar = $("#btnGuardarImei");

        if ($form.length === 0 || $btnGuardar.length === 0) return;

        inicializarSelect2Telefonos();

        $btnGuardar.off("click").on("click", function () {
            guardarImei($form);
        });
    }

    /**
     * Inicializar Select2 para búsqueda de teléfonos
     */
    function inicializarSelect2Telefonos() {
        const $telefonoSelect = $("#telefono_id");

        if ($telefonoSelect.length === 0) return;

        // Destruir select2 anterior si existe
        if ($telefonoSelect.data('select2')) {
            $telefonoSelect.select2('destroy');
            // Limpiar select2 del DOM completamente
            $telefonoSelect.closest('.select2-container').remove();
        }

        $telefonoSelect.select2({
            placeholder: "Buscar teléfono (3+ caracteres)",
            allowClear: true,
            minimumInputLength: 3,
            ajax: {
                url: "/telefonos-imeis-search",
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
                        results: data
                    };
                },
                cache: true
            },
            templateResult: formatTelefonoResult,
            templateSelection: formatTelefonoSelection,
            dropdownParent: $("#modalImei"),
            width: "100%",
            theme: "bootstrap-5",
            language: {
                inputTooShort: function () {
                    return "Escribe al menos 3 caracteres";
                },
                noResults: function () {
                    return "No se encontraron teléfonos";
                },
                searching: function () {
                    return "Buscando...";
                },
                errorLoading: function () {
                    return "Error al cargar los teléfonos";
                }
            }
        });

        // Asegurar que el Select2 tenga el ancho correcto luego de ser renderizado
        $telefonoSelect.on('select2:opening', function () {
            $telefonoSelect.data('select2').$dropdown.css('width', '100%');
        });
    }

    /**
     * Formatear resultado en el dropdown
     */
    function formatTelefonoResult(telefono) {
        if (!telefono.id) return telefono.text;
        return $('<span>' + escapeHtml(telefono.text) + '</span>');
    }

    /**
     * Formatear selección
     */
    function formatTelefonoSelection(telefono) {
        if (!telefono.id) return telefono.text;
        return escapeHtml(telefono.text);
    }

    /**
     * Guardar IMEI
     */
    function guardarImei($form) {
        $form.find(".is-invalid").removeClass("is-invalid");

        const formData = new FormData($form[0]);
        const url = $form.attr("action");
        const $btnGuardar = $("#btnGuardarImei");

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
                cargarImeis(currentPage);

                const modal = bootstrap.Modal.getInstance(document.getElementById("modalImei"));
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
     * Eliminar IMEI
     */
    $(document).on("click", ".btn-eliminar", async function (e) {
        e.preventDefault();

        const imeiId = $(this).val();

        const confirmacion = await confirmarEnvio("Sí, Eliminar", "¿Estás seguro de eliminar este IMEI? Esta acción no se puede deshacer.");

        if (!confirmacion) {
            return;
        }

        const btn = $(this);

        $.ajax({
            url: `/imeis/${imeiId}`,
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
     * Ver detalles del IMEI (click en botón ver)
     */
    $(document).on("click", ".btn-ver", function () {
        mostrarDetalles($(this).val());
    });

    /**
     * Editar IMEI (click en botón editar)
     */
    $(document).on("click", ".btn-editar", function () {
        editarImei($(this).val());
    });

    /**
     * Abrir modal para vincular teléfono
     */
    function abrirModalVincularTelefono(imeiId) {
        const $modal = $("#modalVincularTelefono");
        const $select = $("#telefonoVincular");

        // Limpiar y destroyear select2 anterior
        if ($select.data('select2')) {
            $select.select2('destroy');
            $select.closest('.select2-container').remove();
        }

        $select.val(null).html('<option value="">Seleccionar teléfono...</option>');

        // Inicializar Select2
        $select.select2({
            placeholder: "Buscar teléfono (3+ caracteres)",
            allowClear: true,
            minimumInputLength: 3,
            ajax: {
                url: "/telefonos-imeis-search",
                type: "GET",
                dataType: "json",
                delay: 300,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            dropdownParent: $modal,
            width: "100%",
            theme: "bootstrap-5",
            language: {
                inputTooShort: () => "Escribe al menos 3 caracteres",
                noResults: () => "No se encontraron teléfonos",
                searching: () => "Buscando..."
            }
        });

        $("#btnVincularTelefono").data("imei-id", imeiId);
        $modal.modal("show");
    }

    /**
     * Vincular teléfono al IMEI
     */
    $(document).on("click", "#btnVincularTelefono", function () {
        const imeiId = $(this).data("imei-id");
        const telefonoId = $("#telefonoVincular").val();

        if (!telefonoId) {
            notification("Selecciona un teléfono", "Validación", 2000, "warning", "top");
            return;
        }

        const $btn = $(this);
        $btn.prop("disabled", true);
        $btn.html('<i class="ri-loader-4-line align-middle me-1"></i> Vinculando...');

        $.ajax({
            url: `/imeis/${imeiId}`,
            type: "PUT",
            dataType: "json",
            data: {
                telefono_id: telefonoId,
                _method: 'PUT'
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        })
            .done(function (data) {
                $btn.prop("disabled", false);
                $btn.html('<i class="ri-phone-add-line align-middle me-1"></i> Vincular');

                notification(data.success, "Éxito", 2000, "success", "top");
                cargarImeis(currentPage);
                bootstrap.Modal.getInstance(document.getElementById("modalVincularTelefono")).hide();
            })
            .fail(function (xhr) {
                $btn.prop("disabled", false);
                $btn.html('<i class="ri-phone-add-line align-middle me-1"></i> Vincular');
                processError(xhr);
            });
    });

    /**
     * Vincular teléfono (click en botón vincular-telefono)
     */
    $(document).on("click", ".btn-vincular-telefono", function () {
        abrirModalVincularTelefono($(this).val());
    });

    /**
     * Inicialización cuando el documento está listo
     */
    $(document).on("click", "#btnNuevoImei", function () {
        $.ajax({
            url: "/imeis/create",
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .done(function (html) {
                $("#modalImeiContent").html(html);
                const modalEl = document.getElementById("modalImei");
                const modal = new bootstrap.Modal(modalEl);

                // Esperar a que el modal esté completamente visible antes de inicializar Select2
                modalEl.addEventListener('shown.bs.modal', function onShown() {
                    bindFormulario();
                    modalEl.removeEventListener('shown.bs.modal', onShown);
                }, { once: true });

                modal.show();
            })
            .fail(function (err) {
                console.error("Error:", err);
                processError(err);
            });
    });

    cargarImeis();

    $("#searchImeis").on("input", function () {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val().trim();

        // Solo buscar si tiene al menos 3 caracteres o está vacío
        if (searchValue.length >= 3 || searchValue.length === 0) {
            searchTimeout = setTimeout(() => cargarImeis(1), 400);
        }
    });

    $("#filtros").on("change", function () {
        cargarImeis(1);
    });

})();
