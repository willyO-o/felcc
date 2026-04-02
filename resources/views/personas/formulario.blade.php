<div class="modal-header border-bottom">
    <h5 class="modal-title" id="modalPersonaLabel">
        {{ isset($persona->id) ? 'Editar Persona' : 'Registrar Persona' }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form
    action="{{ isset($persona->id) ? route('personas.update', $persona->id) : route('personas.store') }}"
    method="POST" id="personaForm" enctype="multipart/form-data">
    @csrf
    @if (isset($persona->id))
        @method('PUT')
    @endif

    <div class="modal-body" >
        <div class="row g-3">
            {{-- Información Personal Básica --}}
            <div class="col-md-6">
                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                <input type="text" class="form-control txtMayuscula" id="nombres" name="nombres"
                    value="{{ old('nombres', $persona->nombres ?? '') }}" placeholder="Ingrese nombres" required>
                <div class="invalid-feedback" id="error-nombres"></div>
            </div>

            <div class="col-md-6">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control txtMayuscula" id="apellidos" name="apellidos"
                    value="{{ old('apellidos', $persona->apellidos ?? '') }}" placeholder="Ingrese apellidos">
                <div class="invalid-feedback" id="error-apellidos"></div>
            </div>

            <div class="col-md-4">
                <label for="ci" class="form-label">Cédula de Identidad</label>
                <input type="text" class="form-control txtMayuscula" id="ci" name="ci" maxlength="20"
                    value="{{ old('ci', $persona->ci ?? '') }}" placeholder="Ej: 1234567 LP">
                <div class="invalid-feedback" id="error-ci"></div>
            </div>

            <div class="col-md-2">
                <label for="complemento" class="form-label">Complemento </label>
                <input type="text" class="form-control txtMayuscula" id="complemento" name="complemento" maxlength="40"
                    value="{{ old('complemento', $persona->complemento ?? '') }}" placeholder="Ej: A, B, etc">
                <div class="invalid-feedback" id="error-complemento"></div>
            </div>
            <div class="col-md-6">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="25"
                    value="{{ old('telefono', $persona->telefono ?? '') }}" placeholder="Ej: +591 1234567">
                <div class="invalid-feedback" id="error-telefono"></div>
            </div>
            {{-- Información de Nacimiento --}}
            <div class="col-md-6">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                    value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento ? $persona->fecha_nacimiento->format('Y-m-d') : '') }}">
                <div class="invalid-feedback" id="error-fecha_nacimiento"></div>
            </div>

            <div class="col-md-6">
                <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento</label>
                <input type="text" class="form-control txtMayuscula" id="lugar_nacimiento" name="lugar_nacimiento" maxlength="250"
                    value="{{ old('lugar_nacimiento', $persona->lugar_nacimiento ?? '') }}"
                    placeholder="Ciudad o región">
                <div class="invalid-feedback" id="error-lugar_nacimiento"></div>
            </div>

            <div class="col-md-6">
                <label for="id_pais" class="form-label">País</label>
                <select class="form-select" id="id_pais" name="id_pais">
                    <option value="">Seleccione un país</option>
                    @foreach ($paises as $pais)
                        <option value="{{ $pais->id }}"
                            {{ old('id_pais', $persona->id_pais ?? '') == $pais->id ? 'selected' : '' }}>
                            {{ $pais->pais }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="error-id_pais"></div>
            </div>

            {{-- Información Demográfica --}}
            <div class="col-md-6">
                <label for="genero" class="form-label">Género</label>
                <select class="form-select" id="genero" name="genero">
                    <option value="">Seleccione género</option>
                    <option value="MASCULINO" {{ old('genero', $persona->genero ?? '') == 'MASCULINO' ? 'selected' : '' }}>
                        Masculino
                    </option>
                    <option value="FEMENINO" {{ old('genero', $persona->genero ?? '') == 'FEMENINO' ? 'selected' : '' }}>
                        Femenino
                    </option>
                </select>
                <div class="invalid-feedback" id="error-genero"></div>
            </div>

            <div class="col-md-6">
                <label for="estado_civil" class="form-label">Estado Civil</label>
                <select class="form-select" id="estado_civil" name="estado_civil">
                    <option value="">Seleccione estado civil</option>
                    <option value="SOLTERO" {{ old('estado_civil', $persona->estado_civil ?? '') == 'SOLTERO' ? 'selected' : '' }}>
                        Soltero/a
                    </option>
                    <option value="CASADO" {{ old('estado_civil', $persona->estado_civil ?? '') == 'CASADO' ? 'selected' : '' }}>
                        Casado/a
                    </option>
                    <option value="DIVORCIADO" {{ old('estado_civil', $persona->estado_civil ?? '') == 'DIVORCIADO' ? 'selected' : '' }}>
                        Divorciado/a
                    </option>
                    <option value="VIUDO" {{ old('estado_civil', $persona->estado_civil ?? '') == 'VIUDO' ? 'selected' : '' }}>
                        Viudo/a
                    </option>
                    <option value="CONYUGUE" {{ old('estado_civil', $persona->estado_civil ?? '') == 'CONYUGUE' ? 'selected' : '' }}>
                        Cónyuge
                    </option>
                </select>
                <div class="invalid-feedback" id="error-estado_civil"></div>
            </div>
           <div class="col-md-6">
                <label for="ocupacion" class="form-label">Ocupación</label>
                <input type="text" class="form-control txtMayuscula" id="ocupacion" name="ocupacion" maxlength="150"
                    value="{{ old('ocupacion', $persona->ocupacion ?? '') }}" placeholder="Profesión u oficio">
                <div class="invalid-feedback" id="error-ocupacion"></div>
            </div>
           <div class="col-md-6">
                <label for="grupo_sanguineo" class="form-label">Grupo Sanguineo</label>
                <input type="text" class="form-control txtMayuscula" id="grupo_sanguineo" name="grupo_sanguineo" maxlength="150"
                    value="{{ old('grupo_sanguineo', $persona->grupo_sanguineo ?? '') }}" placeholder="Ej: A+, O-, etc">
                <div class="invalid-feedback" id="error-grupo_sanguineo"></div>
            </div>
            <div class="col-md-6">
                <label for="nombre_conyuge" class="form-label">Nombre del Cónyuge</label>
                <input type="text" class="form-control txtMayuscula" id="nombre_conyuge" name="nombre_conyuge" maxlength="250"
                    value="{{ old('nombre_conyuge', $persona->nombre_conyuge ?? '') }}"
                    placeholder="Nombre completo del cónyuge">
                <div class="invalid-feedback" id="error-nombre_conyuge"></div>
            </div>
            <div class="col-md-6">
                <label for="padre" class="form-label">Nombre del Padre</label>
                <input type="text" class="form-control txtMayuscula" id="padre" name="padre" maxlength="250"
                    value="{{ old('padre', $persona->padre ?? '') }}"
                    placeholder="Nombre completo del padre">
                <div class="invalid-feedback" id="error-padre"></div>
            </div>
            <div class="col-md-6">
                <label for="madre" class="form-label">Nombre de la Madre</label>
                <input type="text" class="form-control txtMayuscula" id="madre" name="madre" maxlength="250"
                    value="{{ old('madre', $persona->madre ?? '') }}"
                    placeholder="Nombre completo de la madre">
                <div class="invalid-feedback" id="error-madre"></div>
            </div>




            {{-- Información de Contacto --}}
            <div class="col-md-6">
                <label for="domicilio" class="form-label">Domicilio</label>
                <textarea class="form-control" id="domicilio" name="domicilio" rows="3" maxlength="500"
                    placeholder="Dirección completa">{{ old('domicilio', $persona->domicilio ?? '') }}</textarea>
                <div class="invalid-feedback" id="error-domicilio"></div>
            </div>
            <div class="col-md-6">
                <label for="datos_segip" class="form-label">Datos Segip</label>
                <textarea class="form-control" id="datos_segip" name="datos_segip" rows="3" maxlength="500"
                    placeholder="Datos del Segip">{{ old('datos_segip', $persona->datos_segip ?? '') }}</textarea>
                <div class="invalid-feedback" id="error-datos_segip"></div>
            </div>
            <div class="col-md-6">
                <label for="responsable" class="form-label">Nombre del responsable</label>
                <input type="text" class="form-control txtMayuscula" id="responsable" name="responsable" maxlength="250"
                    value="{{ old('responsable', $persona->responsable ?? '') }}"
                    placeholder="Nombre completo del responsable">
                <div class="invalid-feedback" id="error-responsable"></div>
            </div>



            {{-- Fotos --}}
            <div class="col-12">
                <label for="fotos" class="form-label">
                    Fotos
                    <small class="text-muted">(JPEG, PNG, WebP, JPG - máx 2MB por archivo)</small>
                </label>
                <input type="file" class="form-control" id="fotos" name="fotos[]" multiple
                    accept="image/jpeg,image/png,image/webp,image/jpg">
                <small id="fotosHelp" class="form-text text-muted">Puede cargar una o más fotografías</small>
                <div class="invalid-feedback" id="error-fotos"></div>

                @if (isset($persona->id) && $persona->multimedia->count() > 0)
                    <div class="mt-3">
                        <label class="form-label">Fotos Actuales:</label>
                        <div class="row g-2" id="fotosActuales">
                            @foreach ($persona->multimedia as $foto)
                                <div class="col-md-3" data-foto-id="{{ $foto->id }}">
                                    <div class="position-relative">
                                        <img src="{{ url('storage/' . $foto->ruta) }}"
                                            alt="Foto" class="img-fluid rounded" style="max-height: 100px;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                            onclick="eliminarFoto({{ $foto->id }})" style="z-index: 10;">
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
    </div>

    <div class="modal-footer border-top" >
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarPersona">
            <i class="ri-save-3-line align-middle me-1"></i> Guardar
        </button>
    </div>
</form>
