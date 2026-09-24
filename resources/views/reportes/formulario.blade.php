@extends('layouts.app')

@section('title', 'Configurar Reporte - ' . ucfirst(str_replace('-', ' ', $tipo)))

@section('content')

<div class="page-content">
    <div class="container-fluid">

        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">
                        <i class="mdi mdi-filter me-2"></i>
                        Configurar Reporte: {{ ucfirst(str_replace('-', ' ', $tipo)) }}
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
                            <li class="breadcrumb-item active">Configurar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <form id="formReporte" method="GET" action="{{ route('reportes.exportar') }}">
            <input type="hidden" name="tipo" value="{{ $tipo }}">

            <div class="row">
                <!-- Columna Principal -->
                <div class="col-lg-8">

                    <!-- Card: Filtros de Fecha -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary border-0">
                            <h5 class="card-title mb-0 text-white">
                                <i class="mdi mdi-calendar me-2"></i>Filtros de Fecha
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-500">Desde</label>
                                        <input type="date" class="form-control" name="fecha_desde">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-500">Hasta</label>
                                        <input type="date" class="form-control" name="fecha_hasta">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Filtros Específicos por Tipo -->
                    @if($tipo === 'mandamientos')
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-info border-0">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="mdi mdi-briefcase me-2"></i>Filtros de Mandamientos
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-500">Estado</label>
                                    <select class="form-select" name="estado">
                                        <option value="">Todos los estados</option>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="ejecutado">Ejecutado</option>
                                        <option value="cancelado">Cancelado</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-500">Tipo de Mandamiento</label>
                                    <select class="form-select" name="tipo_mandamiento">
                                        <option value="">Todos los tipos</option>
                                        <option value="allanamiento">Allanamiento</option>
                                        <option value="captura">Captura</option>
                                        <option value="incautacion">Incautación</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-500">Juzgado</label>
                                    <input type="text" class="form-control" name="juzgado" placeholder="Buscar juzgado...">
                                </div>
                            </div>
                        </div>
                    @elseif($tipo === 'registro-criminal')
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-danger border-0">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="mdi mdi-alert-box me-2"></i>Filtros de Registro Criminal
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-500">Especialidad</label>
                                    <input type="text" class="form-control" name="especialidad" placeholder="Ej: Robo, Narcotráfico...">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-500">División Responsable</label>
                                    <select class="form-select" name="division">
                                        <option value="">Todas las divisiones</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif($tipo === 'personas')
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-info border-0">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="mdi mdi-account-multiple me-2"></i>Filtros de Personas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-500">Género</label>
                                    <select class="form-select" name="genero">
                                        <option value="">Todos</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-500">Estado Civil</label>
                                    <select class="form-select" name="estado_civil">
                                        <option value="">Todos</option>
                                        <option value="soltero">Soltero</option>
                                        <option value="casado">Casado</option>
                                        <option value="divorciado">Divorciado</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-500">País</label>
                                    <select class="form-select" name="pais">
                                        <option value="">Todos</option>
                                        <option value="Bolivia">Bolivia</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif($tipo === 'celulares')
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-success border-0">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="mdi mdi-phone me-2"></i>Filtros de Celulares
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-500">Empresa Operadora</label>
                                    <select class="form-select" name="empresa">
                                        <option value="">Todas</option>
                                        <option value="VIVA">VIVA</option>
                                        <option value="ENTEL">ENTEL</option>
                                        <option value="TIGO">TIGO</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-500">Con Persona Asociada</label>
                                    <select class="form-select" name="con_persona">
                                        <option value="">Todos</option>
                                        <option value="1">Con Persona</option>
                                        <option value="0">Sin Persona</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif($tipo === 'vehiculos')
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-secondary border-0">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="mdi mdi-car me-2"></i>Filtros de Vehículos
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-500">Tipo de Vehículo</label>
                                    <select class="form-select" name="tipo_vehiculo">
                                        <option value="">Todos</option>
                                        <option value="auto">Auto</option>
                                        <option value="moto">Moto</option>
                                        <option value="camion">Camión</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-500">Con Persona Asociada</label>
                                    <select class="form-select" name="con_persona">
                                        <option value="">Todos</option>
                                        <option value="1">Con Persona</option>
                                        <option value="0">Sin Persona</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Card: Búsqueda General -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary border-0">
                            <h5 class="card-title mb-0 text-white">
                                <i class="mdi mdi-magnify me-2"></i>Búsqueda
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-500">Texto de Búsqueda</label>
                                <input type="text" class="form-control" name="buscar"
                                       placeholder="Buscar por nombre, CI, placa, número, etc.">
                                <small class="text-muted">Dejá en blanco para buscar en todo</small>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar: Opciones y Resumen -->
                <div class="col-lg-4">

                    <!-- Card: Campos a Incluir -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning border-0">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-checkbox-multiple-marked me-2"></i>Campos a Incluir
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="campos[]"
                                       value="basico" id="campoBasico" checked>
                                <label class="form-check-label" for="campoBasico">
                                    <strong>Campos Básicos</strong>
                                    <br>
                                    <small class="text-muted">Información esencial</small>
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="campos[]"
                                       value="detallado" id="campoDetallado">
                                <label class="form-check-label" for="campoDetallado">
                                    <strong>Campos Detallados</strong>
                                    <br>
                                    <small class="text-muted">Información completa</small>
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="campos[]"
                                       value="auditoria" id="campoAuditoria">
                                <label class="form-check-label" for="campoAuditoria">
                                    <strong>Auditoría</strong>
                                    <br>
                                    <small class="text-muted">Quién y cuándo creó/modificó</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Resumen de Datos -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-information-outline me-2"></i>Resumen
                            </h5>
                        </div>
                        <div class="card-body">
                            @switch($tipo)
                                @case('mandamientos')
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Total de Mandamientos:</span>
                                            <strong class="fs-5">{{ $datos['mandamientos'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                    @break
                                @case('registro-criminal')
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Registros Criminales:</span>
                                            <strong class="fs-5">{{ $datos['registro_criminal'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                    @break
                                @case('personas')
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Total de Personas:</span>
                                            <strong class="fs-5">{{ $datos['personas'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                    @break
                                @case('celulares')
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Celulares Registrados:</span>
                                            <strong class="fs-5">{{ $datos['celulares'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                    @break
                                @case('imeis')
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>IMEIs Catalogados:</span>
                                            <strong class="fs-5">{{ $datos['imeis'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                    @break
                                @case('vehiculos')
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Vehículos Registrados:</span>
                                            <strong class="fs-5">{{ $datos['vehiculos'] ?? 0 }}</strong>
                                        </div>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <!-- Card: Formatos -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-download-multiple me-2"></i>Formato
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" name="formato" value="csv" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-file-delimited me-2"></i>
                                    Descargar CSV
                                </button>
                                <button type="submit" name="formato" value="pdf" class="btn btn-danger btn-lg">
                                    <i class="mdi mdi-file-pdf me-2"></i>
                                    Descargar PDF
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>
</div>

<style>
    .fw-500 {
        font-weight: 500;
    }

    .form-label {
        margin-bottom: 0.75rem;
        color: #374151;
    }

    .form-select,
    .form-control {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }

    .form-check {
        padding-left: 2rem;
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        margin-left: -2rem;
    }

    .card {
        border-radius: 0.75rem;
    }

    .card-header {
        border-radius: 0.75rem 0.75rem 0 0;
        padding: 1.25rem;
    }

    .bg-primary {
        background-color: #0d47a1 !important;
    }

    .bg-info {
        background-color: #0891b2 !important;
    }

    .bg-danger {
        background-color: #dc2626 !important;
    }

    .bg-success {
        background-color: #059669 !important;
    }

    .bg-warning {
        background-color: #d97706 !important;
    }

    .bg-secondary {
        background-color: #6b7280 !important;
    }

    .text-white {
        color: white !important;
    }
</style>

@endsection
