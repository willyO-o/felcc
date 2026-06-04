/**
 * Módulo de Gestión de Personas
 * CRUD completo con paginación AJAX, búsqueda y filtros
 */
(function () {
    "use strict";

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let currentPage = 1;
    let pageSize = 10;
    let searchTimeout = null;
    let filePondInstance = null;
    let totalPages = 0;

    const $listado = $("#listadoPersonas");
    const $loading = $("#loadingPersonas");
    const $sinResultados = $("#sinResultados");
    const $paginacion = $("#paginacionPersonas");
    const $detallesPagina = $("#detalles-pagina");
    const $searchInput = $("#searchPersonas");
    const $filtroGenero = $("#filtroGenero");
    const $filtroEstadoCivil = $("#filtroEstadoCivil");
    const $filtroVisible = $("#filtroVisible");
    const $btnNuevo = $("#btnNuevaPersona");

    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
    );

    function cargarPersonas(page = 1) {
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

        if ($filtroGenero.val()) {
            params.genero = $filtroGenero.val();
        }

        if ($filtroEstadoCivil.val()) {
            params.estado_civil = $filtroEstadoCivil.val();
        }
        if ($filtroVisible.val()) {
            params.visible = $filtroVisible.val();
        }
        if ($("#filtros").val()) {
            params.filtro = $("#filtros").val();
        }

        $.ajax({
            url: "/personas",
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

                if (!data.datos || data.datos.length === 0) {
                    $sinResultados.show();
                    $paginacion.empty();
                    $detallesPagina.text("");
                    return;
                }

                renderPersonas(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                $detallesPagina.text(
                    `Mostrando ${desde} a ${hasta} de ${data.total} personas`,
                );
            })
            .fail(function (err) {
                $loading.hide();
                console.error("Error cargando personas:", err);
                Swal.fire("Error", "Error al cargar personas", "error");
            });
    }

    function renderPersonas(personas) {
        let html = "";
        personas.forEach((persona, index) => {
            const nombreCompleto = getNombreCompleto(persona);
            const genero = persona.genero ? formatGenero(persona.genero) : "—";
            const estadoCivil = persona.estado_civil
                ? formatEstadoCivil(persona.estado_civil)
                : "—";
            const telefono = persona.telefono || "—";
            const fecha = persona.created_at
                ? new Date(persona.created_at).toLocaleDateString("es-BO", {
                      year: "numeric",
                      month: "short",
                      day: "numeric",
                  })
                : "—";

            const iniciales = nombreCompleto
                .split(" ")
                .map((word) => word.charAt(0).toUpperCase())
                .join("")
                .substring(0, 2);
            const primeraImagen =
                persona.multimedia && persona.multimedia.length > 0
                    ? persona.multimedia[0].ruta
                    : null;

            // Lógica para el botón de documento
            let documentoHtml = "";
            if (persona.url_documento && persona.url_documento.trim()) {
                documentoHtml = `
                    <button class="btn btn-sm btn-soft-success btn-abrir-documento"
                        data-url="${escapeHtml(persona.url_documento)}"
                        title="Abrir documento">
                        <i class="ri-download-cloud-fill align-bottom"></i>
                    </button>
                `;
            } else {
                documentoHtml = `
                    <button class="btn btn-sm btn-soft-secondary btn-vincular-documento"
                        value="${persona.id}"
                        title="Vincular documento">
                        <i class="ri-link align-bottom"></i>
                    </button>
                `;
            }

            html += /*html */ `
                <tr class="${persona.deleted_at ? "table-danger" : ""}">
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2 cursor-pointer" data-persona-id="${persona.id}">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-13">
                                    ${
                                        primeraImagen
                                            ? `<img src="/storage/${primeraImagen}" alt="Foto de ${escapeHtml(nombreCompleto)}" class="avatar-sm rounded-circle">`
                                            : escapeHtml(iniciales)
                                    }
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">${escapeHtml(nombreCompleto)}</div>
                                <small class="text-muted">${persona.ocupacion ? escapeHtml(persona.ocupacion) : ""}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="">
                            ${persona.ci ? escapeHtml(persona.ci + (persona.complemento ? `-${persona.complemento}` : "")) : "—"}
                        </span>
                    </td>
                    <td>
                        <span class="">
                            ${persona.alias ? escapeHtml(persona.alias) : "—"}
                        </span>
                    </td>
                    <td><span class="">${genero}</span></td>
                    <td><span class=" ">${estadoCivil}</span></td>
                    <td>${escapeHtml(telefono)}</td>
                    <td>${fecha}</td>
                    <td class="text-center">
                        ${documentoHtml}
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">

                            <button class="btn btn-sm btn-soft-info btn-ver" value="${persona.id}"
                                title="Ver detalles">
                                <i class="ri-eye-fill align-bottom"></i>
                            </button>

                            ${
                                [
                                    "superadmin",
                                    "administrador",
                                    "tecnico_daci",
                                ].includes(window.role)
                                    ? /*html*/ `
                            <button class="${persona.deleted_at ? "d-none" : ""} btn btn-sm btn-soft-warning btn-editar" value="${persona.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            `
                                    : ""
                            }
                            ${
                                ["superadmin", "administrador"].includes(
                                    window.role,
                                )
                                    ? /*html*/ `
                            <button class="${persona.deleted_at ? "d-none" : ""} btn btn-sm btn-soft-danger btn-eliminar" value="${persona.id}" title="Eliminar">
                                <i class="ri-delete-bin-fill align-bottom"></i>
                            </button>
                            ${
                                persona.deleted_at
                                    ? /*html*/ `
                            <button class="btn btn-sm btn-soft-info btn-recuperar" value="${persona.id}" title="Recuperar">
                                <i class="ri-refresh-line align-bottom"></i>
                            </button>
                            `
                                    : ""
                            }`
                                    : ""
                            }
                        </div>
                    </td>
                </tr>
            `;
        });
        $listado.html(html);
    }
    $(document).on("click", ".d-flex.cursor-pointer", function () {
        const personaId = $(this).data("persona-id");
        mostrarDetalles(personaId);
    });
    $(document).on("click", ".btn-ver", function () {
        mostrarDetalles($(this).val());
    });

    $(document).on("click", ".btn-editar", function () {
        abrirModalEditar($(this).val());
    });

    $(document).on("click", ".btn-eliminar", function (e) {
        e.preventDefault();

        const personaId = $(this).val();

        // Cargar datos del modal
        $.ajax({
            url: `/personas/${personaId}/delete-modal`,
            type: "GET",
            // dataType: "json",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                // Accept: "application/json",
            },
        }).done(function (response) {
            $("#modalPersonaContent").html(response);
            const modal = new bootstrap.Modal(
                document.getElementById("modalPersona"),
            );
            modal.show();
            if (!$("#personaMigrar").data("select2")) {
                inicializarSelectPersona();
            }
        });
    });

    /**
     * Abrir documento en nueva ventana
     */
    $(document).on("click", ".btn-abrir-documento", function (e) {
        e.preventDefault();
        const url = $(this).data("url");
        if (url && url.trim()) {
            window.open(url, "_blank");
        } else {
            Swal.fire("Error", "No hay URL válida para el documento", "error");
        }
    });

    /**
     * Vincular documento
     */
    $(document).on("click", ".btn-vincular-documento", function (e) {
        e.preventDefault();
        const personaId = $(this).val();
        abrirModalVincularDocumento(personaId);
    });

    /**
     * Abrir modal para vincular documento
     */
    function abrirModalVincularDocumento(personaId) {
        const $modal = $("#modalVincularDocumento");
        const $form = $("#formVincularDocumento");

        if ($modal.length === 0) {
            Swal.fire("Error", "Modal no encontrado", "error");
            return;
        }

        // Limpiar formulario
        $form[0].reset();
        $form.find(".is-invalid").removeClass("is-invalid");
        $form.find(".invalid-feedback").text("");

        // Guardar ID de la persona en el formulario
        $form.data("persona-id", personaId);

        // Mostrar modal
        const modal = new bootstrap.Modal($modal[0]);
        modal.show();
    }

    /**
     * Enviar formulario de vincular documento
     */
    $(document).on("submit", "#formVincularDocumento", function (e) {
        e.preventDefault();

        const $form = $(this);
        const personaId = $form.data("persona-id");
        const urlDocumento = $("#urlDocumento").val().trim();

        // Limpiar errores previos
        $form.find(".is-invalid").removeClass("is-invalid");
        $form.find(".invalid-feedback").text("");

        // Validar
        if (!urlDocumento) {
            $("#urlDocumento").addClass("is-invalid");
            $("#error-url_documento").text("Ingresa una URL válida");
            return;
        }

        // Validar que sea una URL válida
        try {
            new URL(urlDocumento);
        } catch (_) {
            $("#urlDocumento").addClass("is-invalid");
            $("#error-url_documento").text(
                "Ingresa una URL válida (ej: https://drive.google.com/...)",
            );
            return;
        }

        const $btnVincular = $("#btnVincularDocumento");
        $btnVincular.prop("disabled", true);
        $btnVincular.html(
            '<i class="ri-loader-4-line align-middle me-1"></i> Guardando...',
        );

        const data = {
            url_documento: urlDocumento,
            _token: csrfToken,
        };

        $.ajax({
            url: `/personas/${personaId}`,
            type: "PATCH",
            data: data,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .done(function (body) {
                $btnVincular.prop("disabled", false);
                $btnVincular.html(
                    '<i class="ri-link align-middle me-1"></i> Vincular',
                );

                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: body.success || "Documento vinculado correctamente",
                    timer: 2000,
                    showConfirmButton: false,
                });

                cargarPersonas(currentPage);

                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("modalVincularDocumento"),
                );
                if (modal) modal.hide();
            })
            .fail(function (xhr) {
                $btnVincular.prop("disabled", false);
                $btnVincular.html(
                    '<i class="ri-link align-middle me-1"></i> Vincular',
                );

                const body = xhr.responseJSON;

                if (body.error) {
                    Swal.fire("Error", body.error, "error");
                } else {
                    processError(xhr);
                }
            });
    });

    /**
     * Obtener nombre completo de la persona
     */
    function getNombreCompleto(persona) {
        const nombres = persona.nombres || "";
        const apellidos = persona.apellidos || "";
        return `${nombres} ${apellidos}`.trim();
    }

    /**
     * Formatear género para mostrar
     */
    function formatGenero(genero) {
        const generos = {
            MASCULINO: "Masculino",
            FEMENINO: "Femenino",
        };
        return generos[genero] || genero;
    }

    /**
     * Formatear estado civil para mostrar
     */
    function formatEstadoCivil(estado) {
        const estados = {
            SOLTERO: "Soltero/a",
            CASADO: "Casado/a",
            DIVORCIADO: "Divorciado/a",
            VIUDO: "Viudo/a",
            CONYUGUE: "Cónyuge",
        };
        return estados[estado] || estado;
    }

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
            if (start > 2)
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }

        for (let i = start; i <= end; i++) {
            html += `<li class="page-item ${i === page ? "active" : ""}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        if (end < totalPages) {
            if (end < totalPages - 1)
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }

        html += `<li class="page-item ${page >= totalPages ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page + 1}">&raquo;</a>
        </li>`;

        $paginacion.html(html);
    }

    function abrirModalCrear() {
        $.ajax({
            url: "/personas/create",
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .done(function (html) {
                $("#modalPersonaContent").html(html);
                const modal = new bootstrap.Modal(
                    document.getElementById("modalPersona"),
                );
                modal.show();

                setTimeout(() => {
                    inicializarFilePond();
                    bindFormulario();
                    $("#id_pais").select2({
                        dropdownParent: $("#modalPersona"),
                        width: "100%",
                        theme: "bootstrap-5",
                        placeholder: "Selecciona un país",
                        allowClear: true,
                    });

                    const $paisSelect = $("#id_pais");
                    const paisBolivia = $paisSelect
                        .find("option")
                        .filter(function () {
                            return $(this).text().trim() === "boliviano/a";
                        })
                        .first();
                    if (paisBolivia.length) {
                        $paisSelect.val(paisBolivia.val()).trigger("change");
                    }
                }, 100);

                $("#modalPersona").on("hidden.bs.modal", limpiarFilePond);
            })
            .fail(function (err) {
                console.error("Error:", err);

                processError(err);
            });
    }

    function abrirModalEditar(id) {
        $.ajax({
            url: `/personas/${id}/edit`,
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .done(function (html) {
                $("#modalPersonaContent").html(html);
                const modal = new bootstrap.Modal(
                    document.getElementById("modalPersona"),
                );
                modal.show();

                setTimeout(() => {
                    inicializarFilePond();
                    bindFormulario();
                    $("#id_pais").select2({
                        dropdownParent: $("#modalPersona"),
                        width: "100%",
                        theme: "bootstrap-5",
                        placeholder: "Selecciona un país",
                        allowClear: true,
                    });
                }, 100);

                $("#modalPersona").on("hidden.bs.modal", limpiarFilePond);
            })
            .fail(function (err) {
                console.error("Error:", err);
                Swal.fire("Error", "No se pudo cargar el formulario", "error");
            });
    }

    window.mostrarDetalles = function (id) {
        $.ajax({
            url: `/personas/${id}`,
            type: "GET",
        })
            .done(function (data) {
                $("#modalDetallesContent").html(data);
                const modal = new bootstrap.Modal(
                    document.getElementById("modalDetalles"),
                );
                modal.show();
            })
            .fail(function (err) {
                console.error("Error:", err);
                Swal.fire(
                    "Error",
                    "No se pudieron cargar los detalles",
                    "error",
                );
            });
    };

    function bindFormulario() {
        const $form = $("#personaForm");
        const $btnGuardar = $("#btnGuardarPersona");
        const $btnGuardarReset = $("#btnGuardarReset");

        if ($form.length === 0 || $btnGuardar.length === 0) return;

        $btnGuardar.on("click", function () {
            guardarPersona($form);
        });
        $btnGuardarReset.on("click", function () {
            guardarPersona($form, true);
        });
    }

    function resetearFormulario() {
        const $form = $("#personaForm");
        if ($form.length === 0) return;

        $form[0].reset();
        $form.find(".is-invalid").removeClass("is-invalid");

        if (filePondInstance) {
            filePondInstance.removeFiles();
        }
    }

    function guardarPersona($form, resetForm = false) {
        $form.find(".is-invalid").removeClass("is-invalid");

        const formData = new FormData($form[0]);
        const url = $form.attr("action");
        const $btnGuardar = $("#btnGuardarPersona");
        const $btnGuardarReset = $("#btnGuardarReset");

        if (resetForm) {
            $btnGuardarReset.prop("disabled", true);
            $btnGuardarReset.html(
                '<i class="ri-loader-4-line align-middle me-1"></i> Guardando...',
            );
        }
        $btnGuardar.prop("disabled", true);
        $btnGuardar.html(
            '<i class="ri-loader-4-line align-middle me-1"></i> Guardando...',
        );

        if (filePondInstance) {
            const files = filePondInstance
                .getFiles()
                .map((fileItem) => fileItem.file);
            files.forEach((file) => formData.append("fotos[]", file));
        }

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
                $btnGuardar.prop("disabled", false);
                $btnGuardar.html(
                    '<i class="ri-save-3-line align-middle me-1"></i> Guardar',
                );

                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: body.success,
                    timer: 2000,
                    showConfirmButton: false,
                });
                cargarPersonas(currentPage);

                if (resetForm) {
                    $btnGuardarReset.prop("disabled", false);
                    $btnGuardarReset.html(
                        '<i class="ri-save-3-line align-middle me-1"></i> Guardar y Registrar Nuevo',
                    );
                    resetearFormulario();
                    return;
                }
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("modalPersona"),
                );
                if (modal) modal.hide();
            })
            .fail(function (xhr) {
                $btnGuardar.prop("disabled", false);
                $btnGuardar.html(
                    '<i class="ri-save-3-line align-middle me-1"></i> Guardar',
                );

                const body = xhr.responseJSON;

                if (xhr.status === 422 && body.errors) {
                    Object.keys(body.errors).forEach((field) => {
                        const $input = $form.find(`[name="${field}"]`);
                        const $errorDiv = $form.find(`#error-${field}`);
                        if ($input.length) $input.addClass("is-invalid");
                        if ($errorDiv.length)
                            $errorDiv.text(body.errors[field][0]);
                    });
                    return;
                }

                Swal.fire(
                    "Error",
                    body.error || "Ocurrió un error inesperado.",
                    "error",
                );
            });
    }

    window.eliminarFoto = function (fotoId) {
        Swal.fire({
            title: "¿Eliminar foto?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/multimedia/${fotoId}`,
                    type: "DELETE",
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                    },
                }).done(function (data) {
                    if (data.success) {
                        $(`[data-foto-id="${fotoId}"]`).remove();
                        Swal.fire({
                            icon: "success",
                            title: "Eliminada",
                            text: "Foto eliminada correctamente",
                            timer: 1500,
                            showConfirmButton: false,
                        });
                    }
                });
            }
        });
    };

    function escapeHtml(text) {
        if (!text) return "";
        return $("<div>").text(text).html();
    }

    function inicializarFilePond() {
        const inputElement = document.querySelector("#fotos");
        if (!inputElement) return;

        if (filePondInstance) {
            FilePond.destroy(inputElement);
        }

        filePondInstance = FilePond.create(inputElement, {
            storeAsFile: false,
            allowMultiple: true,
            labelIdle:
                'Arrastra o sube fotos <br><span class="filepond--label-action">Seleccionar</span>',
            imagePreviewHeight: 140,
            acceptedFileTypes: ["image/*"],
            labelFileTypeNotAllowed: "Archivo no válido",
            labelIdle:
                'Arrastra o sube hasta 4 fotos (opcional)<br><span class="filepond--label-action">Seleccionar</span>',
            allowFileSizeValidation: true,
            maxFiles: 4,
            maxFileSize: "2MB",
            labelMaxFileSize: "Tamaño máximo de archivo es {filesize}",
            labelMaxFileSizeExceeded: "Archivo demasiado grande",
            imageResizeTargetWidth: 200,
            imageResizeTargetHeight: 200,
            stylePanelLayout: "compact stacked",
            styleLoadIndicatorPosition: "center bottom",
            styleProgressIndicatorPosition: "right bottom",
            styleButtonRemoveItemPosition: "left bottom",
            styleButtonProcessItemPosition: "right bottom",
            acceptedFileTypes: ["image/*"],
            labelFileTypeNotAllowed:
                "Archivo no válido. Solo se permiten imágenes.",
        });
    }

    function limpiarFilePond() {
        if (filePondInstance) {
            FilePond.destroy(document.querySelector("#fotos"));
            filePondInstance = null;
        }
        $("#modalPersona").off("hidden.bs.modal", limpiarFilePond);
    }

    /**
     * Manejar envío del formulario de migración y eliminación
     */
    $(document).on("submit", "#formMigrarEliminar", async function (e) {
        e.preventDefault();

        const $form = $(this);

        const url = $form.attr("action");

        const confirmacion = await confirmarEnvio(
            "Si, Eliminar",
            `¿Estás seguro de eliminar este registro?`,
            "¡Sí, eliminar!",
            "Cancelar",
            "warning",
        );

        if (!confirmacion) return;

        const $btnConfirmar = $("#btnConfirmarMigrarEliminar");
        $btnConfirmar.prop("disabled", true);
        $btnConfirmar.html(
            '<i class="ri-loader-4-line align-middle me-1"></i> Procesando...',
        );
        $.ajax({
            url: url,
            type: "POST",
            // dataType: "json",
            data: $form.serializeArray(),
        })
            .done(function (response) {
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("modalPersona"),
                );
                if (modal) modal.hide();

                notification(response.success, "Mandamiento Eliminado");
                cargarPersonas(currentPage);
            })
            .fail(function (xhr) {
                processError(xhr);
            })
            .always(function () {
                $btnConfirmar.prop("disabled", false);
                $btnConfirmar.html(
                    '<i class="ri-delete-bin-fill align-middle me-1"></i> Confirmar Eliminación',
                );
            });
    });

    /**
     * Paginación - Solo se registra una vez
     */
    $(document).on(
        "click",
        "#paginacionPersonas .page-link[data-page]",
        function (e) {
            e.preventDefault();
            const p = parseInt($(this).data("page"));
            if (p >= 1 && p <= totalPages) {
                cargarPersonas(p);
            }
        },
    );

    $(document).ready(function () {
        cargarPersonas();

        if ($btnNuevo.length) {
            $btnNuevo.on("click", abrirModalCrear);
        }

        if ($searchInput.length) {
            $searchInput.on("input", function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => cargarPersonas(1), 400);
            });
        }

        if ($filtroGenero.length) {
            $filtroGenero.on("change", function () {
                cargarPersonas(1);
            });
        }

        if ($filtroEstadoCivil.length) {
            $filtroEstadoCivil.on("change", function () {
                cargarPersonas(1);
            });
        }
        if ($filtroVisible.length) {
            $filtroVisible.on("change", function () {
                cargarPersonas(1);
            });
        }

        const $btnConfirmarEliminar = $("#btnConfirmarEliminar");
        if ($btnConfirmarEliminar.length) {
            $btnConfirmarEliminar.on("click", eliminarPersona);
        }
    });

    $(document).on("input", "#ci, #complemento", function () {
        if ($("#ci").val().trim().length < 4) {
            return;
        }

        const data = {
            ci: $("#ci").val().trim(),
            complemento: $("#complemento").val().trim(),
        };

        $.get("/personas-check-ci", data).done(function (response) {
            if (response.data) {
                $("#ci").addClass("is-invalid");
                $("#error-ci").text(
                    "Ya existe una persona con este CI y complemento",
                ).show();
            } else {
                $("#ci").removeClass("is-invalid");
                $("#error-ci").hide();
            }
        });
    });

    $(document).on("click", ".btn-recuperar", async function (e) {
        e.preventDefault();

        const personaId = $(this).val();

        const confirmacion = await confirmarEnvio(
            "Sí, recuperar",
            "¿Estás seguro de recuperar este registro?",
            "¡Sí, recuperar!",
            "Cancelar",
            "question",
        );

        if (!confirmacion) return;

        $.post(`/personas-restore/${personaId}`, { _token: csrfToken })
            .done(function (response) {
                notification(response.success, "Persona Recuperada");
                cargarPersonas(currentPage);
            })
            .fail(function (xhr) {
                processError(xhr);
            });



    });

    function inicializarSelectPersona() {
        $("#personaMigrar").select2({
            dropdownParent: $("#modalPersonaContent"),
            placeholder: "Seleccione una persona",
            allowClear: true,
            language: {
                noResults: function () {
                    return "No se encontraron personas";
                },
            },
            ajax: {
                url: "/personas-search",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // término de búsqueda
                        id: $("#persona_id").val(), // ID de la persona actual para excluirla de los resultados
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (persona) {
                            return {
                                id: persona.id,
                                text: `${persona.nombres} ${persona.apellidos || ""} - CI: ${persona.ci || ""}`,
                            };
                        }),
                    };
                },
                cache: true,
            },
            minimumInputLength: 2,
        });
    }
})();
