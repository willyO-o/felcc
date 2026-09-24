<style>
    .detalles-vehiculo {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }

    /* Header con gradiente personalizado */
    .detalles-vehiculo .header-placa {
        background-color: rgba(255, 255, 255, 0.03);
        padding: 1.5rem;
        text-align: center;
        margin: -1rem -1rem 1.5rem -1rem;
        border-radius: 0.375rem 0.375rem 0 0;
    }

    [data-bs-theme="light"] .detalles-vehiculo .header-placa {
        background-color: rgba(255, 255, 255, 0.03);
    }

    .detalles-vehiculo .header-placa h1 {
        font-size: 1.75rem;
        color: #fff;
        margin: 0;
        font-weight: 600;
        letter-spacing: 2px;
    }

    /* Secciones principales */
    .detalles-vehiculo .seccion-principal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detalles-vehiculo .fotos-vehiculo {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .detalles-vehiculo .fotos-vehiculo {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .detalles-vehiculo .fotos-vehiculo>* {
            flex: 1 1 calc(50% - 0.5rem);
            min-width: 0;
        }
    }

    .detalles-vehiculo .foto-item {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
        overflow: hidden;
        background-color: var(--bs-secondary-bg);
        border: 1px solid var(--bs-border-color);
    }

    .detalles-vehiculo .foto-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .detalles-vehiculo .info-general {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .detalles-vehiculo .info-general p {
        margin-bottom: 0.5rem;
        color: var(--bs-body-color);
    }

    /* Headers de sección */
    .detalles-vehiculo .section-title {
        /* background-color: #198754; */
        color: #fff;
        padding: 0.5rem 0.75rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 0.375rem;
        display: inline-block;
        width: 100%;
    }

    [data-bs-theme="dark"] .detalles-vehiculo .section-title {
        /* background-color: #20c997; */
    }

    .detalles-vehiculo .badge-count {
        margin-left: 0.5rem;
    }

    /* Items de relación */
    .detalles-vehiculo .relacion-item {
        padding: 0.75rem;
        background-color: var(--bs-secondary-bg);
        border-left: 3px solid var(--bs-success);
        border-radius: 0 0.25rem 0.25rem 0;
        line-height: 1.4;
        color: var(--bs-body-color);
    }

    .detalles-vehiculo .seccion-relacion {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--bs-border-color);
    }

    /* Secciones */
    .detalles-vehiculo .seccion-registros {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--bs-border-color);
    }

    .detalles-vehiculo .seccion-itv,
    .detalles-vehiculo .seccion-ultimo-carguio {
        margin-bottom: 1.5rem;
    }

    /* Tablas */
    .detalles-vehiculo .tabla-info {
        font-size: 0.9375rem;
    }

    .detalles-vehiculo .tabla-carguios,
    .detalles-vehiculo .tabla-itv,
    .detalles-vehiculo .tabla-ultimo-carguio {
        font-size: 0.85rem;
    }

    /* Fila de énfasis (último carguio, última inspección) */
    .detalles-vehiculo .enfasis td {
        background-color: var(--bs-tertiary-bg) !important;
    }

    .detalles-vehiculo .table-success {
        background-color: var(--bs-success) !important;
        color: #fff;
    }



    /* Responsive */
    @media (max-width: 768px) {
        .detalles-vehiculo .seccion-principal {
            grid-template-columns: 1fr;
        }

        .detalles-vehiculo .seccion-relacion {
            grid-template-columns: 1fr;
        }

        .detalles-vehiculo .header-placa h1 {
            font-size: 1.5rem;
        }

        .detalles-vehiculo .tabla-carguios,
        .detalles-vehiculo .tabla-itv,
        .detalles-vehiculo .tabla-ultimo-carguio {
            font-size: 0.75rem;
        }

        .detalles-vehiculo .tabla-carguios th,
        .detalles-vehiculo .tabla-itv th,
        .detalles-vehiculo .tabla-ultimo-carguio th {
            padding: 0.5rem;
        }

        .detalles-vehiculo .tabla-carguios td,
        .detalles-vehiculo .tabla-itv td,
        .detalles-vehiculo .tabla-ultimo-carguio td {
            padding: 0.5rem;
        }
    }

    .seccion-registros thead,
    .seccion-registros tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .seccion-registros .enfasis td {
        background-color: #fbea4d6c ! important;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">Detalles del Vehículo</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="detalles-vehiculo">
        <!-- Encabezado con Placa -->
        <div class="header-placa bg-soft-success">
            <h1 class="text-dark"> {{ $vehiculo->placa }}</h1>
        </div>

        <!-- Sección Principal: Fotos e Información -->
        <div class="seccion-principal">
            <!-- Fotos del vehículo -->
            <div class="fotos-vehiculo">
                @forelse ($vehiculo->multimedia as $foto)
                    <div class="foto-item">
                        <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto del vehículo" class="img-fluid">
                    </div>
                @empty
                    <div class="foto-item d-flex flex-column align-items-center justify-content-center text-muted">
                        <i class="ri-image-2-line" style="font-size: 2rem;"></i>
                        <small class="mt-2">Sin fotos</small>
                    </div>
                    <div class="foto-item d-flex flex-column align-items-center justify-content-center text-muted">
                        <i class="ri-image-2-line" style="font-size: 2rem;"></i>
                        <small class="mt-2">Sin fotos</small>
                    </div>
                @endforelse
            </div>

            <!-- Información General -->
            <div class="info-general">

                <p>
                    Placa: <strong>{{ $vehiculo->placa }}</strong><br>
                </p>
                <p class="">
                    {{ $vehiculo->descripcion ?? 'Sin descripción disponible' }}

                </p>

                <table class="tabla-info d-none">
                    <tbody>
                        <tr>
                            <th colspan="2" style="text-align: center; background-color: var(--color-header);">ESTADO
                            </th>
                        </tr>
                        <tr>
                            <td><strong>PLACA</strong></td>
                            <td>5711HZN</td>
                        </tr>
                        <tr>
                            <td><strong>MARCA</strong></td>
                            <td>TOYOTA</td>
                        </tr>
                        <tr>
                            <td><strong>COLOR</strong></td>
                            <td>PERLA</td>
                        </tr>
                        <tr>
                            <td><strong>MODELO</strong></td>
                            <td>2020</td>
                        </tr>
                        <tr>
                            <td><strong>CLASE</strong></td>
                            <td>VAGONETA</td>
                        </tr>
                        <tr>
                            <td><strong>MOTOR</strong></td>
                            <td>IGR-C147767</td>
                        </tr>
                        <tr>
                            <td><strong>CHASIS</strong></td>
                            <td>JTEBU3FJ8LK188737</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección de Registros -->
        <div class="seccion-registros mb-3">


            <table class="tabla-info table table-sm table-striped align-middle">
                <tbody>
                    <tr>
                        <th class="text-center table-success">
                            Tipo de Registro
                        </th>
                        <th class="text-center table-success">
                            Nombre
                        </th>
                        <th class="text-center table-success">
                            C.I
                        </th>
                        <th class="text-center table-success">
                            Teléfono
                        </th>
                    </tr>

                    @forelse ($vehiculo->personas as $persona)
                        <tr class="border">
                            <th class="text-center"> {{ $persona->pivot->tipo }} </th>
                            <td> {{ $persona->nombres }} {{ $persona->apellidos }} </td>
                            <td> {{ $persona->ci }} </td>
                            <td> {{ $persona->telefono }} </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">No hay personas vinculadas a este vehículo.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
            <div class="d-none">
                <!-- RUAT -->
                <div>
                    <h3>TIPO DE REGISTRO - RUAT</h3>
                    <div class="relacion-contenido">
                        <div class="relacion-item"><strong>Tipo:</strong> REGISTRO RUAT</div>
                        <div class="relacion-item"><strong>Nombre:</strong> RONALD CORREA PADILLA</div>
                        <div class="relacion-item"><strong>C.I.:</strong> 3279696</div>
                        <div class="relacion-item"><strong>Celular:</strong> -</div>
                    </div>
                </div>

                <!-- ANH -->
                <div>
                    <h3>TIPO DE REGISTRO - ANH</h3>
                    <div class="relacion-contenido">
                        <div class="relacion-item"><strong>Tipo:</strong> REGISTRO ANH</div>
                        <div class="relacion-item"><strong>Nombre:</strong> ROBERTO ARAÑA SUAREZ</div>
                        <div class="relacion-item"><strong>C.I.:</strong> 5349829</div>
                        <div class="relacion-item"><strong>Celular:</strong> -</div>
                    </div>
                </div>

                <!-- COMPRA SOAT -->
                <div>
                    <h3>COMPRA SOAT</h3>
                    <div class="relacion-contenido">
                        <div class="relacion-item"><strong>Nombre:</strong> NATALIA MILLET DE ARAÑA</div>
                        <div class="relacion-item"><strong>C.I.:</strong> 6288312</div>
                        <div class="relacion-item"><strong>Celular:</strong> 75020289</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección RELACIÓN OTID -->
        <div class="seccion-relacion">
            <div class="relacion-columna">
                <h5 class="section-title bg-soft-success text-dark">Caso Relacionado</h5>
                <div class="relacion-item">
                    <strong>{{ $vehiculo->caso_relacionado ?? '-' }}</strong>
                </div>
            </div>

            <div class="relacion-columna">
                <h5 class="section-title bg-soft-success text-dark">Responsable</h5>
                <div class="relacion-item">{{ $vehiculo->responsable ?? '-' }}</div>
            </div>
        </div>

        <!-- Sección CARGUIOS -->
        <div class="seccion-registros">
            <h5 class="section-title text-center w-100 bg-soft-success text-dark">
                CARGUIOS
                <span class="badge bg-primary badge-count"> {{ $vehiculo->cargios->count() }}</span>
            </h5>

            <div class="table-responsive">
                <table class="tabla-carguios  tabla-info table table-sm table-striped align-middle">
                    <tr>
                        <th class="table-success">NIT CONSUMIDOR</th>
                        <th class="table-success">RAZÓN SOCIAL</th>
                        <th class="table-success">ESTACION</th>
                        <th class="table-success">DEPARTAMENTO</th>
                        <th class="table-success">MONTO BS</th>
                        <th class="table-success">LITROS</th>
                        <th class="table-success">FECHA</th>
                    </tr>
                    <tbody style="max-height: 300px; overflow-y: auto; display: block;">

                        @foreach ($vehiculo->cargios as $cargio)
                            <tr class="{{ $loop->index == 0 ? 'enfasis' : '' }}">
                                <td>{{ $cargio->nit_consumidor }}</td>
                                <td>{{ $cargio->razon_social }}</td>
                                <td>{{ $cargio->estacionServicio->eess }}</td>
                                <td>{{ $cargio->departamento }}</td>
                                <td>{{ $cargio->monto }}</td>
                                <td>{{ $cargio->cantidad }}</td>
                                <td>{{ $cargio->fecha_venta?->format('d/m/Y H:i') }}
                                    @if ($loop->index == 0)
                                        <span class="badge bg-warning text-dark ms-2">Último Carguio</span>
                                        <br>
                                        <span class="badge bg-info text-dark mt-1">
                                            {{ $cargio->fecha_venta?->diffForHumans() }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección ITV -->
        <div class="seccion-itv">
            <h5 class="section-title text-center w-100 bg-soft-success text-dark">
                ITV
                <span class="badge bg-primary badge-count"> {{ $vehiculo->inspecciones->count() }}</span>
            </h5>
            <div class="table-responsive">
                <table class="tabla-itv table table-sm table-striped align-middle">
                    <tr>
                        <th class="table-success">AÑO</th>
                        <th class="table-success">NOMBRE</th>
                        <th class="table-success">C.I.</th>
                        <th class="table-success">TELÉFONO</th>
                        <th class="table-success">DETALLE</th>
                    </tr>
                    <tbody>

                        @forelse ($vehiculo->inspecciones as $inspeccion)
                            <tr class="{{ $loop->index == 0 ? 'enfasis' : '' }}">
                                <td>{{ $inspeccion->anio }}</td>
                                <td>{{ $inspeccion->persona?->nombres }} {{ $inspeccion->persona?->apellidos }}</td>
                                <td>{{ $inspeccion->persona?->ci }}</td>
                                <td>{{ $inspeccion->persona?->telefono }}</td>
                                <td>{{ $inspeccion->resultado }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <div class="text-center p-3">
                                        No hay inspecciones técnicas vinculadas a este vehículo.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección Último Carguio ANH -->
        <div class="seccion-ultimo-carguio d-none">
            <h3>ÚLTIMO CARGUIO ANH</h3>
            <table class="tabla-ultimo-carguio">
                <thead>
                    <tr>
                        <th>EE.SS</th>
                        <th>DPTO</th>
                        <th>NIT</th>
                        <th>RAZÓN SOCIAL</th>
                        <th>CANTIDAD DE LITROS</th>
                        <th>COSTO</th>
                        <th>FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ESTACION DE SERVICIO LA CIMA</td>
                        <td>SANTA CRUZ</td>
                        <td>6288312</td>
                        <td>NATALIA MILLIET</td>
                        <td>76,21</td>
                        <td>285,03</td>
                        <td>7/29/2023 9:17:00 PM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="ri-close-line align-middle me-1"></i> Cerrar
    </button>
</div>
