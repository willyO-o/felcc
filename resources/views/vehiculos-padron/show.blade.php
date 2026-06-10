<div class="modal-header border-0 pb-0">
    <div class="w-100">
        <h5 class="fw-bold mb-1">
            <i class="ri-car-line text-primary me-2"></i>
            {{ strtoupper($datos->propietario ?? 'N/A') }}
        </h5>
        <div class="d-flex flex-wrap gap-3 align-items-center text-muted small">
            <span>Placa: <strong class="text-primary">{{ $datos->placa ?? 'N/A' }}</strong></span>
            @if($datos->placaantigua)
                <span class="text-muted">|</span>
                <span>Placa Antigua: <strong>{{ $datos->placaantigua }}</strong></span>
            @endif
        </div>
    </div>
    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body pt-2" style="max-height: 75vh; overflow-y: auto;">
    <div class="container-fluid px-2">

        {{-- Identificación del Vehículo --}}
        <p class="section-title mt-3"><i class="ri-car-line me-1"></i> Identificación del Vehículo</p>
        <table class="table table-sm table-bordered info-table mb-3">
            <tbody>
                <tr>
                    <th style="width:20%">Placa</th>
                    <td>{{ $datos->placa ?? 'N/A' }}</td>
                    <th style="width:20%">Placa Antigua</th>
                    <td>{{ $datos->placaantigua ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Marca</th>
                    <td>{{ $datos->marca ?? 'N/A' }}</td>
                    <th>Modelo</th>
                    <td>{{ $datos->modelo ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Clase</th>
                    <td>{{ $datos->clase ?? 'N/A' }}</td>
                    <th>Tipo</th>
                    <td>{{ $datos->tipo ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Color</th>
                    <td>{{ $datos->color ?? 'N/A' }}</td>
                    <th>Servicio</th>
                    <td>{{ $datos->servicio ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>N° Chasis</th>
                    <td>{{ $datos->nochasis ?? 'N/A' }}</td>
                    <th>N° Motor</th>
                    <td>{{ $datos->nomotor ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Póliza</th>
                    <td colspan="3">{{ $datos->poliza ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Datos del Propietario --}}
        <p class="section-title"><i class="ri-user-line me-1"></i> Datos del Propietario</p>
        <table class="table table-sm table-bordered info-table mb-3">
            <tbody>
                <tr>
                    <th style="width:20%">Propietario</th>
                    <td colspan="3">{{ $datos->propietario ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Doc. Identidad</th>
                    <td>{{ $datos->docidentidad ?? 'N/A' }}</td>
                    <th>Alcaldía</th>
                    <td>{{ $datos->alcaldia ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Domicilio</th>
                    <td colspan="3">{{ $datos->dompropietario ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

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
