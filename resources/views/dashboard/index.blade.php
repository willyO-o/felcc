@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Mandamientos')

@section('css')
<link href="/assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('page-title', 'Dashboard')

@section('breadcrumb')
<div class="page-title-right">
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="javascript: void(0);">FELCC</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <!-- Saludo -->
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">Bienvenido, {{ Auth::user()->name }}!</h4>
                            <p class="text-muted mb-0">Sistema de Gestión de Mandamientos de Aprehensión</p>
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-soft-success shadow-none" onclick="window.location.href='{{ route('mandamientos.create') }}'">
                                            <i class="ri-add-circle-line align-middle me-1"></i> Nuevo Mandamiento
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row">
                <!-- Total Mandamientos -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Mandamientos</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i> Activos
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>
                                    </h4>
                                    <a href="{{ route('mandamientos.index') }}" class="text-decoration-underline">Ver todos</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary rounded fs-3">
                                        <i class="bx bx-file"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mandamientos Pendientes -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pendientes</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-warning fs-14 mb-0">
                                        <i class="ri-time-line fs-13 align-middle"></i> En Proceso
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>
                                    </h4>
                                    <a href="{{ route('mandamientos.index') }}" class="text-decoration-underline">Ver pendientes</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning rounded fs-3">
                                        <i class="bx bx-hourglass"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mandamientos Ejecutados -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Ejecutados</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-checkbox-circle-line fs-13 align-middle"></i> Completados
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>
                                    </h4>
                                    <a href="{{ route('mandamientos.index') }}" class="text-decoration-underline">Ver ejecutados</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success rounded fs-3">
                                        <i class="bx bx-check-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personas Registradas -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Personas Registradas</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-info fs-14 mb-0">
                                        <i class="ri-user-line fs-13 align-middle"></i> Total
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>
                                    </h4>
                                    <a href="javascript:void(0);" class="text-decoration-underline">Ver registro</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info rounded fs-3">
                                        <i class="bx bx-user"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Tarjetas de Estadísticas -->

            <!-- Tabla de Mandamientos Recientes -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Mandamientos Recientes</h4>
                            <div class="flex-shrink-0">
                                <a href="{{ route('mandamientos.index') }}" class="btn btn-soft-primary btn-sm">
                                    Ver todos <i class="ri-arrow-right-line align-middle"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                    <thead class="text-muted table-light">
                                        <tr>
                                            <th scope="col">Hoja de Ruta</th>
                                            <th scope="col">Persona</th>
                                            <th scope="col">Delito</th>
                                            <th scope="col">Juzgado</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="ri-file-list-line fs-1 d-block mb-2"></i>
                                                No hay mandamientos registrados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Tabla -->

        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="/assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="/assets/libs/jsvectormap/maps/world-merc.js"></script>

<script>
    // Counter animation
    document.addEventListener('DOMContentLoaded', function() {
        var counterElements = document.querySelectorAll('.counter-value');
        counterElements.forEach(function(element) {
            var target = parseInt(element.getAttribute('data-target'));
            var current = 0;
            var increment = target / 50;

            var timer = setInterval(function() {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 20);
        });
    });
</script>
@endsection
