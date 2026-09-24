/**
 * Módulo de Auditoría de Cambios
 * Listado AJAX con paginación, búsqueda y filtros
 */
(function () {
    "use strict";

    let currentPage = 1;
    let pageSize = 15;
    let searchTimeout = null;
    let totalPages = 0;

    const $listado       = $("#listadoAuditorias");
    const $loading       = $("#loadingAuditorias");
    const $sinResultados = $("#sinResultados");
    const $paginacion    = $("#paginacionAuditorias");
    const $detalles      = $("#detalles-pagina");
    const $searchInput   = $("#searchAuditoria");
    const $filtroModelo  = $("#filtroModelo");
    const $filtroEvento  = $("#filtroEvento");
    const $fechaInicio   = $("#fechaInicio");
    const $fechaFin      = $("#fechaFin");

    /**
     * Colores por tipo de evento
     */
    const eventoBadge = {
        created:  "success",
        updated:  "warning",
        deleted:  "danger",
        restored: "info",
    };

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

        const search = $searchInput.val().trim();
        if (search) params.search = search;

        const modelo = $filtroModelo.val();
        if (modelo) params.modelo = modelo;

        const evento = $filtroEvento.val();
        if (evento) params.evento = evento;

        const fechaInicio = $fechaInicio.val();
        const fechaFin = $fechaFin.val();
        if (fechaInicio && fechaFin) {
            params.fecha_inicio = fechaInicio;
            params.fecha_fin = fechaFin;
        }

        $.ajax({
            url: "/auditorias",
            type: "GET",
            dataType: "json",
            data: params,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            },
        })
            .done(function (data) {
                $loading.hide();

                if (!data.datos || data.datos.length === 0) {
                    $sinResultados.show();
                    $paginacion.empty();
                    $detalles.text("");
                    return;
                }

                renderAuditorias(data.datos);
                renderPaginacion(data.total, currentPage, pageSize);

                const desde = (currentPage - 1) * pageSize + 1;
                const hasta = Math.min(currentPage * pageSize, data.total);
                $detalles.text(`Mostrando ${desde} a ${hasta} de ${data.total} registros`);
            })
            .fail(function (err) {
                $loading.hide();
                console.error("Error cargando auditorías:", err);
                processError(err);
            });
    }

    /**
     * Renderizar filas de la tabla
     */
    function renderAuditorias(auditorias) {
        let html = "";

        auditorias.forEach((audit, index) => {
            const badge = eventoBadge[audit.evento] || "secondary";
            const numero = (currentPage - 1) * pageSize + index + 1;

            const cambiosHtml = audit.evento === "created"
                ? `<span class="badge badge-soft-success">${audit.campos_nuevos} campo(s)</span>`
                : audit.evento === "deleted"
                ? `<span class="badge badge-soft-danger">${audit.campos_antiguos} campo(s)</span>`
                : `<small class="text-muted">
                        <span class="badge badge-soft-danger me-1">${audit.campos_antiguos} antes</span>
                        <span class="badge badge-soft-success">${audit.campos_nuevos} después</span>
                   </small>`;

            html += /*html*/`
                <tr>
                    <td>${numero}</td>
                    <td>
                        <span class="badge badge-soft-${badge} fs-11">
                            ${escapeHtml(audit.evento_label)}
                        </span>
                    </td>
                    <td>
                        <span class="fw-semibold">${escapeHtml(audit.modelo_label)}</span>
                    </td>
                    <td>
                        <code>#${escapeHtml(String(audit.registro_id))}</code>
                    </td>
                    <td>
                        <div class="fw-semibold">${escapeHtml(audit.usuario)}</div>
                        <small class="text-muted">${escapeHtml(audit.usuario_email)}</small>
                    </td>
                    <td>
                        <small><code>${escapeHtml(audit.ip)}</code></small>
                    </td>
                    <td>${cambiosHtml}</td>
                    <td>
                        <small>${escapeHtml(audit.created_at)}</small>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-soft-secondary btn-ver-auditoria"
                            value="${audit.id}" title="Ver detalles">
                            <i class="ri-eye-fill align-bottom"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        $listado.html(html);
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
        let end   = Math.min(totalPages, page + 2);

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
     * Escaper HTML para evitar XSS
     */
    function escapeHtml(text) {
        if (text === null || text === undefined) return "—";
        return $("<div>").text(String(text)).html();
    }

    /**
     * Construir HTML de comparación de valores
     */
    function buildValoresHtml(oldValues, newValues, evento) {
        if (evento === "created") {
            if (!newValues || Object.keys(newValues).length === 0) {
                return '<p class="text-muted">Sin datos registrados.</p>';
            }
            let html = '<table class="table table-sm table-bordered mb-0">';
            html += '<thead class="table-light"><tr><th>Campo</th><th>Valor</th></tr></thead><tbody>';
            for (const [campo, valor] of Object.entries(newValues)) {
                html += `<tr>
                    <td class="fw-semibold text-nowrap">${escapeHtml(campo)}</td>
                    <td class="text-success">${escapeHtml(valor !== null ? String(valor) : 'null')}</td>
                </tr>`;
            }
            html += "</tbody></table>";
            return html;
        }

        if (evento === "deleted") {
            if (!oldValues || Object.keys(oldValues).length === 0) {
                return '<p class="text-muted">Sin datos registrados.</p>';
            }
            let html = '<table class="table table-sm table-bordered mb-0">';
            html += '<thead class="table-light"><tr><th>Campo</th><th>Valor eliminado</th></tr></thead><tbody>';
            for (const [campo, valor] of Object.entries(oldValues)) {
                html += `<tr>
                    <td class="fw-semibold text-nowrap">${escapeHtml(campo)}</td>
                    <td class="text-danger">${escapeHtml(valor !== null ? String(valor) : 'null')}</td>
                </tr>`;
            }
            html += "</tbody></table>";
            return html;
        }

        // updated / restored
        const allKeys = new Set([
            ...Object.keys(oldValues || {}),
            ...Object.keys(newValues || {}),
        ]);

        if (allKeys.size === 0) {
            return '<p class="text-muted">Sin cambios registrados.</p>';
        }

        let html = '<table class="table table-sm table-bordered mb-0">';
        html += '<thead class="table-light"><tr><th>Campo</th><th>Valor Anterior</th><th>Valor Nuevo</th></tr></thead><tbody>';

        for (const campo of allKeys) {
            const anterior = oldValues?.[campo] !== undefined ? String(oldValues[campo] ?? 'null') : '—';
            const nuevo    = newValues?.[campo] !== undefined ? String(newValues[campo] ?? 'null') : '—';
            const changed  = anterior !== nuevo;

            html += `<tr ${changed ? 'class="table-warning"' : ''}>
                <td class="fw-semibold text-nowrap">${escapeHtml(campo)}</td>
                <td class="text-danger">${escapeHtml(anterior)}</td>
                <td class="text-success">${escapeHtml(nuevo)}</td>
            </tr>`;
        }

        html += "</tbody></table>";
        return html;
    }

    /**
     * Ver detalles de una auditoría
     */
    function verDetalles(id) {
        const $content = $("#modalDetallesAuditoriaContent");
        $content.html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div></div>');

        const modal = new bootstrap.Modal(document.getElementById("modalDetallesAuditoria"));
        modal.show();

        $.ajax({
            url: `/auditorias/${id}`,
            type: "GET",
            dataType: "json",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            },
        })
            .done(function (data) {
                const badge = eventoBadge[data.evento] || "secondary";
                const valoresHtml = buildValoresHtml(data.old_values, data.new_values, data.evento);

                const html = /*html*/`
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border mb-0">
                                <div class="card-header p-2 bg-light">
                                    <h6 class="mb-0 fw-semibold"><i class="ri-information-line me-1"></i>Información General</h6>
                                </div>
                                <div class="card-body p-3">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-muted fw-normal w-40">ID Auditoría</th>
                                                <td><code>#${escapeHtml(String(data.id))}</code></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Evento</th>
                                                <td>
                                                    <span class="badge badge-soft-${badge} fs-12">
                                                        ${escapeHtml(data.evento_label)}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Módulo</th>
                                                <td class="fw-semibold">${escapeHtml(data.modelo_label)}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">ID Registro</th>
                                                <td><code>#${escapeHtml(String(data.registro_id))}</code></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Fecha / Hora</th>
                                                <td>${escapeHtml(data.created_at)}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border mb-0">
                                <div class="card-header p-2 bg-light">
                                    <h6 class="mb-0 fw-semibold"><i class="ri-user-line me-1"></i>Usuario & Sesión</h6>
                                </div>
                                <div class="card-body p-3">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-muted fw-normal w-40">Usuario</th>
                                                <td class="fw-semibold">${escapeHtml(data.usuario)}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Email</th>
                                                <td>${escapeHtml(data.usuario_email)}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">IP</th>
                                                <td><code>${escapeHtml(data.ip)}</code></td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">URL</th>
                                                <td>
                                                    <small class="text-break">${escapeHtml(data.url)}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">User Agent</th>
                                                <td>
                                                    <small class="text-muted text-break">${escapeHtml(data.user_agent)}</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border mb-0">
                                <div class="card-header p-2 bg-light">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="ri-file-list-3-line me-1"></i>
                                        Detalle de Cambios
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        ${valoresHtml}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $content.html(html);
            })
            .fail(function (err) {
                $content.html('<div class="alert alert-danger">Error al cargar los detalles.</div>');
                console.error("Error:", err);
            });
    }

    /**
     * Cargar opciones de filtro de modelos
     */
    function cargarFiltroModelos() {
        $.ajax({
            url: "/auditorias-filtros/modelos",
            type: "GET",
            dataType: "json",
            headers: { "X-Requested-With": "XMLHttpRequest" },
        }).done(function (data) {
            data.modelos.forEach(function (m) {
                $filtroModelo.append(`<option value="${escapeHtml(m.value)}">${escapeHtml(m.label)}</option>`);
            });
        });
    }

    /**
     * Cargar opciones de filtro de eventos
     */
    function cargarFiltroEventos() {
        $.ajax({
            url: "/auditorias-filtros/eventos",
            type: "GET",
            dataType: "json",
            headers: { "X-Requested-With": "XMLHttpRequest" },
        }).done(function (data) {
            data.eventos.forEach(function (e) {
                $filtroEvento.append(`<option value="${escapeHtml(e.value)}">${escapeHtml(e.label)}</option>`);
            });
        });
    }

    // ─── Eventos ───────────────────────────────────────────────────────────────

    /**
     * Click en botón ver detalles
     */
    $(document).on("click", ".btn-ver-auditoria", function () {
        verDetalles($(this).val());
    });

    /**
     * Paginación
     */
    $(document).on("click", "#paginacionAuditorias .page-link[data-page]", function (e) {
        e.preventDefault();
        const p = parseInt($(this).data("page"));
        if (p >= 1 && p <= totalPages) {
            cargarAuditorias(p);
        }
    });

    /**
     * Búsqueda con debounce
     */
    $searchInput.on("input", function () {
        clearTimeout(searchTimeout);
        const val = $(this).val().trim();
        if (val.length >= 3 || val.length === 0) {
            searchTimeout = setTimeout(() => cargarAuditorias(1), 400);
        }
    });

    /**
     * Filtros select
     */
    $filtroModelo.on("change", function () { cargarAuditorias(1); });
    $filtroEvento.on("change", function () { cargarAuditorias(1); });

    /**
     * Filtros de fecha
     */
    $fechaInicio.add($fechaFin).on("change", function () {
        if ($fechaInicio.val() && $fechaFin.val()) {
            cargarAuditorias(1);
        }
    });

    /**
     * Toggle filtros avanzados
     */
    $("#btnFiltrosAvanzados").on("click", function () {
        $("#filtrosAvanzados").slideToggle(200);
    });

    /**
     * Limpiar filtros
     */
    $("#btnLimpiarFiltros").on("click", function () {
        $searchInput.val("");
        $filtroModelo.val("");
        $filtroEvento.val("");
        $fechaInicio.val("");
        $fechaFin.val("");
        $("#filtrosAvanzados").hide();
        cargarAuditorias(1);
    });

    // ─── Inicialización ────────────────────────────────────────────────────────
    $(document).ready(function () {
        cargarFiltroModelos();
        cargarFiltroEventos();
        cargarAuditorias();
    });
})();
