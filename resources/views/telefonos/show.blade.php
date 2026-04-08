<div class="modal-header">
    <h5 class="modal-title">Detalles del Teléfono</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <h6 class="text-muted">Número Celular</h6>
                <p class="fw-bold">{{ $datos->numero_celular }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <h6 class="text-muted">Empresa</h6>
                <p class="fw-bold">{{ $datos->empresa ?? '—' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <h6 class="text-muted">Persona/Caso</h6>
                <p class="fw-bold">{{ $datos->persona_caso ?? '—' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <h6 class="text-muted">Caso</h6>
                <p class="fw-bold">{{ $datos->caso ?? '—' }}</p>
            </div>
        </div>

        @if ($datos->persona)
            <div class="col-md-6">
                <div class="mb-3">
                    <h6 class="text-muted">Relacionado con</h6>
                    <p class="fw-bold">
                        <a href="javascript:void(0);" onclick="window.open('{{ route('personas.show', $datos->persona->id) }}', '_blank')">
                            {{ $datos->persona->nombres }} {{ $datos->persona->apellidos }}
                        </a>
                    </p>
                </div>
            </div>
        @endif

        <div class="col-md-6">
            <div class="mb-3">
                <h6 class="text-muted">Respuesta Requerimiento</h6>
                <p class="fw-bold">{{ $datos->respuesta_requerimiento ?? '—' }}</p>
            </div>
        </div>

        @if ($datos->informacion)
            <div class="col-12">
                <div class="mb-3">
                    <h6 class="text-muted">Información General</h6>
                    <p>{{ $datos->informacion }}</p>
                </div>
            </div>
        @endif

        @if ($datos->imeis_asociados && count($datos->imeis_asociados) > 0)
            <div class="col-12">
                <div class="mb-3">
                    <h6 class="text-muted">IMEI Asociados</h6>
                    <div>
                        @foreach ($datos->imeis_asociados as $imei)
                            <span class="badge badge-outline-secondary me-2">{{ $imei }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="row">
                @if ($datos->callapp)
                    <div class="col-md-4">
                        <h6 class="text-muted">CallApp</h6>
                        <p class="fw-bold">{{ $datos->callapp }}</p>
                    </div>
                @endif

                @if ($datos->truecall)
                    <div class="col-md-4">
                        <h6 class="text-muted">TrueCall</h6>
                        <p class="fw-bold">{{ $datos->truecall }}</p>
                    </div>
                @endif

                @if ($datos->uninet)
                    <div class="col-md-4">
                        <h6 class="text-muted">UniNet</h6>
                        <p class="fw-bold">{{ $datos->uninet }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 mt-3 pt-3 border-top">
            <small class="text-muted">
                <i class="ri-calendar-line"></i>
                Registrado: {{ $datos->created_at?->format('d/m/Y H:i') }}
            </small>
            @if ($datos->updated_at)
                <br>
                <small class="text-muted">
                    <i class="ri-refresh-line"></i>
                    Último cambio: {{ $datos->updated_at?->format('d/m/Y H:i') }}
                </small>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer">

    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line align-middle me-1"></i> Cerrar
    </button>
</div>
