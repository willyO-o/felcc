<div class="modal-header border-0 pb-0">
    <div class="w-100">
        <h4 class="fw-bold mb-1">{{ strtoupper($datos->nombre_completo ?? 'N/A') }}</h4>
        <div class="d-flex flex-wrap gap-3 align-items-center text-muted small">
            <span>C.I.: <strong class="text-primary">{{ $datos->cedula_act ?? 'N/A' }}</strong></span>
            <span class="text-muted">|</span>
            <span>Fecha Registro: <strong>{{ $datos->fecha_ins?->format('d/m/Y') ?? 'N/A' }}</strong></span>
            <span class="text-muted">|</span>
            <span>
                <span class="badge bg-{{ $datos->estado_registro == 1 ? 'success' : 'danger' }}">
                    {{ $datos->estado_registro == 1 ? 'Activo' : 'Inactivo' }}
                </span>
            </span>
        </div>
    </div>
    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body pt-2" style="max-height: 78vh; overflow-y: auto;">
    <div class="container-fluid px-2">

        {{-- ===== DATOS PERSONALES ===== --}}
        <p class="section-title mt-3"><i class="ri-id-card-line me-1"></i> Datos Personales</p>
        <table class="table table-sm table-bordered info-table mb-3">
            <tbody>
                <tr>
                    <th>Nombre</th>
                    <td>{{ $datos->nombres ?? 'N/A' }}</td>
                    <th>Apellido Paterno</th>
                    <td>{{ $datos->ap_pat ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Apellido Materno</th>
                    <td>{{ $datos->ap_mat ?? 'N/A' }}</td>
                    <th>Apellido Esposo/a</th>
                    <td>{{ $datos->ap_esp ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Género</th>
                    <td>
                        @if($datos->sexo)
                            {{ in_array($datos->sexo, ['M','MASCULINO']) ? 'Masculino' : 'Femenino' }}
                        @else N/A @endif
                    </td>
                    <th>Estado Civil</th>
                    <td>
                        @php
                            $mapEstadoCivil = ['SOLTERO'=>'Soltero/a','CASADO'=>'Casado/a','DIVORCIADO'=>'Divorciado/a','VIUDO'=>'Viudo/a','UNION_LIBRE'=>'Unión Libre','CONYUGUE'=>'Cónyuge'];
                        @endphp
                        {{ $mapEstadoCivil[$datos->estado_civil] ?? ($datos->estado_civil ?? 'N/A') }}
                    </td>
                </tr>
                <tr>
                    <th>Fecha de Nacimiento</th>
                    <td>{{ $datos->fecha_nac?->format('d/m/Y') ?? 'N/A' }}</td>
                    <th>País de Nacimiento</th>
                    <td>{{ $datos->pais_nac ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Ocupación</th>
                    <td colspan="3">{{ $datos->ocupacion ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- ===== IDENTIFICACIÓN ===== --}}
        <p class="section-title"><i class="ri-passport-line me-1"></i> Identificación</p>
        <table class="table table-sm table-bordered info-table mb-3">
            <tbody>
                <tr>
                    <th>Nro. Cédula</th>
                    <td>{{ $datos->cedula_act ?? 'N/A' }}</td>
                    <th>Tipo de Cédula</th>
                    <td>{{ $datos->tipo_cedula_act ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Código Ciudadano</th>
                    <td colspan="3">{{ $datos->ciudadano ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- ===== DOMICILIO ===== --}}
        <p class="section-title"><i class="ri-map-pin-line me-1"></i> Domicilio</p>
        <table class="table table-sm table-bordered info-table mb-3">
            <tbody>
                <tr>
                    <th style="width:15%">Dirección 1</th>
                    <td colspan="3">{{ $datos->dom_1 ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Dirección 2</th>
                    <td colspan="3">{{ $datos->dom_2 ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- ===== LOCALIDAD ===== --}}
        <p class="section-title"><i class="ri-layout-grid-line me-1"></i> Localidad</p>
        <table class="table table-sm table-bordered info-table mb-3">
            <tbody>

                <tr>
                    <th>Departamento</th>
                    <td>{{ $datos->nom_dep ?? 'N/A' }}</td>
                    <th>Provincia</th>
                    <td>{{ $datos->nom_prov ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Municipio</th>
                    <td colspan="3">{{ $datos->nom_mun ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- ===== DATOS ELECTORALES ===== --}}
        <p class="section-title"><i class="ri-checkbox-multiple-line me-1"></i> Datos Electorales</p>
        <table class="table table-sm table-bordered info-table mb-3">
            <tbody>
                <tr>
                    <th style="width:25%">Mesa Ciudadano</th>
                    <td>{{ $datos->mesa_ciudadano ?? 'N/A' }}</td>
                    <th>Partida Mesa</th>
                    <td>{{ $datos->partida_mesa_ciudadano ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- ===== DATOS ADMINISTRATIVOS ===== --}}
        <p class="section-title"><i class="ri-settings-3-line me-1"></i> Datos Administrativos</p>
        <table class="table table-sm table-bordered info-table mb-3">
            <tbody>
                <tr>
                    <th style="width:25%">Estado del Registro</th>
                    <td>
                        <span class="badge bg-{{ $datos->estado_registro == 1 ? 'success' : 'danger' }}">
                            {{ $datos->estado_registro == 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <th>Fecha de Inscripción</th>
                    <td>{{ $datos->fecha_ins?->format('d/m/Y H:i:s') ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- ===== LOCALIDAD RELACIONADA (notarios y libros) ===== --}}
        <p class="section-title"><i class="ri-links-line me-1"></i> Localidad Relacionada</p>
        @if($datos->localidad)
            <table class="table table-sm table-bordered info-table mb-3">
                <tbody>
                    <tr>
                        <th style="width:15%">Localidad</th>
                        <td>{{ $datos->localidad->nom_loc ?? 'N/A' }}</td>
                        <th>Departamento</th>
                        <td>{{ $datos->localidad->nom_dep ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Provincia</th>
                        <td>{{ $datos->localidad->nom_prov ?? 'N/A' }}</td>
                        <th>Sección</th>
                        <td>{{ $datos->localidad->nom_sec ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Cantón</th>
                        <td colspan="3">{{ $datos->localidad->nom_can ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>

                    <p class="section-title"><i class="ri-user-line me-1"></i> Notarios de la localidad</p>

            <div class="table-responsive mb-3">
                <table class="table table-sm table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Zona</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datos->localidad->notarios as $notario)
                            <tr>
                                <td>{{ $notario->id }}</td>
                                <td>{{ $notario->cod_notario ?? 'N/A' }}</td>
                                <td>{{ $notario->nom_not_e ?? 'N/A' }}</td>
                                <td>{{ $notario->direccion ?? 'N/A' }}</td>
                                <td>{{ $notario->zona ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Sin notarios asociados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="section-title"><i class="ri-links-line me-1"></i> Libros de la localidad</p>
            <div class="table-responsive mb-3">
                <table class="table table-sm table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            <th>Cód. Notario</th>
                            <th>Circunscripción</th>
                            <th>Distrito</th>
                            <th>Zona</th>
                            <th>Receptoría</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datos->localidad->libros as $libro)
                            <tr>
                                <td>{{ $libro->id }}</td>
                                <td>{{ $libro->num_libro ?? 'N/A' }}</td>
                                <td>{{ $libro->cod_notario ?? 'N/A' }}</td>
                                <td>{{ $libro->nom_circun ?? 'N/A' }}</td>
                                <td>{{ $libro->nom_dist ?? 'N/A' }}</td>
                                <td>{{ $libro->nom_zona ?? 'N/A' }}</td>
                                <td>{{ $libro->nom_reci ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Sin libros asociados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted small">No existe localidad relacionada para este ciudadano.</p>
        @endif

    </div>
</div>

<style>
    .section-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 3px;
        margin-bottom: 6px;
    }

    .info-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.8rem;
        color: #495057;
        white-space: nowrap;
        width: 15%;
    }

    .info-table td {
        font-size: 0.875rem;
        color: #212529;
    }

    .info-table th, .info-table td {
        padding: 0.4rem 0.6rem;
        vertical-align: middle;
    }
</style>

<div class="modal-footer border-top py-2">
    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line align-middle me-1"></i> Cerrar
    </button>
</div>


