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
                            <td><a href="{{ route('registro-criminal.show', $registro) }}"
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
                            <td><a href="#" class="btn btn-sm btn-primary">Ver
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
</div>
