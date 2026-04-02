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

    const $listado = $("#listadoPersonas");
    const $loading = $("#loadingPersonas");
    const $sinResultados = $("#sinResultados");
    const $paginacion = $("#paginacionPersonas");
    const $detallesPagina = $("#detalles-pagina");
    const $searchInput = $("#searchPersonas");
    const $filtroGenero = $("#filtroGenero");
    const $filtroEstadoCivil = $("#filtroEstadoCivil");
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

        $.ajax({
            url: "/personas",
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

                renderPersonas(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                $detallesPagina.text(`Mostrando ${desde} a ${hasta} de ${data.total} personas`);
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
            const estadoCivil = persona.estado_civil ? formatEstadoCivil(persona.estado_civil) : "—";
            const telefono = persona.telefono || "—";
            const fecha = persona.created_at
                ? new Date(persona.created_at).toLocaleDateString("es-BO", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                })
                : "—";

            const iniciales = nombreCompleto.split(" ").map(word => word.charAt(0).toUpperCase()).join("").substring(0, 2);

            html += /*html */`
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2 cursor-pointer" data-persona-id="${persona.id}">
                            <div class="avatar-xs flex-shrink-0">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-13">
                                    ${iniciales}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">${escapeHtml(nombreCompleto)}</div>
                                <small class="text-muted">${persona.ocupacion ? escapeHtml(persona.ocupacion) : ""}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">
                            ${persona.ci ? escapeHtml(persona.ci) : "—"}
                        </span>
                    </td>
                    <td><span class="badge bg-info">${genero}</span></td>
                    <td><span class="badge bg-warning">${estadoCivil}</span></td>
                    <td>${escapeHtml(telefono)}</td>
                    <td>${fecha}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-soft-info btn-ver" value="${persona.id}"
                                title="Ver detalles">
                                <i class="ri-eye-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-warning btn-editar" value="${persona.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            ${['superadmin', 'administrador'].includes(window.role) ?
                    `<button class="btn btn-sm btn-soft-danger btn-eliminar" value="${persona.id}" title="Eliminar">
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

    $(document).on("click", ".btn-eliminar", async function (e) {
        e.preventDefault();

        const personaId = $(this).val();

        const confirmacion = await confirmarEnvio("Si, Eliminar", "¿Estás seguro de eliminar esta persona? Esta acción no se puede deshacer.");

        if (!confirmacion) {
            return;
        }

        const btn = $(this);

        $.ajax({
            url: `/personas/${personaId}`,
            type: "DELETE",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        }).done(function (body) {

            notification(body.success);

            btn.closest("tr").remove();
        })
            .fail(function (xhr) {
                processError(xhr);

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
            "MASCULINO": "Masculino",
            "FEMENINO": "Femenino"
        };
        return generos[genero] || genero;
    }

    /**
     * Formatear estado civil para mostrar
     */
    function formatEstadoCivil(estado) {
        const estados = {
            "SOLTERO": "Soltero/a",
            "CASADO": "Casado/a",
            "DIVORCIADO": "Divorciado/a",
            "VIUDO": "Viudo/a",
            "CONYUGUE": "Cónyuge"
        };
        return estados[estado] || estado;
    }

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
                cargarPersonas(p);
            }
        });
    }

    function abrirModalCrear() {
        $.ajax({
            url: "/personas/create",
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .done(function (html) {
                $("#modalPersonaContent").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalPersona"));
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
                    const paisBolivia = $paisSelect.find("option").filter(function () {
                        return $(this).text().trim() === "boliviano/a";
                    }).first();
                    if (paisBolivia.length) {
                        $paisSelect.val(paisBolivia.val()).trigger("change");
                    }
                }, 100);

                $("#modalPersona").on("hidden.bs.modal", limpiarFilePond);
            })
            .fail(function (err) {
                console.error("Error:", err);
                Swal.fire("Error", "No se pudo cargar el formulario", "error");
            });
    }

    function abrirModalEditar(id) {
        $.ajax({
            url: `/personas/${id}/edit`,
            type: "GET",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .done(function (html) {
                $("#modalPersonaContent").html(html);
                const modal = new bootstrap.Modal(document.getElementById("modalPersona"));
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
            dataType: "json",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }
        })
            .done(function (data) {
                if (data.datos) {
                    const persona = data.datos;
                    let html = `
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Información Personal</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td>${getNombreCompleto(persona)}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">CI:</td>
                                        <td>${persona.ci || "—"}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Ocupación:</td>
                                        <td>${persona.ocupacion || "—"}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Datos Demográficos</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td class="fw-bold">Género:</td>
                                        <td>${formatGenero(persona.genero) || "—"}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Estado Civil:</td>
                                        <td>${formatEstadoCivil(persona.estado_civil) || "—"}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Fecha Nac.:</td>
                                        <td>${persona.fecha_nacimiento ? new Date(persona.fecha_nacimiento).toLocaleDateString("es-BO") : "—"}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Contacto</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td class="fw-bold">Teléfono:</td>
                                        <td>${persona.telefono || "—"}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Domicilio:</td>
                                        <td>${persona.domicilio || "—"}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Otra Información</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td class="fw-bold">Lugar Nac.:</td>
                                        <td>${persona.lugar_nacimiento || "—"}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">País:</td>
                                        <td>${persona.pais ? persona.pais.pais : "—"}</td>
                                    </tr>
                                </table>
                            </div>
                            ${persona.multimedia && persona.multimedia.length > 0 ? `
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">Fotos</h6>
                                    <div class="row g-2">
                                        ${persona.multimedia.map(foto => `
                                            <div class="col-md-3">
                                                <img src="/storage/${foto.ruta}" class="img-fluid rounded" alt="Foto">
                                            </div>
                                        `).join("")}
                                    </div>
                                </div>
                            ` : ""}
                        </div>
                    `;
                    $("#modalDetallesContent").html(html);
                    const modal = new bootstrap.Modal(document.getElementById("modalDetalles"));
                    modal.show();
                }
            })
            .fail(function (err) {
                console.error("Error:", err);
                Swal.fire("Error", "No se pudieron cargar los detalles", "error");
            });
    };



    function bindFormulario() {
        const $form = $("#personaForm");
        const $btnGuardar = $("#btnGuardarPersona");

        if ($form.length === 0 || $btnGuardar.length === 0) return;

        $btnGuardar.on("click", function () {
            guardarPersona($form);
        });
    }

    function guardarPersona($form) {
        $form.find(".is-invalid").removeClass("is-invalid");

        const formData = new FormData($form[0]);
        const url = $form.attr("action");
        const $btnGuardar = $("#btnGuardarPersona");

        $btnGuardar.prop("disabled", true);
        $btnGuardar.html('<i class="ri-loader-4-line align-middle me-1"></i> Guardando...');

        if (filePondInstance) {
            const files = filePondInstance.getFiles().map(fileItem => fileItem.file);
            files.forEach((file) => formData.append('fotos[]', file));
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
                "Accept": "application/json",
            }
        })
            .done(function (body) {
                $btnGuardar.prop("disabled", false);
                $btnGuardar.html('<i class="ri-save-3-line align-middle me-1"></i> Guardar');



                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: body.success,
                    timer: 2000,
                    showConfirmButton: false,
                });
                const modal = bootstrap.Modal.getInstance(document.getElementById("modalPersona"));
                if (modal) modal.hide();
                cargarPersonas(currentPage);
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

                Swal.fire("Error", body.error || "Ocurrió un error inesperado.", "error");
            });
    }



    window.eliminarFoto = function (fotoId) {
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
                            Swal.fire({
                                icon: "success",
                                title: "Eliminada",
                                text: "Foto eliminada correctamente",
                                timer: 1500,
                                showConfirmButton: false
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
        const inputElement = document.querySelector('#fotos');
        if (!inputElement) return;

        if (filePondInstance) {
            FilePond.destroy(inputElement);
        }

        filePondInstance = FilePond.create(inputElement, {
            storeAsFile: false,
            allowMultiple: true,
            labelIdle: 'Arrastra o sube fotos <br><span class="filepond--label-action">Seleccionar</span>',
            imagePreviewHeight: 140,
            acceptedFileTypes: ['image/*'],
            labelFileTypeNotAllowed: 'Archivo no válido',
            labelIdle: 'Arrastra o sube hasta 4 fotos (opcional)<br><span class="filepond--label-action">Seleccionar</span>',
            allowFileSizeValidation: true,
            maxFiles: 4,
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
            acceptedFileTypes: ['image/*'],
            labelFileTypeNotAllowed: 'Archivo no válido. Solo se permiten imágenes.',
        });
    }

    function limpiarFilePond() {
        if (filePondInstance) {
            FilePond.destroy(document.querySelector('#fotos'));
            filePondInstance = null;
        }
        $("#modalPersona").off("hidden.bs.modal", limpiarFilePond);
    }

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

        const $btnConfirmarEliminar = $("#btnConfirmarEliminar");
        if ($btnConfirmarEliminar.length) {
            $btnConfirmarEliminar.on("click", eliminarPersona);
        }
    });
})();
