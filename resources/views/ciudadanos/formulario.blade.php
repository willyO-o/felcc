{{-- Modal Header --}}
<div class="modal-header">
    <h5 class="modal-title">
        <i class="ri-user-line align-middle me-2"></i>
        {{ isset($ciudadano->id) ? 'Editar Ciudadano' : 'Nuevo Ciudadano' }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

{{-- Form --}}
<form id="ciudadanoForm"
    action="{{ isset($ciudadano->id) ? route('ciudadanos.update', $ciudadano->id) : route('ciudadanos.store') }}"
    method="POST">
    @csrf
    @if(isset($ciudadano->id))
        @method('PATCH')
    @endif

    <div class="modal-body">
        <div class="row">
            {{-- Identificación --}}
            <div class="col-md-12">
                <h6 class="mb-3">
                    <i class="ri-id-card-line me-2"></i> Identificación
                </h6>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="ciudadano" class="form-label">Ciudadano</label>
                    <input type="text" class="form-control" id="ciudadano" name="ciudadano"
                        value="{{ old('ciudadano', $ciudadano->ciudadano ?? '') }}">
                    <div id="error-ciudadano" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="cedula_act" class="form-label">Cédula de Identidad *</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="cedula_act" name="cedula_act"
                            value="{{ old('cedula_act', $ciudadano->cedula_act ?? '') }}">
                        <select name="tipo_cedula_act" id="tipo_cedula_act" class="form-select" style="max-width: 150px;">
                            <option value="">Tipo</option>
                            <option value="CI" {{ old('tipo_cedula_act', $ciudadano->tipo_cedula_act ?? '') === 'CI' ? 'selected' : '' }}>C.I.</option>
                            <option value="Pasaporte" {{ old('tipo_cedula_act', $ciudadano->tipo_cedula_act ?? '') === 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="Cédula de Extranjería" {{ old('tipo_cedula_act', $ciudadano->tipo_cedula_act ?? '') === 'Cédula de Extranjería' ? 'selected' : '' }}>Cédula Extranjería</option>
                        </select>
                    </div>
                    <div id="error-cedula_act" class="invalid-feedback"></div>
                </div>
            </div>

            {{-- Nombres y Apellidos --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombres" class="form-label">Nombres *</label>
                    <input type="text" class="form-control" id="nombres" name="nombres"
                        value="{{ old('nombres', $ciudadano->nombres ?? '') }}" required>
                    <div id="error-nombres" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="ap_pat" class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" id="ap_pat" name="ap_pat"
                        value="{{ old('ap_pat', $ciudadano->ap_pat ?? '') }}">
                    <div id="error-ap_pat" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="ap_mat" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="ap_mat" name="ap_mat"
                        value="{{ old('ap_mat', $ciudadano->ap_mat ?? '') }}">
                    <div id="error-ap_mat" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="ap_esp" class="form-label">Apellido de Esposo/a</label>
                    <input type="text" class="form-control" id="ap_esp" name="ap_esp"
                        value="{{ old('ap_esp', $ciudadano->ap_esp ?? '') }}">
                    <div id="error-ap_esp" class="invalid-feedback"></div>
                </div>
            </div>

            {{-- Datos Personales --}}
            <div class="col-md-12">
                <h6 class="mb-3 mt-2">
                    <i class="ri-profile-line me-2"></i> Datos Personales
                </h6>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="sexo" class="form-label">Sexo</label>
                    <select name="sexo" id="sexo" class="form-select">
                        <option value="">Seleccionar...</option>
                        <option value="M" {{ old('sexo', $ciudadano->sexo ?? '') === 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo', $ciudadano->sexo ?? '') === 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    <div id="error-sexo" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="estado_civil" class="form-label">Estado Civil</label>
                    <select name="estado_civil" id="estado_civil" class="form-select">
                        <option value="">Seleccionar...</option>
                        <option value="SOLTERO" {{ old('estado_civil', $ciudadano->estado_civil ?? '') === 'SOLTERO' ? 'selected' : '' }}>Soltero</option>
                        <option value="CASADO" {{ old('estado_civil', $ciudadano->estado_civil ?? '') === 'CASADO' ? 'selected' : '' }}>Casado</option>
                        <option value="DIVORCIADO" {{ old('estado_civil', $ciudadano->estado_civil ?? '') === 'DIVORCIADO' ? 'selected' : '' }}>Divorciado</option>
                        <option value="VIUDO" {{ old('estado_civil', $ciudadano->estado_civil ?? '') === 'VIUDO' ? 'selected' : '' }}>Viudo</option>
                        <option value="UNION_LIBRE" {{ old('estado_civil', $ciudadano->estado_civil ?? '') === 'UNION_LIBRE' ? 'selected' : '' }}>Unión Libre</option>
                        <option value="CONYUGUE" {{ old('estado_civil', $ciudadano->estado_civil ?? '') === 'CONYUGUE' ? 'selected' : '' }}>Cónyuge</option>
                    </select>
                    <div id="error-estado_civil" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="fecha_nac" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                        value="{{ old('fecha_nac', $ciudadano->fecha_nac?->format('Y-m-d') ?? '') }}">
                    <div id="error-fecha_nac" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="pais_nac" class="form-label">País de Nacimiento</label>
                    <input type="text" class="form-control" id="pais_nac" name="pais_nac"
                        value="{{ old('pais_nac', $ciudadano->pais_nac ?? '') }}">
                    <div id="error-pais_nac" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="ocupacion" class="form-label">Ocupación</label>
                    <input type="text" class="form-control" id="ocupacion" name="ocupacion"
                        value="{{ old('ocupacion', $ciudadano->ocupacion ?? '') }}">
                    <div id="error-ocupacion" class="invalid-feedback"></div>
                </div>
            </div>

            {{-- Dirección y Ubicación --}}
            <div class="col-md-12">
                <h6 class="mb-3 mt-2">
                    <i class="ri-map-pin-line me-2"></i> Dirección y Ubicación
                </h6>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label for="dom_1" class="form-label">Domicilio Principal</label>
                    <textarea class="form-control" id="dom_1" name="dom_1" rows="2">{{ old('dom_1', $ciudadano->dom_1 ?? '') }}</textarea>
                    <div id="error-dom_1" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label for="dom_2" class="form-label">Domicilio Secundario</label>
                    <textarea class="form-control" id="dom_2" name="dom_2" rows="2">{{ old('dom_2', $ciudadano->dom_2 ?? '') }}</textarea>
                    <div id="error-dom_2" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="nom_dep" class="form-label">Departamento</label>
                    <input type="text" class="form-control" id="nom_dep" name="nom_dep"
                        value="{{ old('nom_dep', $ciudadano->nom_dep ?? '') }}">
                    <div id="error-nom_dep" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="nom_prov" class="form-label">Provincia</label>
                    <input type="text" class="form-control" id="nom_prov" name="nom_prov"
                        value="{{ old('nom_prov', $ciudadano->nom_prov ?? '') }}">
                    <div id="error-nom_prov" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="nom_mun" class="form-label">Municipio</label>
                    <input type="text" class="form-control" id="nom_mun" name="nom_mun"
                        value="{{ old('nom_mun', $ciudadano->nom_mun ?? '') }}">
                    <div id="error-nom_mun" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="id_loc" class="form-label">ID Localidad</label>
                    <input type="number" class="form-control" id="id_loc" name="id_loc"
                        value="{{ old('id_loc', $ciudadano->id_loc ?? '') }}">
                    <div id="error-id_loc" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="id_departamento" class="form-label">ID Departamento</label>
                    <input type="number" class="form-control" id="id_departamento" name="id_departamento"
                        value="{{ old('id_departamento', $ciudadano->id_departamento ?? '') }}">
                    <div id="error-id_departamento" class="invalid-feedback"></div>
                </div>
            </div>

            {{-- Datos Electorales --}}
            <div class="col-md-12">
                <h6 class="mb-3 mt-2">
                    <i class="ri-checkbox-multiple-line me-2"></i> Datos Electorales
                </h6>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="mesa_ciudadano" class="form-label">Mesa Ciudadano</label>
                    <input type="number" class="form-control" id="mesa_ciudadano" name="mesa_ciudadano"
                        value="{{ old('mesa_ciudadano', $ciudadano->mesa_ciudadano ?? '') }}">
                    <div id="error-mesa_ciudadano" class="invalid-feedback"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="partida_mesa_ciudadano" class="form-label">Partida Mesa Ciudadano</label>
                    <input type="number" class="form-control" id="partida_mesa_ciudadano" name="partida_mesa_ciudadano"
                        value="{{ old('partida_mesa_ciudadano', $ciudadano->partida_mesa_ciudadano ?? '') }}">
                    <div id="error-partida_mesa_ciudadano" class="invalid-feedback"></div>
                </div>
            </div>

            {{-- Estado --}}
            <div class="col-md-12">
                <h6 class="mb-3 mt-2">
                    <i class="ri-toggle-line me-2"></i> Estado
                </h6>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="estado_registro" class="form-label">Estado del Registro</label>
                    <select name="estado_registro" id="estado_registro" class="form-select">
                        <option value="1" {{ old('estado_registro', $ciudadano->estado_registro ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado_registro', $ciudadano->estado_registro ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    <div id="error-estado_registro" class="invalid-feedback"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Footer --}}
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line align-middle me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary" id="btnGuardarCiudadano">
            <i class="ri-save-line align-middle me-1"></i> Guardar
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('ciudadanoForm');
        const btnGuardar = document.getElementById('btnGuardarCiudadano');

        if (btnGuardar) {
            btnGuardar.addEventListener('click', function(e) {
                e.preventDefault();
                guardarCiudadano(form);
            });
        }
    });
</script>
