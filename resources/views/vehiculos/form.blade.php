<div class="modal-header">
    <h5 class="modal-title">
        @if (isset($vehiculo) && $vehiculo->id)
            Editar Vehículo
        @else
            Nuevo Vehículo
        @endif
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form id="vehiculoForm"
    action="@if (isset($vehiculo) && $vehiculo->id) {{ route('vehiculos.update', $vehiculo->id) }}@else{{ route('vehiculos.store') }} @endif"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($vehiculo) && $vehiculo->id)
        @method('PUT')
    @endif
    <div class="modal-body">

        <div class="row">
            <div class="mb-3 col-md-6">
                <label for="placa" class="form-label">Placa *</label>
                <input type="text" class="form-control txtMayuscula sinEspacios" id="placa" name="placa" placeholder="Ej: LAB-123"
                    @if (isset($vehiculo) && $vehiculo->id) value="{{ $vehiculo->placa }}" @endif required>
                <div id="error-placa" class="invalid-feedback"></div>
            </div>
            <div class="mb-3 col-md-6">
                <label for="responsable" class="form-label">Responsable</label>
                <input type="text" class="form-control txtMayuscula" id="responsable" name="responsable"
                    placeholder="Nombre del responsable"
                    @if (isset($vehiculo) && $vehiculo->id) value="{{ $vehiculo->responsable }}" @endif>
                <div id="error-responsable" class="invalid-feedback"></div>
            </div>


            <div class="mb-3 col-md-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="2"
                    placeholder="Descripción del vehículo">{{ isset($vehiculo->descripcion) ? $vehiculo->descripcion : '' }}</textarea>
                <div id="error-descripcion" class="invalid-feedback"></div>
            </div>
        </div>

        <div class="mb-3">
            <label for="caso_relacionado" class="form-label">Caso Relacionado</label>
            <input type="text" class="form-control txtMayuscula" id="caso_relacionado" name="caso_relacionado"
                placeholder="Caso relacionado"
                @if (isset($vehiculo) && $vehiculo->id) value="{{ $vehiculo->caso_relacionado }}" @endif>
            <div id="error-caso_relacionado" class="invalid-feedback"></div>
        </div>

        <hr>
        <h6 class="mb-3">Personas Asociadas</h6>

        <div class="mb-3">
            <label for="personaBuscar" class="form-label">Buscar Persona</label>
            <select id="personaBuscar" class="form-select" style="width: 100%;"></select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tipoPersona" class="form-label">Tipo de Información *</label>

                    <input list="tiposPersona" class="form-control txtMayuscula" id="tipoPersona"  placeholder="Seleccionar tipo" >

                    <datalist id="tiposPersona">
                        <option value="BSISA">
                        <option value="RUAT">
                        <option value="SOAT">
                        <option value="ANH">
                        <option value="ITB">
                    </datalist>

                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="casoPersona" class="form-label">Caso (Opcional)</label>
                    <input type="text" class="form-control txtMayuscula" id="casoPersona" placeholder="Ingresa el caso si aplica">
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-secondary mb-3" id="btnAgregarPersona">
            <i class="ri-add-line align-middle me-1"></i> Agregar Persona
        </button>

        <div id="listaPersonasVehiculo">
            @if (isset($vehiculo) && $vehiculo->id && $vehiculo->personas->count() > 0)
                @foreach ($vehiculo->personas as $persona)
                    <div class="card mb-2" data-persona-id="{{ $persona->id }}"
                        data-tipo="{{ $persona->pivot->tipo }}"
                        data-caso="{{ $persona->pivot->caso ?? '' }}">
                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="fw-bold d-block">{{ $persona->nombres }}
                                    {{ $persona->apellidos }}</small>
                                <small class="badge badge-outline-secondary">{{ $persona->pivot->tipo }}</small>
                                @if ($persona->pivot->caso)
                                Caso:
                                    <small class="badge badge-outline-info">{{ $persona->pivot->caso }}</small>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-soft-danger btn-remove-persona"
                                title="Eliminar">
                                <i class="ri-close-line align-bottom"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <input type="hidden" id="personas_asociadas" name="personas_asociadas" value="[]">

        <hr>
        <h6 class="mb-3">Fotos del Vehículo</h6>

        <div class="mb-3">
            <label for="fotosVehiculos" class="form-label">
                Fotos
                <small class="text-muted">(JPEG, PNG, WebP, JPG - máx 2MB por archivo)</small>
            </label>
            <input type="file" class="form-control" id="fotosVehiculos" name="fotosVehiculos[]" multiple
                accept="image/jpeg,image/png,image/webp,image/jpg">
            <small id="fotosVehiculosHelp" class="form-text text-muted">Puede cargar una o más fotografías</small>
            <div class="invalid-feedback" id="error-fotosVehiculos"></div>

            @if (isset($vehiculo) && $vehiculo->id && $vehiculo->multimedia->count() > 0)
                <div class="mt-3">
                    <label class="form-label">Fotos Actuales:</label>
                    <div class="row g-2" id="fotosVehiculosActuales">
                        @foreach ($vehiculo->multimedia as $foto)
                            <div class="col-md-3" data-foto-id="{{ $foto->id }}">
                                <div class="position-relative">
                                    <img src="{{ url('storage/' . $foto->ruta) }}"
                                        alt="Foto" class="img-fluid rounded" style="max-height: 100px;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                        onclick="eliminarFotoVehiculo({{ $foto->id }})" style="z-index: 10;">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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
