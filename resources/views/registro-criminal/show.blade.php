@php
    $estados = [
        'PENDIENTE' => 'info',
        'EJECUTADO' => 'success',
        'CANCELADO' => 'danger',
    ];
@endphp

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row gx-lg-5">
                    <div class="col-xl-4 col-md-8 mx-auto">
                        <div class="product-img-slider sticky-side-div">
                            <div class=" p-2 rounded bg-light">
                                <h6>Fotos de frente</h6>

                                <img src="{{ asset('/storage/' . $datos->getFotoFrenteAttribute()) }}" alt="foto frente"
                                    class="img-fluid d-block" />
                                <h6>Fotos de perfil</h6>

                                <img src="{{ asset('/storage/' . $datos->getFotoPerfilAttribute()) }}" alt="foto perfil"
                                    class="img-fluid d-block mt-2" />

                                <hr>



                            </div>
                            <!-- end swiper thumbnail slide -->

                            <!-- end swiper nav slide -->
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-xl-8">
                        <div class="mt-xl-0 mt-5">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h2> Nro registro: {{ $datos->nro_registro }}</h2>
                                    <h4>{{ $datos->persona?->nombre_completo }}</h4>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div><a href="#" class="text-primary d-block"><span
                                                    class="text-muted fw-medium">C.I.:
                                                </span>{{ $datos->persona?->ci }}
                                                @if ($datos->persona?->complemento)
                                                    ({{ $datos->persona?->complemento }})
                                                @endif
                                            </a>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-muted">ESPECIALIDAD : <span
                                                class="text-primary fw-medium">{{ $datos->especialidad }}</span>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-muted">REGISTRADO POR:
                                            {{ $datos->usuario->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div>

                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center mt-3">
                                <div class="text-muted">Fecha Registro : <span
                                        class="text-body fw-medium">{{ $datos->fecha_registro?->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <!-- end row -->

                            <div class="mt-4 text-muted d-none">
                                <h5 class="fs-14">Description :</h5>
                                <p>Tommy Hilfiger men striped pink sweatshirt. Crafted with cotton.
                                    Material composition is 100% organic cotton. This is one of the
                                    world’s leading designer lifestyle brands and is internationally
                                    recognized for celebrating the essence of classic American cool
                                    style, featuring preppy with a twist designs.</p>
                            </div>


                            @include('personas.partials._datos', ['persona' => $datos->persona])

                            <div class="product-content mt-5">
                                <h5 class="fs-14 mb-3">
                                    <i class="ri-file-list-line me-1 align-middle"></i>
                                    Detalles del Registro:
                                </h5>
                                <div class="table-responsive">
                                    <table class="table mb-0 table-sm small">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 200px;">
                                                    Nro Registro
                                                </th>
                                                <td>{{ $datos->nro_registro }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Nombre Supuesto</th>
                                                <td>{{ $datos->nombre_supuesto }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Teléfono</th>
                                                <td>{{ $datos->telefono }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Edad Aproximada</th>
                                                <td>{{ $datos->edad_aproximada }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Alias</th>
                                                <td>{{ $datos->Alias }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Especialidad</th>
                                                <td>{{ $datos->especialidad }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Modus Operandi</th>
                                                <td>{{ $datos->modus_operandi }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Zonas de Operación</th>
                                                <td>{{ $datos->zonas_opera }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">División</th>
                                                <td>{{ $datos->division?->division }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Observaciones</th>
                                                <td>{{ $datos->observaciones }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Rasgos Distintivos</th>
                                                <td>{{ $datos->rasgos }}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">Estatura</th>
                                                <td>{{ $datos->estatura }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Peso</th>
                                                <td>{{ $datos->peso }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">CUD</th>
                                                <td>{{ $datos->cud }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Características Particulares</th>
                                                <td>{{ $datos->caracteristicas_particulares }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Nombre del Cónyuge</th>
                                                <td>{{ $datos->nombre_conyuge }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Hijos</th>
                                                <td>{{ $datos->hijos }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Domicilio</th>
                                                <td>{{ $datos->domicilio }}</td>
                                            </tr>


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="product-content mt-5">
                                <h5 class="fs-14 mb-3">
                                    <i class="ri-file-list-line me-1 align-middle"></i>
                                    Información Adicional:
                                </h5>

                                @php
                                    $otrosRegistros = $datos->otrosRegistrosCriminales($datos->id);
                                    $mandamientos = $datos->persona?->mandamientos;
                                @endphp


                                @include('personas.partials._tab-adicional', [
                                    'otrosRegistros' => $otrosRegistros,
                                    'mandamientos' => $mandamientos,
                                ])
                            </div>
                            <!-- product-content -->

                            <div class="mt-5 d-none">
                                <div>
                                    <h5 class="fs-14 mb-3">Ratings & Reviews</h5>
                                </div>
                                <div class="row gy-4 gx-0">
                                    <div class="col-lg-4">
                                        <div>
                                            <div class="pb-3">
                                                <div class="bg-light px-3 py-2 rounded-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <div class="fs-16 align-middle text-warning">
                                                                <i class="ri-star-fill"></i>
                                                                <i class="ri-star-fill"></i>
                                                                <i class="ri-star-fill"></i>
                                                                <i class="ri-star-fill"></i>
                                                                <i class="ri-star-half-fill"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h6 class="mb-0">4.5 out of 5</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-muted">Total <span class="fw-medium">5.50k</span>
                                                        reviews
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0">5 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-2">
                                                            <div
                                                                class="progress bg-soft-success animated-progress progress-sm">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: 50.16%" aria-valuenow="50.16"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0 text-muted">2758</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->

                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0">4 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-2">
                                                            <div
                                                                class="progress bg-soft-success animated-progress progress-sm">
                                                                <div class="progress-bar bg-success"
                                                                    role="progressbar" style="width: 19.32%"
                                                                    aria-valuenow="19.32" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0 text-muted">1063</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->

                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0">3 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-2">
                                                            <div
                                                                class="progress bg-soft-success animated-progress progress-sm">
                                                                <div class="progress-bar bg-success"
                                                                    role="progressbar" style="width: 18.12%"
                                                                    aria-valuenow="18.12" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0 text-muted">997</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->

                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0">2 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-2">
                                                            <div
                                                                class="progress bg-soft-warning animated-progress progress-sm">
                                                                <div class="progress-bar bg-warning"
                                                                    role="progressbar" style="width: 7.42%"
                                                                    aria-valuenow="7.42" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0 text-muted">408</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->

                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0">1 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-2">
                                                            <div
                                                                class="progress bg-soft-danger animated-progress progress-sm">
                                                                <div class="progress-bar bg-danger" role="progressbar"
                                                                    style="width: 4.98%" aria-valuenow="4.98"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="p-2">
                                                            <h6 class="mb-0 text-muted">274</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->

                                    <div class="col-lg-8">
                                        <div class="ps-lg-4">
                                            <div class="d-flex flex-wrap align-items-start gap-3">
                                                <h5 class="fs-14">Reviews: </h5>
                                            </div>

                                            <div class="me-lg-n3 pe-lg-4" data-simplebar style="max-height: 225px;">
                                                <ul class="list-unstyled mb-0">
                                                    <li class="py-2">
                                                        <div class="border border-dashed rounded p-3">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <div class="hstack gap-3">
                                                                    <div class="badge rounded-pill bg-success mb-0">
                                                                        <i class="mdi mdi-star"></i>
                                                                        4.2
                                                                    </div>
                                                                    <div class="vr"></div>
                                                                    <div class="flex-grow-1">
                                                                        <p class="text-muted mb-0">
                                                                            Superb sweatshirt. I
                                                                            loved
                                                                            it. It is for winter.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex flex-grow-1 gap-2 mb-3">
                                                                <a href="#" class="d-block">
                                                                    <img src="/assets/images/small/img-12.jpg"
                                                                        alt=""
                                                                        class="avatar-sm shadow rounded object-cover">
                                                                </a>
                                                                <a href="#" class="d-block">
                                                                    <img src="/assets/images/small/img-11.jpg"
                                                                        alt=""
                                                                        class="avatar-sm shadow rounded object-cover">
                                                                </a>
                                                                <a href="#" class="d-block">
                                                                    <img src="/assets/images/small/img-10.jpg"
                                                                        alt=""
                                                                        class="avatar-sm shadow rounded object-cover">
                                                                </a>
                                                            </div>

                                                            <div class="d-flex align-items-end">
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-14 mb-0">Henry
                                                                    </h5>
                                                                </div>

                                                                <div class="flex-shrink-0">
                                                                    <p class="text-muted fs-13 mb-0">
                                                                        12
                                                                        Jul, 21</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="py-2">
                                                        <div class="border border-dashed rounded p-3">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <div class="hstack gap-3">
                                                                    <div class="badge rounded-pill bg-success mb-0">
                                                                        <i class="mdi mdi-star"></i>
                                                                        4.0
                                                                    </div>
                                                                    <div class="vr"></div>
                                                                    <div class="flex-grow-1">
                                                                        <p class="text-muted mb-0">
                                                                            Great at this price,
                                                                            Product
                                                                            quality and look is
                                                                            awesome.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-end">
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-14 mb-0">Nancy
                                                                    </h5>
                                                                </div>

                                                                <div class="flex-shrink-0">
                                                                    <p class="text-muted fs-13 mb-0">
                                                                        06
                                                                        Jul, 21</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="py-2">
                                                        <div class="border border-dashed rounded p-3">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <div class="hstack gap-3">
                                                                    <div class="badge rounded-pill bg-success mb-0">
                                                                        <i class="mdi mdi-star"></i>
                                                                        4.2
                                                                    </div>
                                                                    <div class="vr"></div>
                                                                    <div class="flex-grow-1">
                                                                        <p class="text-muted mb-0">
                                                                            Good
                                                                            product. I am so happy.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-end">
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-14 mb-0">Joseph
                                                                    </h5>
                                                                </div>

                                                                <div class="flex-shrink-0">
                                                                    <p class="text-muted fs-13 mb-0">
                                                                        06
                                                                        Jul, 21</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="py-2">
                                                        <div class="border border-dashed rounded p-3">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <div class="hstack gap-3">
                                                                    <div class="badge rounded-pill bg-success mb-0">
                                                                        <i class="mdi mdi-star"></i>
                                                                        4.1
                                                                    </div>
                                                                    <div class="vr"></div>
                                                                    <div class="flex-grow-1">
                                                                        <p class="text-muted mb-0">
                                                                            Nice
                                                                            Product, Good Quality.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-end">
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-14 mb-0">Jimmy
                                                                    </h5>
                                                                </div>

                                                                <div class="flex-shrink-0">
                                                                    <p class="text-muted fs-13 mb-0">
                                                                        24
                                                                        Jun, 21</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end Ratings & Reviews -->
                            </div>
                            <!-- end card body -->
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
