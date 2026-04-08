<div class="modal-header">
    <h5 class="modal-title">Detalles del IMEI</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-500">IMEI:</label>
            <p id="detalleImei"></p>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label fw-500">Fecha de Registro:</label>
            <p id="detalleFechaRegistro"></p>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-500">Características:</label>
        <p id="detalleCaracteristicas"></p>
    </div>

    <div class="mb-3">
        <label class="form-label fw-500">Teléfono Vinculado:</label>
        <div id="detalleVinculacion">
            <p class="text-muted">Cargando información...</p>
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
