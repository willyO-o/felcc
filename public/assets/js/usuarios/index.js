/**
 * Módulo de Gestión de Usuarios
 * CRUD completo con paginación AJAX
 */
(function () {
    "use strict";

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    let currentPage = 1;
    let pageSize = 10;
    let searchTimeout = null;
    let deleteUserId = null;

    // Referencias DOM
    const listado = document.getElementById("listadoUsuarios");
    const loading = document.getElementById("loadingUsuarios");
    const sinResultados = document.getElementById("sinResultados");
    const paginacion = document.getElementById("paginacionUsuarios");
    const detallesPagina = document.getElementById("detalles-pagina");
    const searchInput = document.getElementById("searchUsuarios");
    const filtroRol = document.getElementById("filtroRol");
    const btnNuevo = document.getElementById("btnNuevoUsuario");

    /**
     * Cargar listado de usuarios
     */
    function cargarUsuarios(page = 1) {
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

        if (filtroRol.value) {
            params.append("role_id", filtroRol.value);
        }

        fetch(`/usuarios?${params.toString()}`, {
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

                renderUsuarios(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                detallesPagina.textContent = `Mostrando ${desde} a ${hasta} de ${data.total} usuarios`;
            })
            .catch((err) => {
                loading.style.display = "none";
                console.error("Error cargando usuarios:", err);
            });
    }

    /**
     * Renderizar filas de la tabla
     */
    function renderUsuarios(usuarios) {
        let html = "";
        usuarios.forEach((user, index) => {
            const roleBadge = getRoleBadge(user.role);
            const fecha = user.created_at
                ? new Date(user.created_at).toLocaleDateString("es-BO", {
                      year: "numeric",
                      month: "short",
                      day: "numeric",
                  })
                : "—";

            html += `
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2 flex-shrink-0">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-13">
                                    ${user.name ? user.name.charAt(0).toUpperCase() : "U"}
                                </div>
                            </div>
                            <div class="flex-grow-1">${escapeHtml(user.name)}</div>
                        </div>
                    </td>
                    <td>${escapeHtml(user.email)}</td>
                    <td>${roleBadge}</td>
                    <td>${fecha}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-soft-info btn-editar" data-id="${user.id}" title="Editar">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-danger btn-eliminar" data-id="${user.id}" title="Eliminar">
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
     * Badge de rol con colores
     */
    function getRoleBadge(role) {
        if (!role) return '<span class="badge bg-secondary">Sin Rol</span>';

        const colores = {
            superadmin: "danger",
            administrador: "primary",
            tecnico: "warning",
        };

        const color = colores[role.nombre] || "secondary";
        return `<span class="badge bg-${color}">${ucfirst(role.nombre)}</span>`;
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
                    cargarUsuarios(p);
                }
            });
        });
    }

    /**
     * Abrir modal para nuevo usuario
     */
    function abrirModalCrear() {
        fetch("/usuarios/create", {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((res) => res.text())
            .then((html) => {
                document.getElementById("modalUsuarioContent").innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById("modalUsuario"));
                modal.show();
                bindFormulario();
            });
    }

    /**
     * Abrir modal para editar usuario
     */
    function abrirModalEditar(id) {
        fetch(`/usuarios/${id}/edit`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((res) => res.text())
            .then((html) => {
                document.getElementById("modalUsuarioContent").innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById("modalUsuario"));
                modal.show();
                bindFormulario();
            });
    }

    /**
     * Abrir modal de confirmación de eliminación
     */
    function abrirModalEliminar(id) {
        deleteUserId = id;
        const modal = new bootstrap.Modal(document.getElementById("modalEliminar"));
        modal.show();
    }

    /**
     * Bind eventos del formulario modal
     */
    function bindFormulario() {
        const form = document.getElementById("usuarioForm");
        const btnGuardar = document.getElementById("btnGuardarUsuario");

        if (!form || !btnGuardar) return;

        btnGuardar.addEventListener("click", () => guardarUsuario(form));

        // Toggle visibilidad contraseña
        const passAddon = document.getElementById("password-addon");
        if (passAddon) {
            passAddon.addEventListener("click", () => {
                const input = document.getElementById("password");
                input.type = input.type === "password" ? "text" : "password";
            });
        }

        const passAddonConfirm = document.getElementById("password-addon-confirm");
        if (passAddonConfirm) {
            passAddonConfirm.addEventListener("click", () => {
                const input = document.getElementById("password_confirmation");
                input.type = input.type === "password" ? "text" : "password";
            });
        }
    }

    /**
     * Guardar usuario (crear o actualizar)
     */
    function guardarUsuario(form) {
        // Limpiar errores previos
        form.querySelectorAll(".is-invalid").forEach((el) => el.classList.remove("is-invalid"));

        const formData = new FormData(form);
        const url = form.getAttribute("action");
        const isEdit = form.querySelector('input[name="_method"]');

        const btnGuardar = document.getElementById("btnGuardarUsuario");
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="ri-loader-4-line align-middle me-1 spin"></i> Guardando...';

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
                const modal = bootstrap.Modal.getInstance(document.getElementById("modalUsuario"));
                if (modal) modal.hide();

                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: body.success,
                    timer: 2000,
                    showConfirmButton: false,
                });

                cargarUsuarios(currentPage);
            })
            .catch((err) => {
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = '<i class="ri-save-3-line align-middle me-1"></i> Guardar';
                console.error("Error:", err);
                Swal.fire("Error", "Error de conexión. Intente nuevamente.", "error");
            });
    }

    /**
     * Eliminar usuario
     */
    function eliminarUsuario() {
        if (!deleteUserId) return;

        fetch(`/usuarios/${deleteUserId}`, {
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
                    Swal.fire("Error", body.error || "No se pudo eliminar el usuario.", "error");
                    return;
                }

                Swal.fire({
                    icon: "success",
                    title: "Eliminado",
                    text: body.success,
                    timer: 2000,
                    showConfirmButton: false,
                });

                cargarUsuarios(currentPage);
                deleteUserId = null;
            })
            .catch((err) => {
                console.error("Error:", err);
                Swal.fire("Error", "Error de conexión.", "error");
            });
    }

    // Helpers
    function escapeHtml(text) {
        if (!text) return "";
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    function ucfirst(str) {
        if (!str) return "";
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Init
    document.addEventListener("DOMContentLoaded", function () {
        cargarUsuarios();

        // Botón nuevo usuario
        if (btnNuevo) {
            btnNuevo.addEventListener("click", abrirModalCrear);
        }

        // Búsqueda con debounce
        if (searchInput) {
            searchInput.addEventListener("input", () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => cargarUsuarios(1), 400);
            });
        }

        // Filtro por rol
        if (filtroRol) {
            filtroRol.addEventListener("change", () => cargarUsuarios(1));
        }

        // Confirmar eliminación
        const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");
        if (btnConfirmarEliminar) {
            btnConfirmarEliminar.addEventListener("click", eliminarUsuario);
        }
    });
})();
