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
                                <h6>Fotos del imputado</h6>
                                <div class="">
                                    @forelse ($mandamiento->persona?->multimedia as $multimedia)
                                        <div class="swiper-slide mb-1">
                                            <img src="{{ asset('/storage/' . $multimedia->ruta) }}" alt=""
                                                class="img-fluid d-block" />
                                        </div>

                                    @empty
                                        <div class="swiper-slide">
                                            <img src="{{ asset('/assets/img/user-dummy-img.jpg') }}" alt=""
                                                class="img-fluid d-block" />
                                        </div>
                                    @endforelse

                                </div>
                                <hr>
                                <h6>Imagen del Mandamiento</h6>
                                <div class="">

                                    @if ($mandamiento->ruta)
                                        <div class="swiper-slide mb-1">
                                            <img src="{{ asset('/storage/' . $mandamiento->ruta) }}" alt=""
                                                class="img-fluid d-block" />
                                        </div>
                                    @else
                                        <div class="swiper-slide py-4">
                                            <div class="text-center text-muted">No hay imagen del mandamiento</div>
                                        </div>
                                    @endif

                                </div>


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
                                    <h2> Nro HR: {{ $mandamiento->hoja_ruta }}</h2>
                                    <h4>{{ $mandamiento->persona?->nombre_completo }}</h4>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div><a href="#" class="text-primary d-block"><span
                                                    class="text-muted fw-medium">C.I.: </span>{{ $mandamiento->persona?->ci }}</a>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-muted">TIPO : <span
                                                class="text-body fw-medium">{{ $mandamiento->tipo_documento }}</span>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-muted">ESTADO:
                                            <span
                                                class=" badge text-bg-{{ $estados[$mandamiento->estado] ?? 'secondary' }}">
                                                {{ $mandamiento->estado }}</span></span>
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
                                        class="text-body fw-medium">{{ $mandamiento->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center mt-3">
                                <div class="text-muted">Delito : <span
                                        class="text-body fw-medium">{{ $mandamiento->nombre_delito }}</span>
                                </div>
                            </div>


                            {{--
                            <div class="row mt-4">
                                <div class="col-lg-3 col-sm-6">
                                    <div class="p-2 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-money-dollar-circle-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-1">Price :</p>
                                                <h5 class="mb-0">$120.40</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-lg-3 col-sm-6">
                                    <div class="p-2 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-file-copy-2-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-1">No. of Orders :</p>
                                                <h5 class="mb-0">2,234</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-lg-3 col-sm-6">
                                    <div class="p-2 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-stack-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-1">Available Stocks :</p>
                                                <h5 class="mb-0">1,230</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-lg-3 col-sm-6">
                                    <div class="p-2 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-inbox-archive-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-1">Total Revenue :</p>
                                                <h5 class="mb-0">$60,645</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div> --}}

                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mt-4">
                                        <h5 class="fs-14">Tipo Mandamiento :</h5>
                                        <div class="d-flex flex-wrap gap-2">
                                            <div class="text-muted"> <span
                                                    class="">{{ $mandamiento->tipo_mandamiento }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-xl-6">
                                    <div class=" mt-4">
                                        <h5 class="fs-14">Juzgado :</h5>
                                        <div class="d-flex flex-wrap gap-2">
                                            <div class="text-muted"> <span
                                                    class="">{{ $mandamiento->nombre_juzgado }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
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

                            <div class="row">
                                <div class="col-12">
                                    <div class="mt-3">
                                        <h5 class="fs-14">
                                            <i class="ri-user-line me-1 align-middle"></i>
                                            Datos del imputado :</h5>
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>

                                                    <tr>
                                                        <th scope="row">Nro C.I.</th>
                                                        <td>{{ $mandamiento->ci }}</td>
                                                    </tr>
                                                    <tr>

                                                        <th scope="row" style="width: 200px;">
                                                            Nombre</th>
                                                        <td>{{ $mandamiento->persona->nombre }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Apellidos</th>
                                                        <td>{{ $mandamiento->persona->paterno }}
                                                            {{ $mandamiento->persona->materno }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Nro Celular</th>
                                                        <td>{{ $mandamiento->persona->telefono }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Edad</th>
                                                        <td>{{ $mandamiento->persona?->fecha_nacimiento?->age }} años
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Domicilio</th>
                                                        <td>{{ $mandamiento->persona?->domicilio }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Vehiculos</th>
                                                        <td>{{ $mandamiento->persona?->vehiculos }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>


                            <div class="product-content mt-5">
                                <h5 class="fs-14 mb-3">
                                    <i class="ri-file-list-line me-1 align-middle"></i>
                                    Detalles :
                                </h5>
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 200px;">
                                                Nro Hoja de Ruta / Memorandum
                                                </th>
                                                <td>{{ $mandamiento->hoja_ruta }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Estado</th>
                                                <td>{{ $mandamiento->estado }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Fecha Ejecución</th>
                                                <td>{{ $mandamiento->fecha_ejecucion?->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Actividades Realizadas</th>
                                                <td>{{ $mandamiento->actividades_realizadas }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Asignado a</th>
                                                <td>{{ $mandamiento->asignado }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <nav class="d-none">
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab"
                                                href="#nav-speci" role="tab" aria-controls="nav-speci"
                                                aria-selected="true">Specification</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="nav-detail-tab" data-bs-toggle="tab"
                                                href="#nav-detail" role="tab" aria-controls="nav-detail"
                                                aria-selected="false">Details</a>
                                        </li>
                                    </ul>
                                </nav>
                                <div class="tab-content border border-top-0 p-4 d-none" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-speci" role="tabpanel"
                                        aria-labelledby="nav-speci-tab">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row" style="width: 200px;">
                                                            Category</th>
                                                        <td>T-Shirt</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Brand</th>
                                                        <td>Tommy Hilfiger</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Color</th>
                                                        <td>Blue</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Material</th>
                                                        <td>Cotton</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Weight</th>
                                                        <td>140 Gram</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-detail" role="tabpanel"
                                        aria-labelledby="nav-detail-tab">
                                        <div>
                                            <h5 class="font-size-16 mb-3">Tommy Hilfiger Sweatshirt
                                                for
                                                Men (Pink)</h5>
                                            <p>Tommy Hilfiger men striped pink sweatshirt. Crafted
                                                with
                                                cotton. Material composition is 100% organic cotton.
                                                This is one of the world’s leading designer
                                                lifestyle
                                                brands and is internationally recognized for
                                                celebrating
                                                the essence of classic American cool style,
                                                featuring
                                                preppy with a twist designs.</p>
                                            <div>
                                                <p class="mb-2"><i
                                                        class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                    Machine Wash</p>
                                                <p class="mb-2"><i
                                                        class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                    Fit Type: Regular</p>
                                                <p class="mb-2"><i
                                                        class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                    100% Cotton</p>
                                                <p class="mb-0"><i
                                                        class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                    Long sleeve</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                                                <div class="progress-bar bg-success"
                                                                    role="progressbar" style="width: 50.16%"
                                                                    aria-valuenow="50.16" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
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
