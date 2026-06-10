/**
 * Módulo Consulta de Vehículos (Padrón externo)
 * Solo lectura — búsqueda manual con paginación AJAX
 */
(function () {
    "use strict";

    let currentPage = 1;
    let pageSize    = 15;
    let totalPages  = 0;
    let hasSearched = false;

    const $listado         = $("#listadoVehiculos");
    const $loading         = $("#loadingVehiculos");
    const $sinResultados   = $("#sinResultados");
    const $estadoInicial   = $("#estadoInicial");
    const $paginacion      = $("#paginacionVehiculos");
    const $detallesPagina  = $("#detalles-pagina");
    const $searchInput     = $("#searchVehiculos");
    const $searchType      = $("#searchType");
    const $btnBuscar       = $("#btnBuscarVehiculos");

    // Referencias búsqueda avanzada
    const $accordionCollapse = $("#collapseBusquedaVehiculos");
    const $badgeFiltros      = $("#badgeFiltrosVehiculos");
    const advFieldIds = [
        "adv_placa", "adv_propietario", "adv_docidentidad",
        "adv_nochasis", "adv_nomotor", "adv_marca", "adv_modelo",
        "adv_clase", "adv_color", "adv_tipo", "adv_servicio", "adv_dom"
    ];

    // ─── Cargar vehículos ──────────────────────────────────────────────────────
    function cargarVehiculos(page = 1) {

        currentPage = page;
        $loading.show();
        $sinResultados.hide();
        $estadoInicial.hide();
        $listado.empty();
        $paginacion.empty();

        const params = {
            page: currentPage,
            size: pageSize,
            search: $searchInput.val().trim(),
        };

        if ($searchType.val()) {
            params.search_type = $searchType.val();
        }

        // Parámetros de búsqueda avanzada por campo
        let activeAdvCount = 0;
        advFieldIds.forEach(function (fieldId) {
            const val = $("#" + fieldId).val();
            if (val && val.trim() !== "") {
                params[fieldId] = val.trim();
                activeAdvCount++;
            }
        });

        // Actualizar badge de filtros activos
        if (activeAdvCount > 0) {
            $badgeFiltros.text(activeAdvCount + (activeAdvCount === 1 ? " filtro" : " filtros")).show();
        } else {
            $badgeFiltros.hide();
        }

        $.ajax({
            url: "/consultas/vehiculos-padron",
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
                hasSearched = true;

                if (data.datos && data.datos.length > 0) {
                    renderVehiculos(data.datos);
                    renderPaginacion(data.total, data.page, pageSize);
                    $listado.show();
                    $sinResultados.hide();

                    const inicio = (data.page - 1) * pageSize + 1;
                    const fin = Math.min(data.page * pageSize, data.total);
                    $detallesPagina.text(`Mostrando ${inicio}–${fin} de ${data.total} registros`);
                } else {
                    $listado.empty();
                    $sinResultados.show();
                    $detallesPagina.text("Sin resultados");
                }
                // Colapsar acordeón tras búsqueda exitosa
                if ($accordionCollapse.hasClass("show")) {
                    const bsCollapse = bootstrap.Collapse.getInstance($accordionCollapse[0])
                        || new bootstrap.Collapse($accordionCollapse[0], { toggle: false });
                    bsCollapse.hide();
                }            })
            .fail(function (err) {
                $loading.hide();
                $sinResultados.show();
                console.error("Error al cargar vehículos:", err);
                mostrarError("Error al cargar los vehículos. Intenta nuevamente.");
            });
    }

    // ─── Renderizar filas ──────────────────────────────────────────────────────
    function renderVehiculos(vehiculos) {
        let html = "";

        vehiculos.forEach((v, index) => {
            html += `
                <tr>
                    <td>${(currentPage - 1) * pageSize + index + 1}</td>
                    <td><strong>${escapeHtml(v.placa || "N/A")}</strong></td>
                    <td>${escapeHtml(v.placaantigua || "—")}</td>
                    <td>${escapeHtml(v.propietario || "N/A")}</td>
                    <td>${escapeHtml(v.docidentidad || "N/A")}</td>
                    <td>${escapeHtml(v.marca || "N/A")}</td>
                    <td>${escapeHtml(v.modelo || "N/A")}</td>
                    <td>${escapeHtml(v.clase || "N/A")}</td>
                    <td>${escapeHtml(v.color || "N/A")}</td>
                    <td>${escapeHtml(v.tipo || "N/A")}</td>
                    <td>${escapeHtml(v.servicio || "N/A")}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info" title="Ver detalles"
                            onclick="mostrarDetallesVehiculo('${v.id}')">
                            <i class="ri-eye-line"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        $listado.html(html);
    }

    // ─── Modal de detalles ─────────────────────────────────────────────────────
    window.mostrarDetallesVehiculo = function (id) {
        $.ajax({
            url: `/consultas/vehiculos-padron/${id}`,
            type: "GET",
        })
            .done(function (html) {
                $("#modalDetallesVehiculoContent").html(html);
                const modal = new bootstrap.Modal(
                    document.getElementById("modalDetallesVehiculo")
                );
                modal.show();
            })
            .fail(function () {
                mostrarError("Error al cargar los detalles del vehículo.");
            });
    };

    // ─── Paginación ────────────────────────────────────────────────────────────
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
            if (start > 2) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
        }

        for (let i = start; i <= end; i++) {
            html += `<li class="page-item ${i === page ? "active" : ""}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        if (end < totalPages) {
            if (end < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }

        html += `<li class="page-item ${page >= totalPages ? "disabled" : ""}">
            <a class="page-link" href="#" data-page="${page + 1}">&raquo;</a>
        </li>`;

        $paginacion.html(html);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────
    function escapeHtml(text) {
        if (!text) return "";
        return $("<div>").text(text).html();
    }

    function mostrarError(mensaje) {
        Swal.fire({ title: "Error", text: mensaje, icon: "error", confirmButtonText: "Ok" });
    }

    // ─── Event listeners ───────────────────────────────────────────────────────
    $(document).ready(function () {

        cargarVehiculos(1);


        $btnBuscar.on("click", function () {
            cargarVehiculos(1);
        });

        $searchInput.on("keypress", function (e) {
            if (e.which === 13) {
                e.preventDefault();
                cargarVehiculos(1);
            }
        });

        // Búsqueda avanzada — botón buscar
        $("#btnBuscarAvanzado").on("click", function () {
            cargarVehiculos(1);
        });

        // Búsqueda avanzada — Enter en cualquier campo
        $("#formBusquedaVehiculos").on("keypress", function (e) {
            if (e.which === 13) {
                e.preventDefault();
                cargarVehiculos(1);
            }
        });

        // Limpiar búsqueda avanzada
        $("#btnLimpiarAvanzado").on("click", function () {
            $("#formBusquedaVehiculos")[0].reset();
            $badgeFiltros.hide();
            $listado.empty();
            $paginacion.empty();
            $sinResultados.hide();
            $estadoInicial.show();
            $detallesPagina.text("");
        });

        // Limpiar resultados cuando se borra el input
        $searchInput.on("search", function () {
            if (!$(this).val()) {
                $listado.empty();
                $paginacion.empty();
                $sinResultados.hide();
                $estadoInicial.show();
                $detallesPagina.text("");
            }
        });

        $(document).on("click", "#paginacionVehiculos .page-link[data-page]", function (e) {
            e.preventDefault();
            const p = parseInt($(this).data("page"));
            if (p >= 1 && p <= totalPages) {
                cargarVehiculos(p);
            }
        });
    });
})();
