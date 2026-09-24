<div class="modal-header bg-danger-subtle">
    <h5 class="modal-title text-danger" id="modalMigrarEliminarLabel">
        <i class="ri-alert-line align-middle me-2"></i>Eliminar Persona
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="formMigrarEliminar" action="{{ route('personas.destroy', $persona->id) }}" method="POST">

    <input type="hidden" id="persona_id" value="{{ $persona->id }}">
    @csrf
    @method('DELETE')

    <div class="modal-body">
        <div class="alert alert-warning mb-3">
            <i class="ri-information-line align-middle me-2"></i>
            <strong>Atención:</strong> Está a punto de eliminar una persona del sistema.
        </div>

        <div class="mb-3">
            <label for="personaMigrar" class="form-label">
                <i class="ri-shuffle-line align-middle me-2"></i>Migrar Datos Relacionados (Opcional)
            </label>
            <small class="text-muted d-block mb-2">
                Si esta persona tiene datos relacionados (mandamientos, registros criminales, etc.),
                puede migrarlos a otro registro existente antes de eliminar.
            </small>
            <select id="personaMigrar" name="persona_migrar_id" class="form-select select2-migrar"
                data-placeholder="Selecciona una persona para migrar datos relacionados (opcional)">
                <option value="">— Sin migrar (eliminar solo registros huérfanos) —</option>
            </select>
            <div id="error-persona_migrar_id" class="invalid-feedback d-block mt-1"></div>
        </div>

        <div class="card bg-light mb-3">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="ri-file-list-line align-middle me-2"></i>Datos Relacionados
                </h6>
                <div id="resumenDatos">
                    @forelse ($resumen as $clave => $cantidad)
                        <div>
                            <strong>{{ ucfirst(str_replace('_', ' ', $clave)) }}:</strong>
                            {{ $cantidad }}
                        </div>
                    @empty
                        <span class="text-muted">No hay datos relacionados.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check">

                <div class="form-check form-switch form-switch-danger form-switch-lg" dir="ltr">
                    <input type="checkbox" class="form-check-input" id="chkEliminarCompleto"  name="eliminar_completo">
                    <label class="form-check-label" for="chkEliminarCompleto">
                        <i class="ri-delete-bin-line align-middle me-2"></i>
                        <strong>Eliminar completamente</strong> (no recuperable)
                    </label>
                </div>
                <small class="text-danger d-block mt-1">
                    Si marca esta opción, la persona se eliminará permanentemente de la base de datos. <br>
                    Si deja sin marcar, se marcará como eliminada pero será recuperable.
                </small>
            </div>
        </div>

        <div class="alert alert-info mb-0">
            <strong>Nota:</strong> Si no migra los datos relacionados, estos se eliminarán totalmente junto con la persona.
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-danger" id="btnConfirmarMigrarEliminar">
            <i class="ri-delete-bin-fill align-middle me-1"></i> Confirmar Eliminación
        </button>
    </div>
</form>
