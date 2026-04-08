<div class="modal-header">
    <h5 class="modal-title">
        @if(isset($imei) && $imei->id)
            Editar IMEI
        @else
            Nuevo IMEI
        @endif
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="imeiForm" action="@if(isset($imei) && $imei->id){{ route('imeis.update', $imei->id) }}@else{{ route('imeis.store') }}@endif" method="POST">
    @csrf
    @if(isset($imei) && $imei->id)
        @method('PUT')
    @endif
    <div class="modal-body">
        <div class="mb-3">
            <label for="imei" class="form-label">IMEI *</label>
            <input type="text" class="form-control" id="imei" name="imei" placeholder="Ingresa el IMEI"
                @if(isset($imei) && $imei->id)value="{{ $imei->imei }}"@endif required>
            <div id="error-imei" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label for="caracteristicas" class="form-label">Características</label>
            <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3" placeholder="Características del IMEI">@if(isset($imei) && $imei->id){{ $imei->caracteristicas }}@endif</textarea>
            <div id="error-caracteristicas" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label for="telefono_id" class="form-label">Vincular Teléfono</label>
            <select id="telefono_id" name="telefono_id" class="form-select select2">
                <option value="">-- Sin vincular --</option>
                @if(isset($imei) && $imei->id && $imei->telefono)
                    <option value="{{ $imei->telefono->id }}" selected>
                        {{ $imei->telefono->numero_celular }}
                        @if($imei->telefono->persona)
                            - {{ $imei->telefono->persona->nombres }} {{ $imei->telefono->persona->apellidos }}
                        @endif
                    </option>
                @endif
            </select>
            <small class="text-muted d-block mt-2">Un IMEI puede estar vinculado a un teléfono (búsqueda mínimo 3 caracteres)</small>
            <div id="error-telefono_id" class="invalid-feedback"></div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary" id="btnGuardarImei">
            <i class="ri-save-3-line align-middle me-1"></i> Guardar
        </button>
    </div>
</form>
