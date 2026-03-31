/**
 * Módulo de Gestión de Personas
 * CRUD completo con paginación AJAX, búsqueda y filtros
 */
(function () {
    "use strict";

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    let currentPage = 1;
    let pageSize = 10;
    let searchTimeout = null;
    let deletePersonaId = null;

    // Referencias DOM
    const listado = document.getElementById("listadoPersonas");
    const loading = document.getElementById("loadingPersonas");
    const sinResultados = document.getElementById("sinResultados");
    const paginacion = document.getElementById("paginacionPersonas");
    const detallesPagina = document.getElementById("detalles-pagina");
    const searchInput = document.getElementById("searchPersonas");
    const filtroGenero = document.getElementById("filtroGenero");
    const filtroEstadoCivil = document.getElementById("filtroEstadoCivil");
    const btnNuevo = document.getElementById("btnNuevaPersona");

    /**
     * Cargar listado de personas
     */
    function cargarPersonas(page = 1) {
        currentPage = page;
        loading.style.display = "block";
        sinResultados.style.display = "none";
        listado.innerHTML = "";

        const params = new URLSearchParams({
            page: currentPage,
            size: pageSize,
        });

        if (searchInput.value.trim()) {
            params.append("search", searchInput.value.trim());
        }

        if (filtroGenero.value) {
            params.append("genero", filtroGenero.value);
        }

        if (filtroEstadoCivil.value) {
            params.append("estado_civil", filtroEstadoCivil.value);
        }

        fetch(`/personas?${params.toString()}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                loading.style.display = "none";

                if (!data.datos || data.datos.length === 0) {
                    sinResultados.style.display = "block";
                    paginacion.innerHTML = "";
                    detallesPagina.textContent = "";
                    return;
                }

                renderPersonas(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                detallesPagina.textContent = `Mostrando ${desde} a ${hasta} de ${data.total} personas`;
            })
            .catch((err) => {
                loading.style.display = "none";
                console.error("Error cargando personas:", err);
                Swal.fire("Error", "Error al cargar personas", "error");
            });
    }

    /**
     * Renderizar filas de la tabla
     */
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

            html += `
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2 cursor-pointer" onclick="mostrarDetalles(${persona.id})">
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
                            <button class="btn btn-sm btn-soft-info btn-ver" data-id="${persona.id}"
                                onclick="mostrarDetalles(${persona.id})" title="Ver detalles">
                                <i class="ri-eye-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-warning btn-editar" data-id="${persona.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-danger btn-eliminar" data-id="${persona.id}" title="Eliminar">
                                <i class="ri-delete-bin-fill align-bottom"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        listado.innerHTML = html;

        // Bind eventos
        listado.querySelectorAll(".btn-editar").forEach((btn) => {
            btn.addEventListener("click", () => abrirModalEditar(btn.dataset.id));
        });

        listado.querySelectorAll(".btn-eliminar").forEach((btn) => {
            btn.addEventListener("click", () => abrirModalEliminar(btn.dataset.id));
        });
    }

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

    /**
     * Renderizar paginación
     */
    function renderPaginacion(total, page, size) {
        const totalPages = Math.ceil(total / size);
        let html = "";

        // Anterior
        html += `<li class="page-item ${page <= 1 ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page - 1}">&laquo;</a>
        </li>`;

        // Páginas
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

        // Siguiente
        html += `<li class="page-item ${page >= totalPages ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page + 1}">&raquo;</a>
        </li>`;

        paginacion.innerHTML = html;

        paginacion.querySelectorAll(".page-link[data-page]").forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault();
                const p = parseInt(link.dataset.page);
                if (p >= 1 && p <= totalPages) {
                    cargarPersonas(p);
                }
            });
        });
    }

    /**
     * Abrir modal para nueva persona
     */
    function abrirModalCrear() {
        fetch("/personas/create", {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((res) => res.text())
            .then((html) => {
                document.getElementById("modalPersonaContent").innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById("modalPersona"));
                modal.show();
                bindFormulario();
            })
            .catch((err) => {
                console.error("Error:", err);
                Swal.fire("Error", "No se pudo cargar el formulario", "error");
            });
    }

    /**
     * Abrir modal para editar persona
     */
    function abrirModalEditar(id) {
        fetch(`/personas/${id}/edit`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((res) => res.text())
            .then((html) => {
                document.getElementById("modalPersonaContent").innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById("modalPersona"));
                modal.show();
                bindFormulario();
            })
            .catch((err) => {
                console.error("Error:", err);
                Swal.fire("Error", "No se pudo cargar el formulario", "error");
            });
    }

    /**
     * Mostrar detalles completos de la persona
     */
    window.mostrarDetalles = function (id) {
        fetch(`/personas/${id}`, {
            headers: { "X-Requested-With": "XMLHttpRequest", Accept: "application/json" },
        })
            .then((res) => res.json())
            .then((data) => {
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
                    document.getElementById("modalDetallesContent").innerHTML = html;
                    const modal = new bootstrap.Modal(document.getElementById("modalDetalles"));
                    modal.show();
                }
            })
            .catch((err) => {
                console.error("Error:", err);
                Swal.fire("Error", "No se pudieron cargar los detalles", "error");
            });
    };

    /**
     * Abrir modal de confirmación de eliminación
     */
    function abrirModalEliminar(id) {
        deletePersonaId = id;
        const modal = new bootstrap.Modal(document.getElementById("modalEliminar"));
        modal.show();
    }

    /**
     * Bind eventos del formulario modal
     */
    function bindFormulario() {
        const form = document.getElementById("personaForm");
        const btnGuardar = document.getElementById("btnGuardarPersona");

        if (!form || !btnGuardar) return;

        btnGuardar.addEventListener("click", () => guardarPersona(form));
    }

    /**
     * Guardar persona (crear o actualizar)
     */
    function guardarPersona(form) {
        // Limpiar errores previos
        form.querySelectorAll(".is-invalid").forEach((el) => el.classList.remove("is-invalid"));

        const formData = new FormData(form);
        const url = form.getAttribute("action");

        const btnGuardar = document.getElementById("btnGuardarPersona");
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="ri-loader-4-line align-middle me-1"></i> Guardando...';

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
            body: formData,
        })
            .then((res) => res.json().then((data) => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = '<i class="ri-save-3-line align-middle me-1"></i> Guardar';

                if (status === 422 && body.errors) {
                    // Mostrar errores de validación
                    Object.keys(body.errors).forEach((field) => {
                        const input = form.querySelector(`[name="${field}"]`);
                        const errorDiv = form.querySelector(`#error-${field}`);
                        if (input) input.classList.add("is-invalid");
                        if (errorDiv) errorDiv.textContent = body.errors[field][0];
                    });
                    return;
                }

                if (status >= 400) {
                    Swal.fire("Error", body.error || "Ocurrió un error inesperado.", "error");
                    return;
                }

                // Éxito
                const modal = bootstrap.Modal.getInstance(document.getElementById("modalPersona"));
                if (modal) modal.hide();

                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: body.success,
                    timer: 2000,
                    showConfirmButton: false,
                });

                cargarPersonas(currentPage);
            })
            .catch((err) => {
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = '<i class="ri-save-3-line align-middle me-1"></i> Guardar';
                console.error("Error:", err);
                Swal.fire("Error", "Error de conexión. Intente nuevamente.", "error");
            });
    }

    /**
     * Eliminar persona
     */
    function eliminarPersona() {
        if (!deletePersonaId) return;

        fetch(`/personas/${deletePersonaId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((res) => res.json().then((data) => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById("modalEliminar"));
                if (modal) modal.hide();

                if (status >= 400) {
                    Swal.fire("Error", body.error || "No se pudo eliminar la persona.", "error");
                    return;
                }

                Swal.fire({
                    icon: "success",
                    title: "Eliminado",
                    text: body.success,
                    timer: 2000,
                    showConfirmButton: false,
                });

                cargarPersonas(currentPage);
                deletePersonaId = null;
            })
            .catch((err) => {
                console.error("Error:", err);
                Swal.fire("Error", "Error de conexión.", "error");
            });
    }

    /**
     * Eliminar foto individual
     */
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
                fetch(`/multimedia/${fotoId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            // Remover elemento del DOM
                            document.querySelector(`[data-foto-id="${fotoId}"]`)?.remove();
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

    // Helpers
    function escapeHtml(text) {
        if (!text) return "";
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    // Init
    document.addEventListener("DOMContentLoaded", function () {
        cargarPersonas();

        // Botón nueva persona
        if (btnNuevo) {
            btnNuevo.addEventListener("click", abrirModalCrear);
        }

        // Búsqueda con debounce
        if (searchInput) {
            searchInput.addEventListener("input", () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => cargarPersonas(1), 400);
            });
        }

        // Filtro por género
        if (filtroGenero) {
            filtroGenero.addEventListener("change", () => cargarPersonas(1));
        }

        // Filtro por estado civil
        if (filtroEstadoCivil) {
            filtroEstadoCivil.addEventListener("change", () => cargarPersonas(1));
        }

        // Confirmar eliminación
        const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");
        if (btnConfirmarEliminar) {
            btnConfirmarEliminar.addEventListener("click", eliminarPersona);
        }
    });
})();
