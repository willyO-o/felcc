<div class="modal-header">
    <h5 class="modal-title">Nuevo Vehículo</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="vehiculoForm" action="{{ route('vehiculos.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="placa" class="form-label">Placa *</label>
            <input type="text" class="form-control" id="placa" name="placa" placeholder="Ej: LAB-123" required>
            <div id="error-placa" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="2" placeholder="Descripción del vehículo"></textarea>
            <div id="error-descripcion" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label for="responsable" class="form-label">Responsable</label>
            <input type="text" class="form-control" id="responsable" name="responsable" placeholder="Nombre del responsable">
            <div id="error-responsable" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label for="caso_relacionado" class="form-label">Caso Relacionado</label>
            <input type="text" class="form-control" id="caso_relacionado" name="caso_relacionado" placeholder="Caso relacionado">
            <div id="error-caso_relacionado" class="invalid-feedback"></div>
        </div>

        <hr>
        <h6 class="mb-3">Información Adicional</h6>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="bsisa" class="form-label">BSISA</label>
                    <input type="text" class="form-control" id="bsisa" name="bsisa" placeholder="BSISA">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="ci_bsisa" class="form-label">CI BSISA</label>
                    <input type="text" class="form-control" id="ci_bsisa" name="ci_bsisa" placeholder="CI del BSISA">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="ruat" class="form-label">RUAT</label>
                    <input type="text" class="form-control" id="ruat" name="ruat" placeholder="RUAT">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="anh" class="form-label">ANH</label>
                    <input type="text" class="form-control" id="anh" name="anh" placeholder="ANH">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="itb" class="form-label">ITB</label>
                    <input type="text" class="form-control" id="itb" name="itb" placeholder="ITB">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="soat" class="form-label">SOAT</label>
            <input type="text" class="form-control" id="soat" name="soat" placeholder="SOAT">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary" id="btnGuardarVehiculo">
            <i class="ri-save-3-line align-middle me-1"></i> Guardar
        </button>
    </div>
</form>
