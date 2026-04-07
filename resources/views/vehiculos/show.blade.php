<div class="modal-header">
    <h5 class="modal-title">Detalles del Vehículo</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row mb-3">
        <div class="col-md-6">
            <h6 class="text-muted">Placa</h6>
            <p class="fw-bold">{{ $vehiculo->placa }}</p>
        </div>
        <div class="col-md-6">
            <h6 class="text-muted">Responsable</h6>
            <p class="fw-bold">{{ $vehiculo->responsable ?? '—' }}</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <h6 class="text-muted">Caso Relacionado</h6>
            <p>{{ $vehiculo->caso_relacionado ?? '—' }}</p>
        </div>
        <div class="col-md-6">
            <h6 class="text-muted">Descripción</h6>
            <p>{{ $vehiculo->descripcion ?? '—' }}</p>
        </div>
    </div>

    <hr>
    <h6 class="mb-3 text-muted">Información Adicional</h6>

    <div class="row">
        <div class="col-md-6">
            <small class="text-muted">BSISA</small>
            <p>{{ $vehiculo->bsisa ?? '—' }}</p>
        </div>
        <div class="col-md-6">
            <small class="text-muted">CI BSISA</small>
            <p>{{ $vehiculo->ci_bsisa ?? '—' }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <small class="text-muted">RUAT</small>
            <p>{{ $vehiculo->ruat ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <small class="text-muted">ANH</small>
            <p>{{ $vehiculo->anh ?? '—' }}</p>
        </div>
        <div class="col-md-4">
            <small class="text-muted">ITB</small>
            <p>{{ $vehiculo->itb ?? '—' }}</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <small class="text-muted">SOAT</small>
            <p>{{ $vehiculo->soat ?? '—' }}</p>
        </div>
    </div>

    @if($vehiculo->casos->count() > 0)
        <hr>
        <h6 class="mb-3 text-muted">Personas Vinculadas</h6>
        <div class="list-group">
            @foreach($vehiculo->casos as $caso)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $caso->persona->nombres ?? '—' }} {{ $caso->persona->apellidos ?? '' }}</h6>
                            <small class="text-muted">CI: {{ $caso->persona->ci ?? '—' }}</small><br>
                            <small class="badge bg-secondary">{{ $caso->tipo }}</small>
                            @if($caso->caso)
                                <small class="badge bg-info">{{ $caso->caso }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-3 text-muted small">
        <p>Registrado: {{ $vehiculo->created_at->format('d/m/Y H:i') }}</p>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line align-middle me-1"></i> Cerrar
    </button>
</div>
