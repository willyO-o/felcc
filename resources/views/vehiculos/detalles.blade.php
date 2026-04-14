<style>
    .detalles-vehiculo {
        --color-primary: #7cb342;
        --color-header: #9ccc65;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        font-size: 0.9375rem;
    }

    .detalles-vehiculo .header-placa {
        background: linear-gradient(135deg, #9ccc65 0%, #7cb342 100%);
        padding: 15px 20px;
        text-align: center;
        margin: -15px -15px 20px -15px;
    }

    .detalles-vehiculo .header-placa h1 {
        font-size: 1.75rem;
        color: white;
        margin: 0;
        font-weight: 600;
        letter-spacing: 2px;
    }

    .detalles-vehiculo .seccion-principal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .detalles-vehiculo .foto-item {
        /* width: 100%; */
        min-height: 200px;
        background-color: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 0.85rem;
    }

    .detalles-vehiculo .foto-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 2px;
    }

    .detalles-vehiculo .tabla-info {
        width: 100%;
        border-collapse: collapse;
    }

    .detalles-vehiculo .tabla-info th {
        background-color: #9ccc65;
        color: white;
        padding: 10px;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .detalles-vehiculo .tabla-info td {
        padding: 8px 10px;
        border: 1px solid #e9ecef;
        background-color: #fafbfc;
        font-size: 0.9rem;
    }

    .detalles-vehiculo .tabla-info tr:nth-child(even) td {
        background-color: #fff;
    }

    .detalles-vehiculo .seccion-registros {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .detalles-vehiculo .seccion-registros>div {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .detalles-vehiculo .seccion-registros h3 {
        background-color: #9ccc65;
        color: white;
        padding: 8px 12px;
        margin: 0 0 12px 0;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 3px;
    }

    .detalles-vehiculo .relacion-contenido {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detalles-vehiculo .relacion-item {
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-left: 3px solid #9ccc65;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .detalles-vehiculo .seccion-relacion {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .detalles-vehiculo .relacion-columna h3 {
        background-color: #9ccc65;
        color: white;
        padding: 8px 12px;
        margin: 0 0 12px 0;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 3px;
    }

    .detalles-vehiculo .tabla-carguios,
    .detalles-vehiculo .tabla-itv,
    .detalles-vehiculo .tabla-ultimo-carguio {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.65rem;
    }

    .detalles-vehiculo .tabla-carguios th,
    .detalles-vehiculo .tabla-itv th,
    .detalles-vehiculo .tabla-ultimo-carguio th {
        background-color: #9ccc65;
        color: white;
        padding: 8px 10px;
        text-align: left;
        font-weight: 600;
    }

    .detalles-vehiculo .tabla-carguios td,
    .detalles-vehiculo .tabla-itv td,
    .detalles-vehiculo .tabla-ultimo-carguio td {
        padding: 8px 10px;
        border: 1px solid #e9ecef;
        background-color: #fafbfc;
    }

    .detalles-vehiculo .tabla-carguios tr:nth-child(even) td,
    .detalles-vehiculo .tabla-itv tr:nth-child(even) td,
    .detalles-vehiculo .tabla-ultimo-carguio tr:nth-child(even) td {
        background-color: #fff;
    }

    .detalles-vehiculo .seccion-itv,
    .detalles-vehiculo .seccion-ultimo-carguio {
        margin-bottom: 20px;
    }

    .detalles-vehiculo .seccion-itv h3,
    .detalles-vehiculo .seccion-ultimo-carguio h3 {
        background-color: #9ccc65;
        color: white;
        padding: 8px 12px;
        margin: 0 0 12px 0;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 3px;
    }

    @media (max-width: 768px) {
        .detalles-vehiculo .seccion-principal {
            grid-template-columns: 1fr;
            gap: 15px;
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
            font-size: 0.8rem;
        }

        .detalles-vehiculo .tabla-carguios th,
        .detalles-vehiculo .tabla-itv th,
        .detalles-vehiculo .tabla-ultimo-carguio th,
        .detalles-vehiculo .tabla-carguios td,
        .detalles-vehiculo .tabla-itv td,
        .detalles-vehiculo .tabla-ultimo-carguio td {
            padding: 6px 8px;
        }
    }

    .seccion-registros thead,
    .seccion-registros tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
        /* Importante para que las celdas alineen */
    }

    .seccion-registros .enfasis td {
        background-color: #e5db91 !important;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">Detalles del Vehículo</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="detalles-vehiculo">
        <!-- Encabezado con Placa -->
        <div class="header-placa">
            <h1> {{ $vehiculo->placa }}</h1>
        </div>

        <!-- Sección Principal: Fotos e Información -->
        <div class="seccion-principal">
            <!-- Fotos del vehículo -->
            <div class="fotos-vehiculo row g-3">
                @forelse ($vehiculo->multimedia as $foto)
                    <div class="foto-item col-md-6 ">
                        <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto del vehículo">
                    </div>

                @empty
                    <div class="foto-item col-md-6 ">
                        <div class="foto-placeholder">Foto frontal</div>
                    </div>
                    <div class="foto-item col-md-6 ">
                        <div class="foto-placeholder">Foto lateral</div>
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


            <table class="tabla-info ">
                <tbody>
                    <tr>
                        <th class="text-center">
                            Tipo de Registro
                        </th>
                        <th class="text-center">
                            Nombre
                        </th>
                        <th class="text-center">
                            C.I
                        </th>
                        <th class="text-center">
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
                <h3>Caso Relacionado</h3>
                <div class="relacion-contenido">
                    <div class="relacion-item">
                        <strong></strong> {{ $vehiculo->caso_relacionado ?? '-' }}
                    </div>

                </div>
            </div>

            <div class="relacion-columna">
                <h3>Responsable</h3>
                <div class="relacion-contenido">
                    <div class="relacion-item"> {{ $vehiculo->responsable ?? '-' }} </div>
                </div>
            </div>
        </div>

        <!-- Sección CARGUIOS -->
        <div class="seccion-registros">
            <h3 class="text-center">
                CARGUIOS
                <span class="badge bg-primary"> {{ $vehiculo->cargios->count() }}</span>
            </h3>


            <table class="tabla-carguios table-sm">
                <thead>
                    <tr>
                        <th>NIT CONSUMIDOR</th>
                        <th>RAZÓN SOCIAL</th>
                        <th>ESTACION</th>
                        <th>DEPARTAMENTO</th>
                        <th>MONTO BS</th>
                        <th>LITROS</th>
                        <th>FECHA</th>
                    </tr>
                </thead>
                <tbody class="" style="max-height: 300px; overflow-y: auto; display: block;">

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
                                    {{-- calcular tiempo transcurrido desde el último carguio --}}

                                    <span class="badge bg-warning text-dark">Último Carguio</span>
                                    <br>
                                    <span class="badge bg-soft-primary text-dark">
                                        {{ $cargio->fecha_venta?->diffForHumans() }}
                                    </span>
                                @endif

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <!-- Sección ITV -->
        <div class="seccion-itv ">
            <h3 class="text-center">ITV <span class="badge bg-primary"> {{ $vehiculo->inspecciones->count() }}</span>
            </h3>
            <table class="tabla-itv">
                <thead>
                    <tr>
                        <th>AÑO</th>
                        <th>NOMBRE</th>
                        <th>C.I.</th>
                        <th>TELÉFONO</th>
                        <th>DETALLE</th>
                    </tr>
                </thead>
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
