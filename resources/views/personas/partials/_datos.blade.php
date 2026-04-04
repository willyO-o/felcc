<div class="row">
    <div class="col-12">
        <div class="mt-3">
            <h5 class="fs-14">
                <i class="ri-user-line me-1 align-middle"></i>
                Datos personales :
            </h5>
            <div class="table-responsive">
                <table class="table mb-0 small table-sm">
                    <tbody>

                        <tr>
                            <th scope="row">Nro C.I.</th>
                            <td>{{ $persona->ci }}
                                @if ($persona->complemento)
                                    -<span class="text-muted">({{ $persona->complemento }})</span>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <th scope="row" style="width: 200px;">
                                Nombre</th>
                            <td>{{ $persona->nombres }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Apellidos</th>
                            <td>{{ $persona->apellidos }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Alias</th>
                            <td>{{ $persona->alias }}</td>
                        </tr>
                        </tr>

                        <tr>
                            <th scope="row">Nro Celular</th>
                            <td>{{ $persona->telefono }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Ocupación</th>
                            <td>{{ $persona->ocupacion }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nacimiento / edad</th>
                            <td> {{ $persona->fecha_nacimiento?->format('d-m-Y') }} /
                                {{ $persona->fecha_nacimiento?->age }} años
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Genero</th>
                            <td>{{ $persona->genero }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Grupo sanguíneo</th>
                            <td>{{ $persona->grupo_sanguineo }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nacionalidad</th>
                            <td>{{ $persona->pais?->gentilicio }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Estado civil</th>
                            <td>{{ $persona->estado_civil }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nombre del cónyuge</th>
                            <td>{{ $persona->nombre_conyuge }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nombre del padre</th>
                            <td>{{ $persona->padre }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nombre de la madre</th>
                            <td>{{ $persona->madre }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Domicilio</th>
                            <td>{{ $persona->domicilio }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
