<div class="modal-header">
    <h5 class="modal-title">
        @if(isset($imei) && $imei->id)
            Editar IMEI
        @else
            Nuevos IMEIs
        @endif
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

@if(isset($imei) && $imei->id)
    {{-- MODO EDICIÓN: Un solo IMEI --}}
    <form id="imeiForm" action="{{ route('imeis.update', $imei->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="mb-3">
                <label for="imei" class="form-label">IMEI *</label>
                <input type="text" class="form-control" id="imei" name="imei" placeholder="Ingresa el IMEI"
                    value="{{ $imei->imei }}" required>
                <div id="error-imei" class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="caracteristicas" class="form-label">Características</label>
                <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3" 
                    placeholder="Características del IMEI">{{ $imei->caracteristicas }}</textarea>
                <div id="error-caracteristicas" class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="telefono_id" class="form-label">Vincular Teléfono</label>
                <select id="telefono_id" name="telefono_id" class="form-select select2">
                    <option value="">-- Sin vincular --</option>
                    @if($imei->telefono)
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
@else
    {{-- MODO CREACIÓN: Múltiples IMEIs con transacción --}}
    <form id="imeiForm" action="{{ route('imeis.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label for="telefono_id_multiple" class="form-label">Vincular a Teléfono *</label>
                <select id="telefono_id_multiple" name="telefono_id" class="form-select select2" required>
                    <option value="">-- Seleccionar teléfono --</option>
                </select>
                <small class="text-muted d-block mt-2">Todos los IMEIs se vincularán a este teléfono (búsqueda mínimo 3 caracteres)</small>
                <div id="error-telefono_id" class="invalid-feedback"></div>
            </div>

            <hr class="my-3">

            <div class="mb-3">
                <label class="form-label">IMEIs a registrar *</label>
                
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="imei_input" placeholder="Ingresa un IMEI"
                        maxlength="50">
                    <textarea class="form-control" id="caracteristicas_input" placeholder="Características (opcional)"
                        rows="1" style="max-height: 40px;"></textarea>
                    <button class="btn btn-outline-primary" type="button" id="btnAgregarImei">
                        <i class="ri-add-line align-middle"></i> Agregar
                    </button>
                </div>

                <div id="listadoImeinuevo" class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 45%">IMEI</th>
                                <th style="width: 45%">Características</th>
                                <th style="width: 10%" class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-imeis">
                        </tbody>
                    </table>
                    <div id="sinImeis" class="text-center text-muted py-3">
                        <small>No hay IMEIs agregados aún</small>
                    </div>
                </div>

                <div id="error-imeis" class="invalid-feedback d-block"></div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="ri-close-line align-middle me-1"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary" id="btnGuardarImei">
                <i class="ri-save-3-line align-middle me-1"></i> Registrar IMEIs
            </button>
        </div>
    </form>
@endif
