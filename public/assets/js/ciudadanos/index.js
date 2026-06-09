/**
 * Módulo de Gestión de Ciudadanos
 * CRUD completo con paginación AJAX, búsqueda y filtros
 */
(function () {
    "use strict";

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let currentPage = 1;
    let pageSize = 10;
    let searchTimeout = null;
    let totalPages = 0;

    const $listado = $("#listadoCiudadanos");
    const $loading = $("#loadingCiudadanos");
    const $sinResultados = $("#sinResultados");
    const $paginacion = $("#paginacionCiudadanos");
    const $detallesPagina = $("#detalles-pagina");
    const $searchInput = $("#searchCiudadanos");
    const $filtroSexo = $("#filtroSexo");
    const $filtroEstadoCivil = $("#filtroEstadoCivil");
    const $filtroEstadoRegistro = $("#filtroEstadoRegistro");
    const $filtroVisible = $("#filtroVisible");
    const $btnNuevo = $("#btnNuevoCiudadano");

    /**
     * Cargar ciudadanos con filtros
     */
    function cargarCiudadanos(page = 1) {
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

        if ($filtroSexo.val()) {
            params.sexo = $filtroSexo.val();
        }

        if ($filtroEstadoCivil.val()) {
            params.estado_civil = $filtroEstadoCivil.val();
        }

        if ($filtroEstadoRegistro.val()) {
            params.estado_registro = $filtroEstadoRegistro.val();
        }

        if ($filtroVisible.val()) {
            params.visible = $filtroVisible.val();
        }

        $.ajax({
            url: "/ciudadanos",
            type: "GET",
            dataType: "json",
            data: params,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .done(function (data) {
                $loading.hide();

                if (data.datos && data.datos.length > 0) {
                    renderCiudadanos(data.datos);
                    renderPaginacion(data.total, data.page, pageSize);
                    $sinResultados.hide();
                    $listado.show();
                } else {
                    $listado.empty();
                    $sinResultados.show();
                    $paginacion.empty();
                }

                const inicio = (data.page - 1) * pageSize + 1;
                const fin = Math.min(data.page * pageSize, data.total);
                $detallesPagina.text(`Mostrando ${inicio}-${fin} de ${data.total} registros`);
            })
            .fail(function (err) {
                $loading.hide();
                $sinResultados.show();
                console.error("Error al cargar ciudadanos:", err);
                mostrarError("Error al cargar los ciudadanos. Intenta nuevamente.");
            });
    }

    /**
     * Renderizar tabla de ciudadanos
     */
    function renderCiudadanos(ciudadanos) {
        let html = "";

        ciudadanos.forEach((ciudadano, index) => {
            const nombreCompleto = getNombreCompleto(ciudadano);
            const cedula = ciudadano.cedula_act || "N/A";
            const sexo = formatSexo(ciudadano.sexo);
            const estadoCivil = formatEstadoCivil(ciudadano.estado_civil);
            const ocupacion = ciudadano.ocupacion || "N/A";
            const ubicacion = getUbicacion(ciudadano);
            const estadoRegistro = ciudadano.estado_registro == 1 ? "Activo" : "Inactivo";
            const badgeClass = ciudadano.estado_registro == 1 ? "success" : "danger";

            let acciones = '';

            if (!ciudadano.deleted_at) {
                acciones = `
                    <button class="btn btn-sm btn-info me-1" title="Ver" onclick="mostrarDetalles('${ciudadano.id}')">
                        <i class="ri-eye-line"></i>
                    </button>
                    <button class="btn btn-sm btn-warning me-1" title="Editar" onclick="abrirModalEditar('${ciudadano.id}')">
                        <i class="ri-edit-line"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" title="Eliminar" onclick="mostrarModalEliminar('${ciudadano.id}')">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                `;
            } else {
                acciones = `
                    <button class="btn btn-sm btn-success" title="Restaurar" onclick="restaurarCiudadano('${ciudadano.id}')">
                        <i class="ri-restart-line"></i>
                    </button>
                `;
            }

            html += `
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center cursor-pointer" data-ciudadano-id="${ciudadano.id}" title="Ver detalles">
                            <div>
                                <p class="mb-0 fw-bold">${escapeHtml(nombreCompleto)}</p>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(cedula)}</td>
                    <td>${sexo}</td>
                    <td>${estadoCivil}</td>
                    <td>${escapeHtml(ocupacion)}</td>
                    <td class="text-muted small">${escapeHtml(ubicacion)}</td>
                    <td>
                        <span class="badge bg-${badgeClass}">${estadoRegistro}</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            ${acciones}
                        </div>
                    </td>
                </tr>
            `;
        });

        $listado.html(html);
    }

    /**
     * Abrir modal de detalles
     */
    $(document).on("click", ".d-flex.cursor-pointer", function () {
        const ciudadanoId = $(this).data("ciudadano-id");
        mostrarDetalles(ciudadanoId);
    });

    /**
     * Mostrar detalles del ciudadano
     */
    window.mostrarDetalles = function (id) {
        $.ajax({
            url: `/ciudadanos/${id}`,
            type: "GET",
        })
            .done(function (data) {
                $("#modalDetallesContent").html(data);
                const modal = new bootstrap.Modal(document.getElementById("modalDetalles"));
                modal.show();
            })
            .fail(function (err) {
                console.error("Error al obtener detalles:", err);
                mostrarError("Error al cargar los detalles del ciudadano.");
            });
    };

    /**
     * Abrir modal para crear ciudadano
     */
    function abrirModalCrear() {
        $.ajax({
            url: "/ciudadanos/create",
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .done(function (html) {
                $("#modalCiudadanoContent").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalCiudadano"));
                modal.show();
                bindFormulario();
            })
            .fail(function (err) {
                console.error("Error al crear formulario:", err);
                mostrarError("Error al cargar el formulario.");
            });
    }

    /**
     * Abrir modal para editar ciudadano
     */
    window.abrirModalEditar = function (id) {
        $.ajax({
            url: `/ciudadanos/${id}/edit`,
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .done(function (html) {
                $("#modalCiudadanoContent").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalCiudadano"));
                modal.show();
                bindFormulario();
            })
            .fail(function (err) {
                console.error("Error al editar:", err);
                mostrarError("Error al cargar el formulario de edición.");
            });
    };

    /**
     * Mostrar modal de eliminación
     */
    window.mostrarModalEliminar = function (id) {
        $.ajax({
            url: `/ciudadanos/${id}/delete-modal`,
            type: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .done(function (response) {
                const tempDiv = document.createElement("div");
                tempDiv.innerHTML = response;
                const modal = new bootstrap.Modal(tempDiv.querySelector(".modal"));
                modal.show();
                document.body.appendChild(tempDiv);
            })
            .fail(function (err) {
                console.error("Error al cargar modal:", err);
                mostrarError("Error al cargar el modal de eliminación.");
            });
    };

    /**
     * Restaurar ciudadano eliminado
     */
    window.restaurarCiudadano = function (id) {
        Swal.fire({
            title: "¿Restaurar ciudadano?",
            text: "Se restaurará este registro en el sistema.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, restaurar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`/ciudadanos-restore/${id}`, { _token: csrfToken })
                    .done(function (response) {
                        mostrarExito(response.success);
                        cargarCiudadanos(currentPage);
                    })
                    .fail(function (xhr) {
                        const error = xhr.responseJSON?.error || "Error al restaurar el ciudadano.";
                        mostrarError(error);
                    });
            }
        });
    };

    /**
     * Guardar ciudadano (crear o editar)
     */
    window.guardarCiudadano = function ($form, resetForm = false) {
        $form.find(".is-invalid").removeClass("is-invalid");

        const formData = new FormData($form[0]);
        const url = $form.attr("action");
        const $btnGuardar = $("#btnGuardarCiudadano");

        $btnGuardar.prop("disabled", true);
        $btnGuardar.html(
            '<i class="ri-loader-4-line align-middle me-1"></i> Guardando...'
        );

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
                Accept: "application/json",
            },
        })
            .done(function (body) {
                mostrarExito(body.success);

                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("modalCiudadano")
                );
                if (modal) modal.hide();

                cargarCiudadanos(currentPage);

                if (resetForm) {
                    resetearFormulario();
                }
            })
            .fail(function (xhr) {
                $btnGuardar.prop("disabled", false);
                $btnGuardar.html(
                    '<i class="ri-save-line align-middle me-1"></i> Guardar'
                );

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors || {};
                    for (const field in errors) {
                        const $field = $form.find(`[name="${field}"]`);
                        if ($field.length) {
                            $field.addClass("is-invalid");
                            const $feedback = $form.find(`#error-${field}`);
                            if ($feedback.length) {
                                $feedback.text(errors[field][0]).show();
                            }
                        }
                    }
                } else {
                    const error =
                        xhr.responseJSON?.error ||
                        "Error al guardar el ciudadano.";
                    mostrarError(error);
                }
            });
    };

    /**
     * Bind del formulario
     */
    function bindFormulario() {
        const $form = $("#ciudadanoForm");
        const $btnGuardar = $("#btnGuardarCiudadano");

        if ($form.length === 0 || $btnGuardar.length === 0) return;

        $btnGuardar.on("click", function () {
            guardarCiudadano($form);
        });
    }

    /**
     * Resetear formulario
     */
    function resetearFormulario() {
        const $form = $("#ciudadanoForm");
        if ($form.length === 0) return;

        $form[0].reset();
        $form.find(".is-invalid").removeClass("is-invalid");
        $form.find(".invalid-feedback").text("");
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
            html += `<li class="page-item">
                <a class="page-link" href="#" data-page="1">1</a>
            </li>`;
            if (start > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        for (let i = start; i <= end; i++) {
            html += `<li class="page-item ${i === page ? "active" : ""}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        if (end < totalPages) {
            if (end < totalPages - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item">
                <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
            </li>`;
        }

        html += `<li class="page-item ${page >= totalPages ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page + 1}">&raquo;</a>
        </li>`;

        $paginacion.html(html);
    }

    /**
     * Obtener nombre completo
     */
    function getNombreCompleto(ciudadano) {
        const nombres = ciudadano.nombres || "";
        const apPat = ciudadano.ap_pat || "";
        const apMat = ciudadano.ap_mat || "";
        return `${nombres} ${apPat} ${apMat}`.trim();
    }

    /**
     * Obtener ubicación
     */
    function getUbicacion(ciudadano) {
        const ubicaciones = [
            ciudadano.nom_dep,
            ciudadano.nom_prov,
            ciudadano.nom_mun
        ].filter(u => u);
        return ubicaciones.join(" / ") || "N/A";
    }

    /**
     * Formatear sexo
     */
    function formatSexo(sexo) {
        const sexos = {
            "M": "Masculino",
            "F": "Femenino",
            "MASCULINO": "Masculino",
            "FEMENINO": "Femenino",
        };
        return sexos[sexo] || sexo || "N/A";
    }

    /**
     * Formatear estado civil
     */
    function formatEstadoCivil(estado) {
        const estados = {
            "SOLTERO": "Soltero/a",
            "CASADO": "Casado/a",
            "DIVORCIADO": "Divorciado/a",
            "VIUDO": "Viudo/a",
            "UNION_LIBRE": "Unión Libre",
            "CONYUGUE": "Cónyuge",
        };
        return estados[estado] || estado || "N/A";
    }

    /**
     * Escapar HTML para evitar XSS
     */
    function escapeHtml(text) {
        if (!text) return "";
        return $("<div>").text(text).html();
    }

    /**
     * Mostrar mensaje de éxito
     */
    function mostrarExito(mensaje) {
        Swal.fire({
            title: "¡Éxito!",
            text: mensaje,
            icon: "success",
            confirmButtonText: "Ok",
        });
    }

    /**
     * Mostrar mensaje de error
     */
    function mostrarError(mensaje) {
        Swal.fire({
            title: "Error",
            text: mensaje,
            icon: "error",
            confirmButtonText: "Ok",
        });
    }

    /**
     * Manejar formulario de eliminación
     */
    $(document).on("submit", "#formMigrarEliminar", async function (e) {
        e.preventDefault();

        const $form = $(this);
        const url = $form.attr("action");
        const $btnConfirmar = $("#btnConfirmarMigrarEliminar");

        $btnConfirmar.prop("disabled", true);
        $btnConfirmar.html(
            '<i class="ri-loader-4-line align-middle me-1"></i> Procesando...'
        );

        $.ajax({
            url: url,
            type: "POST",
            data: $form.serializeArray(),
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .done(function (response) {
                mostrarExito(response.success);
                const modals = document.querySelectorAll(".modal.show");
                modals.forEach((modal) => {
                    bootstrap.Modal.getInstance(modal)?.hide();
                });
                cargarCiudadanos(currentPage);
            })
            .fail(function (xhr) {
                const error = xhr.responseJSON?.error || "Error al eliminar el ciudadano.";
                mostrarError(error);
                $btnConfirmar.prop("disabled", false);
                $btnConfirmar.html(
                    '<i class="ri-delete-bin-line align-middle me-1"></i> Eliminar'
                );
            });
    });

    /**
     * Event listeners para filtros y búsqueda
     */
    $(document).on(
        "click",
        "#paginacionCiudadanos .page-link[data-page]",
        function (e) {
            e.preventDefault();
            const p = parseInt($(this).data("page"));
            if (p >= 1 && p <= totalPages) {
                cargarCiudadanos(p);
            }
        }
    );

    /**
     * Inicialización al cargar el documento
     */
    $(document).ready(function () {
        cargarCiudadanos();

        if ($btnNuevo.length) {
            $btnNuevo.on("click", abrirModalCrear);
        }

        if ($searchInput.length) {
            $searchInput.on("input", function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    cargarCiudadanos(1);
                }, 500);
            });
        }

        if ($filtroSexo.length) {
            $filtroSexo.on("change", function () {
                cargarCiudadanos(1);
            });
        }

        if ($filtroEstadoCivil.length) {
            $filtroEstadoCivil.on("change", function () {
                cargarCiudadanos(1);
            });
        }

        if ($filtroEstadoRegistro.length) {
            $filtroEstadoRegistro.on("change", function () {
                cargarCiudadanos(1);
            });
        }

        if ($filtroVisible.length) {
            $filtroVisible.on("change", function () {
                cargarCiudadanos(1);
            });
        }
    });
})();
