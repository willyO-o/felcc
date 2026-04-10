<div class="modal-header">
    <h5 class="modal-title">Detalles del IMEI</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-500">IMEI:</label>
            <p id="detalleImei"> {{ $datos->imei }} </p>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label fw-500">Fecha de Registro:</label>
            <p id="detalleFechaRegistro"> {{ $datos->created_at->format('d/m/Y H:i') }} </p>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-500">Características:</label>
        <p id="detalleCaracteristicas"> {{ $datos->caracteristicas ?? '—' }} </p>
    </div>

    <div class="mb-3">
        <label class="form-label fw-500">Teléfono Vinculado:</label>
        <div id="detalleVinculacion">
            @forelse ($datos->telefonos as $telefono)
                <p class="text-primary mb-1">
                    {{ $telefono->numero_celular }}
                    @if ($telefono->persona)
                        - {{ $telefono->persona->nombres }} {{ $telefono->persona->apellidos }} (C.I.: {{ $telefono->persona->ci }})
                    @endif
                </p>
            @empty
                <p class="text-danger">No hay teléfonos vinculados.</p>
            @endforelse
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-500">Última Actualización:</label>
        <p id="detalleUltActualizacion"></p>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line align-middle me-1"></i> Cerrar
    </button>
    <button type="button" class="btn btn-warning" id="btnEditarImeiDetalle">
        <i class="ri-edit-2-line align-middle me-1"></i> Editar
    </button>
</div>
