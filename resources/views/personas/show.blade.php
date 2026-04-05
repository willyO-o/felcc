<div class="modal-header border-bottom">
    <h5 class="modal-title" id="modalPersonaLabel">
        Detalles de la Persona
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row gx-lg-5">
                    <div class="col-xl-4 col-md-8 mx-auto">
                        <div class="product-img-slider sticky-side-div">
                            <div class=" p-2 rounded bg-light">
                                <h6>Fotos de la Persona</h6>

                                @forelse ($datos->multimedia as  $foto)
                                    <img src="{{ asset('/storage/' . $foto->ruta) }}" alt="foto frente"
                                        class="img-fluid d-block" />

                                @empty
                                    <div class="alert alert-warning text-center">
                                        No se encontraron fotos disponibles.
                                    </div>
                                @endforelse




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
                                    <h2> {{ $datos->nombre }} {{ $datos->apellidos }} </h2>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div><a href="#" class="text-primary d-block"><span
                                                    class="text-muted fw-medium">C.I.:
                                                </span>{{ $datos->ci }}
                                                @if ($datos->complemento)
                                                    ({{ $datos->complemento }})
                                                @endif
                                            </a>
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
                                        class="text-body fw-medium">{{ $datos->created_at?->format('d/m/Y') }}</span>
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


                            @include('personas.partials._datos', ['persona' => $datos])
                            <div class="mt-4 text-muted ">
                                <h5 class="fs-14">Datos Segip:</h5>
                                <p>
                                    {{ $datos->datos_segip }}
                                </p>
                            </div>
                            <hr>
                            <div class="mt-4 text-muted ">
                                <h5 class="fs-14">Url documento:</h5>
                                <p>
                                    @if ($datos->url_documento)
                                        <a href="{{ $datos->url_documento }}" target="_blank">
                                            {{ $datos->url_documento }}
                                        </a>
                                    @else
                                        No agregado
                                    @endif
                                </p>
                            </div>




                            <div class="product-content mt-5">
                                <h5 class="fs-14 mb-3">
                                    <i class="ri-file-list-line me-1 align-middle"></i>
                                    Información Adicional:
                                </h5>

                                @php
                                    $otrosRegistros = $datos->registroCriminal;
                                    $mandamientos = $datos->mandamientos;
                                @endphp


                                @include('personas.partials._tab-adicional', [
                                    'otrosRegistros' => $otrosRegistros,
                                    'mandamientos' => $mandamientos,
                                ])
                            </div>

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
<div class="modal-footer border-top">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
</div>
