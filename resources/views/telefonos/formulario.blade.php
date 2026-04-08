@php
    $esEdicion = isset($telefono) && $telefono->id;
    $accion = $esEdicion ? route('telefonos.update', $telefono->id) : route('telefonos.store');
    $metodo = 'POST';
@endphp

<div class="modal-header">
    <h5 class="modal-title">
        @if ($esEdicion)
            Editar Teléfono
        @else
            Nuevo Teléfono
        @endif
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="telefonoForm" action="{{ $accion }}" method="{{ $metodo }}">
    @csrf
    @if ($esEdicion)
        @method('PUT')
    @endif

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="numero_celular" class="form-label">Número Celular <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control txtNumero" id="numero_celular" name="numero_celular"
                        value="{{ $telefono->numero_celular ?? '' }}" placeholder="Ej: +591 71234567" required>
                    <div id="error-numero_celular" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="empresa" class="form-label">Empresa</label>
                    <input type="text" class="form-control txtMayuscula" id="empresa" name="empresa"
                        value="{{ $telefono->empresa ?? '' }}" placeholder="Ej: Viva, Entel, Tigo">
                    <div id="error-empresa" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="persona_caso" class="form-label">Persona del caso</label>
                    <input type="text" class="form-control txtMayuscula" id="persona_caso" name="persona_caso"
                        value="{{ $telefono->persona_caso ?? '' }}" placeholder="Nombre de la persona o caso">
                    <div id="error-persona_caso" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="caso" class="form-label">Caso</label>
                    <input type="text" class="form-control txtMayuscula" id="caso" name="caso"
                        value="{{ $telefono->caso ?? '' }}" placeholder="Referencia del caso">
                    <div id="error-caso" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="persona_id" class="form-label">Relacionar con Persona (opcional)</label>
                    <select class="form-select" id="persona_id" name="persona_id" style="width: 100%;">
                        <option value="">Seleccionar persona (opcional)</option>
                        @if ($telefono->persona_id && $telefono->persona)
                            <option value="{{ $telefono->persona->id }}" selected>
                                {{ $telefono->persona->nombres }} {{ $telefono->persona->apellidos }}
                                {{ $telefono->persona->ci ? '(' . $telefono->persona->ci . ')' : '' }}
                            </option>
                        @endif
                    </select>
                    <div id="error-persona_id" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="respuesta_requerimiento" class="form-label">Respuesta Requerimiento</label>
                    <input type="text" class="form-control" id="respuesta_requerimiento"
                        name="respuesta_requerimiento" value="{{ $telefono->respuesta_requerimiento ?? '' }}"
                        placeholder="Estado de la respuesta">
                    <div id="error-respuesta_requerimiento" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">IMEI Asociados</label>
                    @if ($telefono->id)
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @if ($telefono->imeis && $telefono->imeis->count() > 0)
                                @foreach ($telefono->imeis as $imei)
                                    <span class="badge badge-outline-secondary">{{ $imei->imei }}</span>
                                @endforeach
                            @else
                                <small class="text-muted">Sin IMEIs asociados</small>
                            @endif
                        </div>
                        <small class="text-muted d-block">
                            <i class="ri-info-line"></i> Los IMEIs se gestionan desde el módulo IMEI
                        </small>
                    @else
                        <small class="text-muted">Los IMEIs se agregan después de crear el teléfono</small>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="informacion" class="form-label">Información</label>
                    <input class="form-control" id="informacion" name="informacion" rows="2"
                        placeholder="Información adicional sobre el teléfono"
                        value="{{ $telefono->informacion ?? '' }}">
                    <div id="error-informacion" class="invalid-feedback"></div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="callapp" class="form-label">CallApp</label>
                        <input type="text" class="form-control" id="callapp" name="callapp"
                            value="{{ $telefono->callapp ?? '' }}" placeholder="Información CallApp">
                        <div id="error-callapp" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="truecall" class="form-label">TrueCall</label>
                        <input type="text" class="form-control" id="truecall" name="truecall"
                            value="{{ $telefono->truecall ?? '' }}" placeholder="Información TrueCall">
                        <div id="error-truecall" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="uninet" class="form-label">UniNet</label>
                        <input type="text" class="form-control" id="uninet" name="uninet"
                            value="{{ $telefono->uninet ?? '' }}" placeholder="Información UniNet">
                        <div id="error-uninet" class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-primary" id="btnGuardarTelefono">
            <i class="ri-save-3-line align-middle me-1"></i> Guardar
        </button>
    </div>
</form>
