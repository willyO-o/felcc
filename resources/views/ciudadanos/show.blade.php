<div class="modal-header">
    <h5 class="modal-title">
        <i class="ri-user-line align-middle me-2"></i>
        Detalles del Ciudadano
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row">
        {{-- Identificación --}}
        <div class="col-md-12">
            <h6 class="mb-3 border-bottom pb-2">
                <i class="ri-id-card-line me-2"></i> Identificación
            </h6>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Ciudadano:</strong><br>
                <span class="text-muted">{{ $datos->ciudadano ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Cédula de Identidad:</strong><br>
                <span class="text-muted">{{ $datos->cedula_act ?? 'N/A' }} {{ $datos->tipo_cedula_act ? '(' . $datos->tipo_cedula_act . ')' : '' }}</span>
            </p>
        </div>

        {{-- Nombres y Apellidos --}}
        <div class="col-md-12">
            <p class="mb-2">
                <strong>Nombre Completo:</strong><br>
                <span class="text-muted">{{ $datos->nombre_completo }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Apellido Paterno:</strong><br>
                <span class="text-muted">{{ $datos->ap_pat ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Apellido Materno:</strong><br>
                <span class="text-muted">{{ $datos->ap_mat ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Apellido de Esposo/a:</strong><br>
                <span class="text-muted">{{ $datos->ap_esp ?? 'N/A' }}</span>
            </p>
        </div>

        {{-- Datos Personales --}}
        <div class="col-md-12">
            <h6 class="mb-3 border-bottom pb-2 mt-3">
                <i class="ri-profile-line me-2"></i> Datos Personales
            </h6>
        </div>

        <div class="col-md-4">
            <p class="mb-2">
                <strong>Sexo:</strong><br>
                <span class="text-muted">{{ $datos->sexo_formatted ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-4">
            <p class="mb-2">
                <strong>Estado Civil:</strong><br>
                <span class="text-muted">{{ $datos->estado_civil_formatted ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-4">
            <p class="mb-2">
                <strong>Fecha de Nacimiento:</strong><br>
                <span class="text-muted">{{ $datos->fecha_nac?->format('d/m/Y') ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>País de Nacimiento:</strong><br>
                <span class="text-muted">{{ $datos->pais_nac ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Ocupación:</strong><br>
                <span class="text-muted">{{ $datos->ocupacion ?? 'N/A' }}</span>
            </p>
        </div>

        {{-- Dirección y Ubicación --}}
        <div class="col-md-12">
            <h6 class="mb-3 border-bottom pb-2 mt-3">
                <i class="ri-map-pin-line me-2"></i> Dirección y Ubicación
            </h6>
        </div>

        <div class="col-md-12">
            <p class="mb-2">
                <strong>Domicilio Principal:</strong><br>
                <span class="text-muted">{{ $datos->dom_1 ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-12">
            <p class="mb-2">
                <strong>Domicilio Secundario:</strong><br>
                <span class="text-muted">{{ $datos->dom_2 ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-4">
            <p class="mb-2">
                <strong>Departamento:</strong><br>
                <span class="text-muted">{{ $datos->nom_dep ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-4">
            <p class="mb-2">
                <strong>Provincia:</strong><br>
                <span class="text-muted">{{ $datos->nom_prov ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-4">
            <p class="mb-2">
                <strong>Municipio:</strong><br>
                <span class="text-muted">{{ $datos->nom_mun ?? 'N/A' }}</span>
            </p>
        </div>

        {{-- Datos Electorales --}}
        <div class="col-md-12">
            <h6 class="mb-3 border-bottom pb-2 mt-3">
                <i class="ri-checkbox-multiple-line me-2"></i> Datos Electorales
            </h6>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Mesa Ciudadano:</strong><br>
                <span class="text-muted">{{ $datos->mesa_ciudadano ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Partida Mesa Ciudadano:</strong><br>
                <span class="text-muted">{{ $datos->partida_mesa_ciudadano ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Fecha de Inscripción:</strong><br>
                <span class="text-muted">{{ $datos->fecha_ins?->format('d/m/Y H:i') ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="col-md-6">
            <p class="mb-2">
                <strong>Estado del Registro:</strong><br>
                <span class="badge bg-{{ $datos->estado_registro == 1 ? 'success' : 'danger' }}">
                    {{ $datos->estado_registro_formatted ?? 'N/A' }}
                </span>
            </p>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line align-middle me-1"></i> Cerrar
    </button>
</div>
