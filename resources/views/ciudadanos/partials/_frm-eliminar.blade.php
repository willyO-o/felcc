<div class="modal-header">
    <h5 class="modal-title">
        <i class="ri-delete-bin-line align-middle me-2"></i>
        Eliminar Ciudadano
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="formMigrarEliminar" action="{{ route('ciudadanos.destroy', $ciudadano->id) }}" method="POST">
    @csrf
    @method('DELETE')

    <div class="modal-body">
        <div class="alert alert-warning" role="alert">
            <i class="ri-alert-line align-middle me-2"></i>
            <strong>¡Advertencia!</strong> Esta acción podría no ser reversible dependiendo del tipo de eliminación que elijas.
        </div>

        <p class="mb-3">
            <strong>Ciudadano a eliminar:</strong><br>
            <span class="text-muted">{{ $ciudadano->nombre_completo }}</span>
        </p>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="eliminar_tipo" id="eliminarSuave" value="suave" checked>
                <label class="form-check-label" for="eliminarSuave">
                    <strong>Eliminación Suave (Recomendado)</strong>
                    <div class="text-muted small">El registro se marcará como eliminado pero se puede restaurar.</div>
                </label>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="eliminar_tipo" id="eliminarCompleto" value="completo">
                <label class="form-check-label" for="eliminarCompleto">
                    <strong>Eliminación Completa</strong>
                    <div class="text-muted small">El registro se eliminará permanentemente y no se podrá recuperar.</div>
                </label>
            </div>
            <input type="hidden" id="eliminar_completo" name="eliminar_completo" value="0">
        </div>

        <script>
            document.getElementById('eliminarCompleto').addEventListener('change', function() {
                document.getElementById('eliminar_completo').value = this.checked ? '1' : '0';
            });
            document.getElementById('eliminarSuave').addEventListener('change', function() {
                document.getElementById('eliminar_completo').value = this.checked ? '0' : '0';
            });
        </script>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-danger" id="btnConfirmarMigrarEliminar">
            <i class="ri-delete-bin-line align-middle me-1"></i> Eliminar
        </button>
    </div>
</form>
