@extends('layouts.app')

@section('page-title', 'Centro de Reportes')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Centro de Reportes</li>
        </ol>
    </div>
@endsection

@section('content')


        <!-- Tarjeta de Introducción -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-light border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2">
                                    <i class="mdi mdi-file-document-multiple text-primary me-2"></i>
                                    Exporta tus datos en diferentes formatos
                                </h5>
                                <p class="card-text text-muted mb-0">
                                    Selecciona un tipo de reporte para exportar los datos en formato CSV o PDF. Aplica filtros si lo necesitas.
                                </p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <i class="mdi mdi-folder-multiple-outline" style="font-size: 3rem; color: #0d47a1; opacity: 0.1;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Reportes -->
        <div class="row mt-4">
            @foreach($reportes as $reporte)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 transition-all" style="cursor: pointer; transition: all 0.3s ease;">
                        <!-- Header del Card con color -->
                        <div class="card-header bg-{{ $reporte['color'] }} border-0" style="padding: 2rem;">
                            <div class="text-center">
                                <i class="mdi {{ $reporte['icono'] }} text-white" style="font-size: 3rem;"></i>
                            </div>
                        </div>

                        <!-- Body del Card -->
                        <div class="card-body">
                            <h5 class="card-title text-center mb-2 font-weight-600">
                                {{ $reporte['titulo'] }}
                            </h5>
                            <p class="card-text text-center text-muted small mb-4">
                                {{ $reporte['descripcion'] }}
                            </p>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm d-none" data-bs-toggle="modal" data-bs-target="#modalFiltros"
                                   onclick="prepararFiltros('{{ $reporte['params']['tipo'] }}', event)">
                                    <i class="mdi mdi-filter me-1"></i>Con Filtros
                                </a>
                                <button class="btn btn-success btn-sm" onclick="exportarDirecto('{{ $reporte['params']['tipo'] }}', 'csv')">
                                    <i class="mdi mdi-download me-1"></i>Exportar CSV
                                </button>
                                <button class="btn btn-danger btn-sm d-none" onclick="exportarDirecto('{{ $reporte['params']['tipo'] }}', 'pdf')">
                                    <i class="mdi mdi-file-pdf me-1"></i>Exportar PDF
                                </button>
                            </div>
                        </div>

                        <!-- Footer del Card con Estadísticas -->
                        <div class="card-footer bg-light border-top">
                            <small class="text-muted">
                                <i class="mdi mdi-information-outline"></i>
                                @switch($reporte['params']['tipo'])
                                    @case('mandamientos')
                                        Total de mandamientos registrados
                                        @break
                                    @case('registro-criminal')
                                        Registros criminales activos
                                        @break
                                    @case('personas')
                                        Personas en el sistema
                                        @break
                                    @case('celulares')
                                        Teléfonos registrados
                                        @break
                                    @case('imeis')
                                        IMEIs catalogados
                                        @break
                                    @case('vehiculos')
                                        Vehículos registrados
                                        @break
                                @endswitch
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sección de Configuración de Exportación -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient border-0">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-cog me-2"></i>Configuración de Exportación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h6 class="mb-3">Formatos Disponibles</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="badge bg-primary">CSV</span>
                                        <small class="text-muted ms-2">Ideal para Excel y análisis de datos</small>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-danger">PDF</span>
                                        <small class="text-muted ms-2">Para reportes formales e impresión</small>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <h6 class="mb-3">Opciones Adicionales</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="mdi mdi-check-circle text-success me-2"></i>
                                        <small>Aplicar filtros personalizados</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="mdi mdi-check-circle text-success me-2"></i>
                                        <small>Seleccionar campos a exportar</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="mdi mdi-check-circle text-success me-2"></i>
                                        <small>Historial de exportaciones</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal para Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary border-0">
                <h5 class="modal-title text-white">
                    <i class="mdi mdi-filter me-2"></i>Filtros - <span id="tipoReporte"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formFiltros" method="GET" action="{{ route('reportes.exportar') }}">
                <div class="modal-body">
                    <input type="hidden" id="tipoFiltro" name="tipo">

                    <!-- Sección de Rango de Fechas -->
                    <div class="mb-3">
                        <label class="form-label">Rango de Fechas</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="date" class="form-control" name="fecha_desde" placeholder="Desde">
                            </div>
                            <div class="col-lg-6">
                                <input type="date" class="form-control" name="fecha_hasta" placeholder="Hasta">
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Estado -->
                    <div class="mb-3" id="seccionEstado" style="display: none;">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado">
                            <option value="">-- Seleccionar --</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="ejecutado">Ejecutado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>

                    <!-- Sección de Búsqueda General -->
                    <div class="mb-3">
                        <label class="form-label">Búsqueda</label>
                        <input type="text" class="form-control" name="buscar" placeholder="Buscar por nombre, CI, placa, etc.">
                    </div>

                    <!-- Opciones de Campos -->
                    <div class="mb-3">
                        <label class="form-label">Campos a Incluir</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="campos[]" value="basico" id="campoBasico" checked>
                            <label class="form-check-label" for="campoBasico">
                                Campos Básicos
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="campos[]" value="detallado" id="campoDetallado">
                            <label class="form-check-label" for="campoDetallado">
                                Campos Detallados
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="campos[]" value="auditoria" id="campoAuditoria">
                            <label class="form-check-label" for="campoAuditoria">
                                Información de Auditoría
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="formato" value="csv">
                        <i class="mdi mdi-download me-1"></i>Exportar CSV
                    </button>
                    <button type="submit" class="btn btn-danger" name="formato" value="pdf">
                        <i class="mdi mdi-file-pdf me-1"></i>Exportar PDF
                    </button>
                </div>
            </form>
        </div>


<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .card {
        border-radius: 0.75rem;
    }

    .card:hover {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .mdi {
        display: inline-block;
        font-style: normal;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>

<script>
    function prepararFiltros(tipo, event) {
        event.preventDefault();
        document.getElementById('tipoFiltro').value = tipo;
        document.getElementById('tipoReporte').textContent = tipo.replace('-', ' ').toUpperCase();

        // Mostrar/ocultar secciones según el tipo
        const seccionEstado = document.getElementById('seccionEstado');
        if (tipo === 'mandamientos') {
            seccionEstado.style.display = 'block';
        } else {
            seccionEstado.style.display = 'none';
        }
    }

    function exportarDirecto(tipo, formato) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("reportes.exportar") }}';

        const tipoInput = document.createElement('input');
        tipoInput.type = 'hidden';
        tipoInput.name = 'tipo';
        tipoInput.value = tipo;

        const formatoInput = document.createElement('input');
        formatoInput.type = 'hidden';
        formatoInput.name = 'formato';
        formatoInput.value = formato;

        form.appendChild(tipoInput);
        form.appendChild(formatoInput);
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>

@endsection
