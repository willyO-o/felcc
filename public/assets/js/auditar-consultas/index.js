/**
 * Módulo de Auditoría de Consultas
 * CRUD de solo lectura con paginación AJAX, búsqueda y filtros
 */
(function () {
    "use strict";

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let currentPage = 1;
    let pageSize = 10;
    let searchTimeout = null;
    let totalPages = 0;

    const $listado = $("#listadoAuditoria");
    const $loading = $("#loadingAuditoria");
    const $sinResultados = $("#sinResultados");
    const $paginacion = $("#paginacionAuditoria");
    const $detallesPagina = $("#detalles-pagina");
    const $searchInput = $("#searchAuditoria");
    const $filtroModulo = $("#filtroModulo");
    const $filtroRol = $("#filtroRol");
    const $btnFiltrosAvanzados = $("#btnFiltrosAvanzados");
    const $filtrosAvanzados = $("#filtrosAvanzados");
    const $fechaInicio = $("#fechaInicio");
    const $fechaFin = $("#fechaFin");

    /**
     * Cargar auditorías con paginación y filtros
     */
    function cargarAuditorias(page = 1) {
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

        if ($filtroModulo.val()) {
            params.modulo = $filtroModulo.val();
        }

        if ($filtroRol.val()) {
            params.rol_usuario = $filtroRol.val();
        }

        if ($fechaInicio.val()) {
            params.fecha_inicio = $fechaInicio.val();
        }

        if ($fechaFin.val()) {
            params.fecha_fin = $fechaFin.val();
        }

        $.ajax({
            url: "/auditar-consultas",
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

                renderAuditorias(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                $detallesPagina.text(`Mostrando ${desde} a ${hasta} de ${data.total} registros`);
            })
            .fail(function (err) {
                $loading.hide();
                console.error("Error cargando auditorías:", err);
                Swal.fire("Error", "Error al cargar auditorías", "error");
            });
    }

    /**
     * Renderizar la tabla de auditorías
     */
    function renderAuditorias(auditorias) {
        let html = "";
        auditorias.forEach((auditoria, index) => {
            const nombreUsuario = auditoria.usuario ? auditoria.usuario.name : "Desconocido";
            const rol = escapeHtml(auditoria.rol_usuario || "—");
            const modulo = escapeHtml(auditoria.modulo || "—");
            const cantidad = auditoria.cantidad_resultados || 0;
            const user_agent = auditoria.user_agent;
            const fecha = auditoria.created_at
                ? new Date(auditoria.created_at).toLocaleDateString("es-BO", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                })
                : "—";
            const hora = auditoria.created_at
                ? new Date(auditoria.created_at).toLocaleTimeString("es-BO")
                : "—";

            const badge = `<span class="badge badge-outline-secondary">${cantidad}</span>`;

            html += /*html */`
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td>
                        <div>
                            <strong>${escapeHtml(nombreUsuario)}</strong>
                            <small class="text-muted d-block">${escapeHtml(auditoria.usuario?.email || "")}</small>
                        </div>
                    </td>
                    <td><span class="badge badge-outline-secondary">${rol}</span></td>
                    <td><span class="badge badge-outline-primary">${modulo}</span></td>
                    <td class="text-center">${badge}</td>
                    <td><code class="text-muted">${user_agent ? `${user_agent.browser} (${user_agent.platform})` : "—"}</code></td>
                    <td>
                        <small class="text-muted">
                            ${fecha}<br>
                            ${hora}
                        </small>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-soft-info btn-ver-detalles" data-auditoria-id="${auditoria.id}"
                            title="Ver detalles">
                            <i class="ri-eye-fill align-bottom"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $listado.html(html);
    }

    /**
     * Evento para ver detalles de auditoría
     */
    $(document).on("click", ".btn-ver-detalles", function () {
        mostrarDetalles($(this).data("auditoria-id"));
    });

    /**
     * Mostrar detalles de una auditoría
     */
    window.mostrarDetalles = function (id) {
        $.ajax({
            url: `/auditar-consultas/${id}`,
            type: "GET",
            dataType: "json",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        })
            .done(function (auditoria) {
                renderModalDetalles(auditoria);
                const modal = new bootstrap.Modal(document.getElementById("modalDetalles"));
                modal.show();
            })
            .fail(function (err) {
                console.error("Error:", err);
                Swal.fire("Error", "No se pudieron cargar los detalles", "error");
            });
    };

    /**
     * Renderizar el modal con detalles de la auditoría
     */
    function renderModalDetalles(auditoria) {
        const $content = $("#modalDetallesContent");

        // Procesar criterios de búsqueda
        const criterios = auditoria.criterios_consulta || {};
        let criteriasHtml = '<dl class="row">';
        if (criterios.busqueda) {
            criteriasHtml += `
                <dt class="col-sm-3">Texto de Búsqueda:</dt>
                <dd class="col-sm-9"><strong>${escapeHtml(criterios.busqueda)}</strong></dd>
            `;
        }
        if (criterios.tipo_filtro) {
            criteriasHtml += `
                <dt class="col-sm-3">Tipo Filtro:</dt>
                <dd class="col-sm-9">${escapeHtml(criterios.tipo_filtro)}</dd>
            `;
        }
        if (criterios.fecha_inicio) {
            criteriasHtml += `
                <dt class="col-sm-3">Fecha Inicio:</dt>
                <dd class="col-sm-9">${escapeHtml(criterios.fecha_inicio)}</dd>
            `;
        }
        if (criterios.fecha_fin) {
            criteriasHtml += `
                <dt class="col-sm-3">Fecha Fin:</dt>
                <dd class="col-sm-9">${escapeHtml(criterios.fecha_fin)}</dd>
            `;
        }
        criteriasHtml += '</dl>';

        // Procesar IDs accedidos
        let idsAccedidosHtml = '<div class="alert alert-info">No hay registros accedidos</div>';
        if (auditoria.ids_accedidos_resueltos && auditoria.ids_accedidos_resueltos.length > 0) {
            idsAccedidosHtml = `
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Modulo</th>
                                <th>Información Accedida</th>
                                <th>Fecha Acceso</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            auditoria.ids_accedidos_resueltos.forEach(item => {
                idsAccedidosHtml += `
                    <tr>
                        <td><code>${item.id}</code></td>
                        <td><span class="badge badge-soft-success badge-border">${escapeHtml(item.modelo)}</span></td>
                        <td>${item.descripcion}</td>
                        <td><small>${formatearFecha(item.fecha_acceso)}</small></td>
                    </tr>
                `;
            });
            idsAccedidosHtml += `
                        </tbody>
                    </table>
                </div>
            `;
        }

        const fechaHora = new Date(auditoria.created_at).toLocaleString("es-BO");

        const html = /*html */`
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Auditoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Información del Usuario</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Usuario:</dt>
                            <dd class="col-sm-8"><strong>${escapeHtml(auditoria.usuario.name)}</strong></dd>
                            <dt class="col-sm-4">Email:</dt>
                            <dd class="col-sm-8"><code>${escapeHtml(auditoria.usuario.email)}</code></dd>
                            <dt class="col-sm-4">Rol:</dt>
                            <dd class="col-sm-8"><span class="badge badge-outline-secondary">${escapeHtml(auditoria.rol_usuario)}</span></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Información de la Consulta</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Módulo Accedido:</dt>
                            <dd class="col-sm-8"><span class="badge badge-outline-primary">${escapeHtml(auditoria.modulo)}</span></dd>
                            <dt class="col-sm-4">Cantidad Resultados:</dt>
                            <dd class="col-sm-8"><span class="badge badge-outline-success">${auditoria.cantidad_resultados}</span></dd>
                            <dt class="col-sm-4">Fecha/Hora:</dt>
                            <dd class="col-sm-8"><small class="text-muted">${fechaHora}</small></dd>
                        </dl>
                    </div>
                </div>

                <hr>

                <h6 class="text-muted mb-2">Criterios de Búsqueda</h6>
                ${criteriasHtml}

                <hr>

                <h6 class="text-muted mb-2">Registros Accedidos</h6>
                ${idsAccedidosHtml}

                <hr>

                <h6 class="text-muted mb-2">Información Técnica del Consultor <small>(los datos pueden ser imprecisos)</small></h6>
                <dl class="row">
                    <dt class="col-sm-3">Dirección IP:</dt>
                    <dd class="col-sm-9"><code>${escapeHtml(auditoria.ip_usuario)}</code></dd>
                    ${auditoria.location_info ? `
                        <dt class="col-sm-3">Ubicación:</dt>
                        <dd class="col-sm-9">
                            <small class="text-muted">
                                ${escapeHtml(auditoria.location_info.cityName)} - ${escapeHtml(auditoria.location_info.countryName)}
                            </small>
                        </dd>
                    ` : ''}
                    <dt class="col-sm-3">Información del Navegador:</dt>
                    <dd class="col-sm-9"><small class="text-muted text-break">${auditoria.user_agent ? `Navegador: ${auditoria.user_agent.browser}| S.O.: (${auditoria.user_agent.platform})| Dispositivo: ${auditoria.user_agent.device}` : "—"}</small></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line align-middle me-1"></i> Cerrar
                </button>
            </div>
        `;

        $content.html(html);
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
     * Cargar opciones de filtros dinámicamente
     */
    function cargarFiltros() {
        // Cargar módulos
        $.ajax({
            url: "/auditar-consultas/filtros/modulos",
            type: "GET",
            dataType: "json",
        })
            .done(function (data) {
                data.modulos.forEach(modulo => {
                    $filtroModulo.append(`<option value="${modulo}">${escapeHtml(modulo)}</option>`);
                });
            })
            .fail(function (err) {
                console.warn("Error cargando módulos:", err);
            });

        // Cargar roles
        $.ajax({
            url: "/auditar-consultas/filtros/roles",
            type: "GET",
            dataType: "json",
        })
            .done(function (data) {
                data.roles.forEach(rol => {
                    $filtroRol.append(`<option value="${rol}">${escapeHtml(rol)}</option>`);
                });
            })
            .fail(function (err) {
                console.warn("Error cargando roles:", err);
            });
    }

    /**
     * Escape HTML para evitar XSS
     */
    function escapeHtml(text) {
        if (!text) return "";
        return $("<div>").text(text).html();
    }

    /**
     * Formatear fecha/hora
     */
    function formatearFecha(fecha) {
        if (!fecha) return "—";
        return new Date(fecha).toLocaleString("es-BO", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit"
        });
    }

    /**
     * Eventos de paginación
     */
    $(document).on("click", "#paginacionAuditoria .page-link[data-page]", function (e) {
        e.preventDefault();
        const p = parseInt($(this).data("page"));
        if (p >= 1 && p <= totalPages) {
            cargarAuditorias(p);
        }
    });

    /**
     * Eventos de búsqueda y filtros
     */
    $(document).ready(function () {
        // Cargar auditorías iniciales
        cargarAuditorias();

        // Cargar opciones de filtros
        cargarFiltros();

        // Búsqueda
        if ($searchInput.length) {
            $searchInput.on("input", function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => cargarAuditorias(1), 400);
            });
        }

        // Filtro de módulo
        if ($filtroModulo.length) {
            $filtroModulo.on("change", function () {
                cargarAuditorias(1);
            });
        }

        // Filtro de rol
        if ($filtroRol.length) {
            $filtroRol.on("change", function () {
                cargarAuditorias(1);
            });
        }

        // Botón para mostrar/ocultar filtros avanzados
        if ($btnFiltrosAvanzados.length) {
            $btnFiltrosAvanzados.on("click", function () {
                $filtrosAvanzados.slideToggle();
            });
        }

        // Cambios en filtros avanzados
        if ($fechaInicio.length) {
            $fechaInicio.on("change", function () {
                cargarAuditorias(1);
            });
        }

        if ($fechaFin.length) {
            $fechaFin.on("change", function () {
                cargarAuditorias(1);
            });
        }
    });
})();
