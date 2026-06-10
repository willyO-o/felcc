<div class="modal-header bg-light border-0">
    <div class="w-100">
        <h3 class="modal-title mb-2">
            <i class="ri-book-2-line align-middle me-2 text-primary"></i>
            LIBRO NRO. {{ $datos->num_libro ?? 'N/A' }}
        </h3>
        <div class="d-flex gap-3 flex-wrap">
            <small class="text-muted">
                <strong>Código Notario:</strong> <span class="text-dark">{{ $datos->cod_notario ?? 'N/A' }}</span>
            </small>
            <small class="text-muted">
                <strong>Localidad:</strong>
                <span class="text-dark">{{ $datos->localidad?->nom_loc ?? 'Sin localidad vinculada' }}</span>
            </small>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
    <div class="container-fluid">
        {{-- Datos del Libro --}}
        <div class="mb-4">
            <h6 class="text-uppercase fw-bold text-primary mb-3">
                <i class="ri-file-list-3-line me-2"></i> Datos del Libro
            </h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Número de Libro</small>
                        <span class="fw-semibold">{{ $datos->num_libro ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Código Notario</small>
                        <span class="fw-semibold">{{ $datos->cod_notario ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">ID Localidad Libro</small>
                        <span class="fw-semibold">{{ $datos->id_loc_libro ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Circunscripción</small>
                        <span class="fw-semibold">{{ $datos->circun ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Nombre Circunscripción</small>
                        <span class="fw-semibold">{{ $datos->nom_circun ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Distrito</small>
                        <span class="fw-semibold">{{ $datos->dist ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Nombre Distrito</small>
                        <span class="fw-semibold">{{ $datos->nom_dist ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Zona</small>
                        <span class="fw-semibold">{{ $datos->zona ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Nombre Zona</small>
                        <span class="fw-semibold">{{ $datos->nom_zona ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Recinto</small>
                        <span class="fw-semibold">{{ $datos->reci ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Nombre Recinto</small>
                        <span class="fw-semibold">{{ $datos->nom_reci ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        {{-- Localidad Relacionada --}}
        <div class="mb-4">
            <h6 class="text-uppercase fw-bold text-primary mb-3">
                <i class="ri-layout-grid-line me-2"></i> Localidad Relacionada
            </h6>

            @php
                $localidad = $datos->localidad;
            @endphp

            @if($localidad)
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">ID Localidad</small>
                            <span class="fw-semibold">{{ $localidad->id ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Departamento</small>
                            <span class="fw-semibold">{{ $localidad->dep_loc ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Provincia</small>
                            <span class="fw-semibold">{{ $localidad->prov_loc ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Sección</small>
                            <span class="fw-semibold">{{ $localidad->sec_loc ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Cantón</small>
                            <span class="fw-semibold">{{ $localidad->can ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Localidad</small>
                            <span class="fw-semibold">{{ $localidad->loc ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Nombre Departamento</small>
                            <span class="fw-semibold">{{ $localidad->nom_dep ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Nombre Provincia</small>
                            <span class="fw-semibold">{{ $localidad->nom_prov ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Nombre Sección</small>
                            <span class="fw-semibold">{{ $localidad->nom_sec ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Nombre Cantón</small>
                            <span class="fw-semibold">{{ $localidad->nom_can ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Nombre Localidad</small>
                            <span class="fw-semibold">{{ $localidad->nom_loc ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    Este libro no tiene una localidad vinculada.
                </div>
            @endif
        </div>

        <hr class="my-4">

        {{-- Notarios de la Localidad --}}
        <div class="mb-4">
            <h6 class="text-uppercase fw-bold text-primary mb-3">
                <i class="ri-user-star-line me-2"></i> Notarios de la Localidad
            </h6>

            @if($localidad)
                @php
                    $notarios = $localidad->notarios;
                @endphp

                @if($notarios->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Código Notario</th>
                                    <th>ID Localidad</th>
                                    <th>Nombre Notario</th>
                                    <th>Dirección</th>
                                    <th>Zona</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notarios as $notario)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $notario->cod_notario ?? 'N/A' }}</td>
                                        <td>{{ $notario->id_loc_not_e ?? 'N/A' }}</td>
                                        <td>{{ $notario->nom_not_e ?? 'N/A' }}</td>
                                        <td>{{ $notario->direccion ?? 'N/A' }}</td>
                                        <td>{{ $notario->zona ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        No existen notarios asociados a esta localidad.
                    </div>
                @endif
            @else
                <div class="alert alert-warning mb-0">
                    No se pueden mostrar notarios porque el libro no tiene localidad vinculada.
                </div>
            @endif
        </div>

        <hr class="my-4">

        {{-- Libros de la Localidad --}}
        <div class="mb-4">
            <h6 class="text-uppercase fw-bold text-primary mb-3">
                <i class="ri-book-open-line me-2"></i> Libros de la Localidad
            </h6>

            @if($localidad)
                @php
                    $libros = $localidad->libros;
                @endphp

                @if($libros->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Número Libro</th>
                                    <th>Código Notario</th>
                                    <th>ID Localidad</th>
                                    <th>Circun.</th>
                                    <th>Nombre Circun.</th>
                                    <th>Dist.</th>
                                    <th>Nombre Dist.</th>
                                    <th>Zona</th>
                                    <th>Nombre Zona</th>
                                    <th>Recinto</th>
                                    <th>Nombre Recinto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($libros as $libro)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $libro->num_libro ?? 'N/A' }}</td>
                                        <td>{{ $libro->cod_notario ?? 'N/A' }}</td>
                                        <td>{{ $libro->id_loc_libro ?? 'N/A' }}</td>
                                        <td>{{ $libro->circun ?? 'N/A' }}</td>
                                        <td>{{ $libro->nom_circun ?? 'N/A' }}</td>
                                        <td>{{ $libro->dist ?? 'N/A' }}</td>
                                        <td>{{ $libro->nom_dist ?? 'N/A' }}</td>
                                        <td>{{ $libro->zona ?? 'N/A' }}</td>
                                        <td>{{ $libro->nom_zona ?? 'N/A' }}</td>
                                        <td>{{ $libro->reci ?? 'N/A' }}</td>
                                        <td>{{ $libro->nom_reci ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        No existen libros asociados a esta localidad.
                    </div>
                @endif
            @else
                <div class="alert alert-warning mb-0">
                    No se pueden mostrar libros relacionados porque el libro no tiene localidad vinculada.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .detail-item {
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        border-left: 3px solid #0d6efd;
    }

    .detail-item small {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-item span {
        font-size: 0.95rem;
    }
</style>

<div class="modal-footer border-top">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line align-middle me-1"></i> Cerrar
    </button>
</div>
