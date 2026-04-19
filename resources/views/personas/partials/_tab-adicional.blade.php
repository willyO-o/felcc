<nav class="">
    <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab" href="#nav-speci" role="tab"
                aria-controls="nav-speci" aria-selected="true">
                Otros Registros
                <span class="badge bg-danger">{{ $otrosRegistros?->count() ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-detail-tab" data-bs-toggle="tab" href="#nav-detail" role="tab"
                aria-controls="nav-detail" aria-selected="false">
                Mandamientos
                <span class="badge bg-danger">{{ $mandamientos?->count() ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-vehiculos-tab" data-bs-toggle="tab" href="#nav-vehiculos" role="tab"
                aria-controls="nav-vehiculos" aria-selected="false">
                Vehiculos
                <span class="badge bg-danger">{{ $vehiculos?->count() ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="nav-telefonos-tab" data-bs-toggle="tab" href="#nav-telefonos" role="tab"
                aria-controls="nav-telefonos" aria-selected="false">
                Telefonos
                <span class="badge bg-danger">{{ $telefonos?->count() ?? 0 }}</span>
            </a>
        </li>
    </ul>
</nav>
<div class="tab-content border border-top-0 p-4 " id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-speci" role="tabpanel" aria-labelledby="nav-speci-tab">
        <div class="table-responsive">
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th scope="row" style="">
                            Nro
                        </th>
                        <th scope="row" style="">
                            Fecha
                        </th>
                        <th scope="row" style="">
                            Edad Aproximada
                        </th>
                        <th scope="row" style="">
                            Especialidad
                        </th>
                        <td></td>
                    </tr>
                    @forelse ($otrosRegistros as $registro)
                        <tr>
                            <th scope="row">{{ $registro->nro_registro }}</th>
                            <td>{{ $registro->fecha_registro?->format('d/m/Y') }}</td>
                            <td>{{ $registro->edad_aproximada }}</td>
                            <td>{{ $registro->especialidad }}</td>
                            <td>
                                <a  {{ $isAjax ? 'target="_blank"' : '' }}
                                href="{{ route('registro-criminal.showByCodigo', ['codigo' => md5($registro->id), 'identificador' => $identificador ?? null]) }}"
                                    class="btn btn-sm btn-primary">Ver Detalles</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center py-4">No se
                                encontraron mas registros.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab">
        <div>

            <table class="table mb-0 small table-sm">
                <tbody>
                    <tr>
                        <th scope="row" style="">
                            Nro
                        </th>
                        <th scope="row" style="">
                            Delito
                        </th>
                        <th scope="row" style="">
                            Tipo
                        </th>
                        <th scope="row" style="">
                            Estado
                        </th>
                        <td></td>
                    </tr>
                    @forelse ($mandamientos as $man)
                        <tr>
                            <th scope="row">{{ $man->hoja_ruta }}</th>
                            <td>{{ $man->delito?->nombre_delito }}</td>
                            <td>{{ $man->tipoMandamiento?->tipo_mandamiento }}</td>
                            <td>
                                {{ $man->estado }}
                            </td>
                            <td><a {{ $isAjax ? 'target="_blank"' : '' }}
                                    href="{{ route('mandamientos.showByCodigo', ['codigo' => md5($man->id), 'identificador' => $identificador ?? null]) }}"
                                 class="btn btn-sm btn-primary">Ver
                                    Detalles</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center py-4">No se
                                encontraron mandamientos.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-vehiculos" role="tabpanel" aria-labelledby="nav-vehiculos-tab">
        <div>

            <table class="table mb-0 small table-sm">
                <tbody>
                    <tr>
                        <th scope="row" style="">
                            Nro de Placa
                        </th>
                        <th scope="row" style="">
                            Responsable
                        </th>
                        <th scope="row" style="">
                            Caso Relacionado
                        </th>
                        <th scope="row" style="">
                            Descripción
                        </th>
                    </tr>
                    @forelse ($vehiculos as $vehiculo)
                        <tr>
                            <th scope="row">{{ $vehiculo->placa }}</th>
                            <td>{{ $vehiculo->responsable }}</td>
                            <td>{{ $vehiculo->caso_relacionado }}</td>
                            <td>
                                {{ $vehiculo->descripcion }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center py-4">No se
                                encontraron vehículos.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-telefonos" role="tabpanel" aria-labelledby="nav-telefonos-tab">
        <div>

            <table class="table mb-0 small table-sm">
                <tbody>
                    <tr>
                        <th scope="row" style="">
                            Nro de Celular
                        </th>
                        <th scope="row" style="">
                            Persona Caso
                        </th>
                        <th scope="row" style="">
                            Caso
                        </th>
                        <th scope="row" style="">
                            Empresa
                        </th>
                    </tr>
                    @forelse ($telefonos as $telefono)
                        <tr>
                            <th scope="row">{{ $telefono->numero_celular }}</th>
                            <td>{{ $telefono->persona_caso }}</td>
                            <td>{{ $telefono->caso }}</td>
                            <td>{{ $telefono->empresa }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center py-4">No se
                                encontraron teléfonos.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
