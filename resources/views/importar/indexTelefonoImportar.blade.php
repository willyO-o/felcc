@extends('layouts.app')

@section('page-title', 'Importar Telefonos desde CSV')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('telefonos.index') }}">Telefonos</a></li>
            <li class="breadcrumb-item active">Importar</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#telefonosTab" role="tab">
                                <i class="ri-phone-line me-2"></i>Importar Teléfonos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#imeisTab" role="tab">
                                <i class="ri-smartphone-line me-2"></i>Importar IMEIs
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    {{-- TAB TELEFONOS --}}
                    <div class="tab-pane p-3 fade show active" id="telefonosTab" role="tabpanel">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- Área de carga --}}
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <div class="border-2 border-dashed rounded-3 p-5 text-center"
                                                    id="dropZone_tel"
                                                    style="border-color: #dee2e6; cursor: pointer; transition: all 0.3s;">
                                                    <div id="uploadIcon_tel">
                                                        <div class="mb-3">
                                                            <i class="ri-phone-line"
                                                                style="font-size: 4rem; color: #0ab39c;"></i>
                                                        </div>
                                                        <p class="text-muted mt-3 mb-1"><strong>Importar Teléfonos</strong>
                                                        </p>
                                                        <p class="text-muted mb-1">Arrastra tu archivo aquí o haz clic para
                                                            seleccionar</p>
                                                        <small class="text-muted">Formatos: CSV, XLSX, XLS (máx.
                                                            10MB)</small>
                                                    </div>
                                                    <input type="file" id="archivoInput_tel" accept=".csv,.xlsx,.xls"
                                                        style="display: none;">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info archivo --}}
                                        <div id="infoArchivo_tel" style="display: none;">
                                            <div class="alert alert-info">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong id="nombreArchivo_tel"></strong>
                                                        <small id="tamañoArchivo_tel" class="d-block text-muted"></small>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        onclick="limpiarArchivo('_tel')">Cambiar</button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Botones de acción --}}
                                        <div class="row gap-2">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary w-100" id="btnImportar_tel"
                                                    style="display: none;" onclick="importarArchivo('_tel', 'telefonos')">
                                                    <i class="ri-download-line me-1"></i> Importar Teléfonos
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Barra de progreso --}}
                                        <div id="progressContainer_tel" style="display: none;" class="mt-3">
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                    id="progressBar_tel" role="progressbar" style="width: 0%"
                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    <span id="progressText_tel">0%</span>
                                                </div>
                                            </div>
                                            <p class="text-muted small mt-2" id="progressInfo_tel">Procesando...</p>
                                        </div>

                                        {{-- Resultados --}}
                                        <div id="resultContainer_tel" style="display: none;" class="mt-4">
                                            <div class="alert" id="resultAlert_tel"></div>

                                            <div id="statsContent_tel"></div>

                                            <div id="erroresContent_tel" style="display: none;">
                                                <h6 class="mt-3 mb-2">Errores encontrados:</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered" id="tablaErrores_tel">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Fila</th>
                                                                <th>Nombre</th>
                                                                <th>Error</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <a href="{{ route('telefonos.index') }}" class="btn btn-primary">
                                                    <i class="ri-check-line me-1"></i> Ver Teléfonos
                                                </a>
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="limpiarResultados('_tel')">
                                                    <i class="ri-refresh-line me-1"></i> Importar Otro
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Guía de Campos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionCampos">
                                            {{-- Encabezados requeridos --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseRequired">
                                                        <i class="ri-checkbox-circle-line me-2 text-success"></i> Campos
                                                        Obligatorios
                                                    </button>
                                                </h2>
                                                <div id="collapseRequired" class="accordion-collapse collapse show"
                                                    data-bs-parent="#accordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <ul class="list-unstyled small">
                                                            <li class="mb-2">
                                                                <strong>NOMBRE</strong><br>
                                                                <small class="text-muted">Nombre completo de la persona. Se
                                                                    parseará
                                                                    automáticamente en nombres y apellidos.</small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Campos opcionales básicos --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseBasic">
                                                        <i class="ri-file-text-line me-2 text-info"></i> Datos Básicos
                                                    </button>
                                                </h2>
                                                <div id="collapseBasic" class="accordion-collapse collapse"
                                                    data-bs-parent="#accordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <ul class="list-unstyled small">
                                                            <li class="mb-2">
                                                                <strong>CI</strong><br>
                                                                <small class="text-muted">Cédula de Identidad (solo
                                                                    números)</small>
                                                            </li>
                                                            <li class="mb-2">
                                                                <strong>responsable</strong><br>
                                                                <small class="text-muted">Nombre del
                                                                    responsable/investigador</small>
                                                            </li>
                                                            <li class="mb-2">
                                                                <strong>ESTADO</strong><br>
                                                                <small class="text-muted">Estado de investigación de la
                                                                    persona</small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Datos SEGIP --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseSEGIP">
                                                        <i class="ri-identity-card-line me-2 text-primary"></i> Datos SEGIP
                                                    </button>
                                                </h2>
                                                <div id="collapseSEGIP" class="accordion-collapse collapse"
                                                    data-bs-parent="#accordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <p class="small text-muted mb-2">🔄 <strong>Parsing
                                                                Automático</strong></p>
                                                        <p class="small mb-2">La columna <strong>DATOS_SEGIP</strong>
                                                            contiene toda la
                                                            información del SEGIP. El sistema extrae automáticamente:</p>
                                                        <ul class="list-unstyled small">
                                                            <li>✓ Fecha de Nacimiento</li>
                                                            <li>✓ Lugar de Nacimiento</li>
                                                            <li>✓ Domicilio</li>
                                                            <li>✓ Teléfono</li>
                                                            <li>✓ Género</li>
                                                            <li>✓ Estado Civil</li>
                                                            <li>✓ Nombre del Cónyuge</li>
                                                            <li>✓ Ocupación</li>
                                                            <li>✓ País</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Ejemplo de formato --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseExample">
                                                        <i class="ri-file-csv-line me-2 text-warning"></i> Ejemplo CSV
                                                    </button>
                                                </h2>
                                                <div id="collapseExample" class="accordion-collapse collapse"
                                                    data-bs-parent="#acordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <pre class="small bg-light p-2 rounded" style="overflow-x: auto;"><code>
                                        N REGISTRO;NOMBRE;CI;DATOS_SEGIP;responsable;ESTADO
                                        1;JUAN PEREZ GARCIA;1234567;Fecha de Nacimiento: 15/01/1990...;JHONY;En investigación
                                        2;MARIA LOPEZ QUISPE;7654321;Fecha de Nacimiento: 22/03/1985...;ENZO;Pendiente</code></pre>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Validaciones --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseValidation">
                                                        <i class="ri-error-warning-line me-2 text-danger"></i> Validaciones
                                                    </button>
                                                </h2>
                                                <div id="collapseValidation" class="accordion-collapse collapse"
                                                    data-bs-parent="#acordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <ul class="list-unstyled small">
                                                            <li class="mb-2">⚠️ Los CIs duplicados se actualizarán</li>
                                                            <li class="mb-2">✓ Las fechas se validan automáticamente</li>
                                                            <li class="mb-2">✓ Los géneros se normalizan
                                                                (MASCULINO/FEMENINO)</li>
                                                            <li class="mb-2">✓ Los países se buscan en la BD</li>
                                                            <li class="mb-2">✓ Los registros vacíos se ignoran</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Descargar plantilla --}}
                                        <div class="mt-3">
                                            <a href="{{ route('personas.importar.plantilla') }}"
                                                class="btn btn-sm btn-outline-primary w-100">
                                                <i class="ri-download-line me-1"></i> Descargar Plantilla
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Estadísticas --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Información</h5>
                                    </div>
                                    <div class="card-body small">
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="fw-bold">{{ \App\Models\Telefono::count() }}</div>
                                                    <small class="text-muted">Teléfonos</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="fw-bold">{{ \App\Models\Imei::count() }}</div>
                                                    <small class="text-muted">IMEIs</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="text-center p-2 bg-warning bg-opacity-10 rounded">
                                                    <div class="fw-bold">Máx 10 MB</div>
                                                    <small class="text-muted">Tamaño Archivo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- TAB IMEIS --}}
                    <div class="tab-pane p-3 fade" id="imeisTab" role="tabpanel">

                        <div class="row">
                            <div class="col-md-7">

                                <div class="card">
                                    <div class="card-body">
                                        {{-- Área de carga --}}
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <div class="border-2 border-dashed rounded-3 p-5 text-center"
                                                    id="dropZone_imei"
                                                    style="border-color: #dee2e6; cursor: pointer; transition: all 0.3s;">
                                                    <div id="uploadIcon_imei">
                                                        <div class="mb-3">
                                                            <i class="ri-smartphone-line"
                                                                style="font-size: 4rem; color: #0ab39c;"></i>
                                                        </div>
                                                        <p class="text-muted mt-3 mb-1"><strong>Importar IMEIs</strong></p>
                                                        <p class="text-muted mb-1">Arrastra tu archivo aquí o haz clic para
                                                            seleccionar
                                                        </p>
                                                        <small class="text-muted">Formatos: CSV, XLSX, XLS (máx.
                                                            10MB)</small>
                                                    </div>
                                                    <input type="file" id="archivoInput_imei" accept=".csv,.xlsx,.xls"
                                                        style="display: none;">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info archivo --}}
                                        <div id="infoArchivo_imei" style="display: none;">
                                            <div class="alert alert-info">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong id="nombreArchivo_imei"></strong>
                                                        <small id="tamañoArchivo_imei" class="d-block text-muted"></small>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        onclick="limpiarArchivo('_imei')">Cambiar</button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Botones de acción --}}
                                        <div class="row gap-2">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary w-100"
                                                    id="btnImportar_imei" style="display: none;"
                                                    onclick="importarArchivo('_imei', 'imeis')">
                                                    <i class="ri-download-line me-1"></i> Importar IMEIs
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Barra de progreso --}}
                                        <div id="progressContainer_imei" style="display: none;" class="mt-3">
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                    id="progressBar_imei" role="progressbar" style="width: 0%"
                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    <span id="progressText_imei">0%</span>
                                                </div>
                                            </div>
                                            <p class="text-muted small mt-2" id="progressInfo_imei">Procesando...</p>
                                        </div>

                                        {{-- Resultados --}}
                                        <div id="resultContainer_imei" style="display: none;" class="mt-4">
                                            <div class="alert" id="resultAlert_imei"></div>

                                            <div id="statsContent_imei"></div>

                                            <div id="erroresContent_imei" style="display: none;">
                                                <h6 class="mt-3 mb-2">Errores encontrados:</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered" id="tablaErrores_imei">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Fila</th>
                                                                <th>Nombre</th>
                                                                <th>Error</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <a href="{{ route('imeis.index') }}" class="btn btn-primary">
                                                    <i class="ri-check-line me-1"></i> Ver IMEIs
                                                </a>
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="limpiarResultados('_imei')">
                                                    <i class="ri-refresh-line me-1"></i> Importar Otro
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Guía de Campos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="accordionCampos">
                                            {{-- Encabezados requeridos --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseRequired">
                                                        <i class="ri-checkbox-circle-line me-2 text-success"></i> Campos
                                                        Obligatorios
                                                    </button>
                                                </h2>
                                                <div id="collapseRequired" class="accordion-collapse collapse show"
                                                    data-bs-parent="#accordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <ul class="list-unstyled small">
                                                            <li class="mb-2">
                                                                <strong>NOMBRE</strong><br>
                                                                <small class="text-muted">Nombre completo de la persona. Se
                                                                    parseará
                                                                    automáticamente en nombres y apellidos.</small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Campos opcionales básicos --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseBasic">
                                                        <i class="ri-file-text-line me-2 text-info"></i> Datos Básicos
                                                    </button>
                                                </h2>
                                                <div id="collapseBasic" class="accordion-collapse collapse"
                                                    data-bs-parent="#accordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <ul class="list-unstyled small">
                                                            <li class="mb-2">
                                                                <strong>CI</strong><br>
                                                                <small class="text-muted">Cédula de Identidad (solo
                                                                    números)</small>
                                                            </li>
                                                            <li class="mb-2">
                                                                <strong>responsable</strong><br>
                                                                <small class="text-muted">Nombre del
                                                                    responsable/investigador</small>
                                                            </li>
                                                            <li class="mb-2">
                                                                <strong>ESTADO</strong><br>
                                                                <small class="text-muted">Estado de investigación de la
                                                                    persona</small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Datos SEGIP --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseSEGIP">
                                                        <i class="ri-identity-card-line me-2 text-primary"></i> Datos SEGIP
                                                    </button>
                                                </h2>
                                                <div id="collapseSEGIP" class="accordion-collapse collapse"
                                                    data-bs-parent="#accordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <p class="small text-muted mb-2">🔄 <strong>Parsing
                                                                Automático</strong></p>
                                                        <p class="small mb-2">La columna <strong>DATOS_SEGIP</strong>
                                                            contiene toda la
                                                            información del SEGIP. El sistema extrae automáticamente:</p>
                                                        <ul class="list-unstyled small">
                                                            <li>✓ Fecha de Nacimiento</li>
                                                            <li>✓ Lugar de Nacimiento</li>
                                                            <li>✓ Domicilio</li>
                                                            <li>✓ Teléfono</li>
                                                            <li>✓ Género</li>
                                                            <li>✓ Estado Civil</li>
                                                            <li>✓ Nombre del Cónyuge</li>
                                                            <li>✓ Ocupación</li>
                                                            <li>✓ País</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Ejemplo de formato --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseExample">
                                                        <i class="ri-file-csv-line me-2 text-warning"></i> Ejemplo CSV
                                                    </button>
                                                </h2>
                                                <div id="collapseExample" class="accordion-collapse collapse"
                                                    data-bs-parent="#acordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <pre class="small bg-light p-2 rounded" style="overflow-x: auto;"><code>
                                        N REGISTRO;NOMBRE;CI;DATOS_SEGIP;responsable;ESTADO
                                        1;JUAN PEREZ GARCIA;1234567;Fecha de Nacimiento: 15/01/1990...;JHONY;En investigación
                                        2;MARIA LOPEZ QUISPE;7654321;Fecha de Nacimiento: 22/03/1985...;ENZO;Pendiente</code></pre>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Validaciones --}}
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseValidation">
                                                        <i class="ri-error-warning-line me-2 text-danger"></i> Validaciones
                                                    </button>
                                                </h2>
                                                <div id="collapseValidation" class="accordion-collapse collapse"
                                                    data-bs-parent="#acordionCampos">
                                                    <div class="accordion-body p-2">
                                                        <ul class="list-unstyled small">
                                                            <li class="mb-2">⚠️ Los CIs duplicados se actualizarán</li>
                                                            <li class="mb-2">✓ Las fechas se validan automáticamente</li>
                                                            <li class="mb-2">✓ Los géneros se normalizan
                                                                (MASCULINO/FEMENINO)</li>
                                                            <li class="mb-2">✓ Los países se buscan en la BD</li>
                                                            <li class="mb-2">✓ Los registros vacíos se ignoran</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Descargar plantilla --}}
                                        <div class="mt-3">
                                            <a href="{{ route('personas.importar.plantilla') }}"
                                                class="btn btn-sm btn-outline-primary w-100">
                                                <i class="ri-download-line me-1"></i> Descargar Plantilla
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Estadísticas --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Información</h5>
                                    </div>
                                    <div class="card-body small">
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="fw-bold">{{ \App\Models\Telefono::count() }}</div>
                                                    <small class="text-muted">Teléfonos</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="fw-bold">{{ \App\Models\Imei::count() }}</div>
                                                    <small class="text-muted">IMEIs</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="text-center p-2 bg-warning bg-opacity-10 rounded">
                                                    <div class="fw-bold">Máx 10 MB</div>
                                                    <small class="text-muted">Tamaño Archivo</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        {{-- Panel de referencia --}}

    </div>

    <style>
        #dropZone_tel:hover,
        #dropZone_imei:hover {
            border-color: #0ab39c !important;
            background-color: rgba(10, 179, 156, 0.05);
        }

        #dropZone_tel.dragover,
        #dropZone_imei.dragover {
            border-color: #0ab39c !important;
            background-color: rgba(10, 179, 156, 0.1);
        }
    </style>
@endsection

@section('js')
    <script>
        // Variables para cada tipo de importación
        let archivoSeleccionado_tel = null;
        let archivoSeleccionado_imei = null;

        // Inicializar drag and drop para Teléfonos
        const dropZone_tel = document.getElementById('dropZone_tel');
        const archivoInput_tel = document.getElementById('archivoInput_tel');

        dropZone_tel.addEventListener('click', () => archivoInput_tel.click());
        dropZone_tel.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone_tel.classList.add('dragover');
        });
        dropZone_tel.addEventListener('dragleave', () => {
            dropZone_tel.classList.remove('dragover');
        });
        dropZone_tel.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone_tel.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                seleccionarArchivo(files[0], '_tel');
            }
        });
        archivoInput_tel.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                seleccionarArchivo(e.target.files[0], '_tel');
            }
        });

        // Inicializar drag and drop para IMEIs
        const dropZone_imei = document.getElementById('dropZone_imei');
        const archivoInput_imei = document.getElementById('archivoInput_imei');

        dropZone_imei.addEventListener('click', () => archivoInput_imei.click());
        dropZone_imei.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone_imei.classList.add('dragover');
        });
        dropZone_imei.addEventListener('dragleave', () => {
            dropZone_imei.classList.remove('dragover');
        });
        dropZone_imei.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone_imei.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                seleccionarArchivo(files[0], '_imei');
            }
        });
        archivoInput_imei.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                seleccionarArchivo(e.target.files[0], '_imei');
            }
        });

        // Seleccionar archivo
        function seleccionarArchivo(archivo, sufijo) {
            // Guardar referencia del archivo
            if (sufijo === '_tel') {
                archivoSeleccionado_tel = archivo;
            } else {
                archivoSeleccionado_imei = archivo;
            }

            document.getElementById('uploadIcon' + sufijo).style.display = 'none';
            document.getElementById('infoArchivo' + sufijo).style.display = 'block';
            document.getElementById('btnImportar' + sufijo).style.display = 'block';

            document.getElementById('nombreArchivo' + sufijo).textContent = archivo.name;
            document.getElementById('tamañoArchivo' + sufijo).textContent =
                `${(archivo.size / 1024 / 1024).toFixed(2)} MB`;
        }

        // Limpiar archivo
        function limpiarArchivo(sufijo) {
            if (sufijo === '_tel') {
                archivoSeleccionado_tel = null;
            } else {
                archivoSeleccionado_imei = null;
            }

            document.getElementById('archivoInput' + sufijo).value = '';
            document.getElementById('uploadIcon' + sufijo).style.display = 'block';
            document.getElementById('infoArchivo' + sufijo).style.display = 'none';
            document.getElementById('btnImportar' + sufijo).style.display = 'none';
        }

        // Importar archivo
        function importarArchivo(sufijo, tipo) {
            const archivoSeleccionado = sufijo === '_tel' ? archivoSeleccionado_tel : archivoSeleccionado_imei;

            if (!archivoSeleccionado) {
                Swal.fire('Error', 'Selecciona un archivo', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('archivo', archivoSeleccionado);
            formData.append('_token', '{{ csrf_token() }}');

            document.getElementById('progressContainer' + sufijo).style.display = 'block';
            document.getElementById('btnImportar' + sufijo).disabled = true;

            // Simular progreso
            let progress = 0;
            const progressInterval = setInterval(() => {
                if (progress < 90) {
                    progress += Math.random() * 30;
                    actualizarProgreso(Math.min(progress, 90), sufijo);
                }
            }, 200);

            // Determinar la ruta según el tipo
            let ruta = tipo === 'telefonos' ? '{{ route('telefonos.importar.store') }}' :
                '{{ route('imeis.importar.store') }}';

            $.post(ruta, formData, {
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .done(data => {
                    clearInterval(progressInterval);
                    actualizarProgreso(100, sufijo);

                    setTimeout(() => {
                        document.getElementById('progressContainer' + sufijo).style.display = 'none';
                        mostrarResultados(data, sufijo);
                    }, 500);
                })
                .fail(err => {
                    clearInterval(progressInterval);
                    document.getElementById('btnImportar' + sufijo).disabled = false;
                    Swal.fire('Error', 'Error al importar: ' + err.responseJSON.message, 'error');
                });
        }

        // Actualizar progreso
        function actualizarProgreso(valor, sufijo) {
            document.getElementById('progressBar' + sufijo).style.width = valor + '%';
            document.getElementById('progressBar' + sufijo).setAttribute('aria-valuenow', valor);
            document.getElementById('progressText' + sufijo).textContent = Math.round(valor) + '%';
        }

        // Mostrar resultados
        function mostrarResultados(data, sufijo) {
            const container = document.getElementById('resultContainer' + sufijo);
            const alert = document.getElementById('resultAlert' + sufijo);
            const statsContent = document.getElementById('statsContent' + sufijo);
            const erroresContent = document.getElementById('erroresContent' + sufijo);

            if (data.errors) {
                alert.className = 'alert alert-danger';
                alert.innerHTML = `<i class="ri-error-warning-line me-2"></i> ${data.message}`;
            } else {
                alert.className = 'alert alert-success';
                alert.innerHTML = `<i class="ri-check-circle-line me-2"></i> ${data.success}`;

                // Estadísticas
                statsContent.innerHTML = `
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <div class="h4 text-success mb-0">${data.importadas}</div>
                                <small class="text-muted">Importadas</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                <div class="h4 text-info mb-0">${data.total}</div>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 ${data?.errores?.length > 0 ? 'bg-warning' : 'bg-success'} bg-opacity-10 rounded">
                                <div class="h4 ${data?.errores?.length > 0 ? 'text-warning' : 'text-success'} mb-0">${data?.errores?.length}</div>
                                <small class="text-muted">Errores</small>
                            </div>
                        </div>
                    </div>
                `;

                // Errores si los hay
                if (data?.errores?.length > 0) {
                    erroresContent.style.display = 'block';
                    const tbody = document.querySelector('#tablaErrores' + sufijo + ' tbody');
                    tbody.innerHTML = '';
                    data.errores.forEach(error => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${error.fila}</td>
                                <td>${error.nombre}</td>
                                <td><small>${error.error}</small></td>
                            </tr>
                        `;
                    });
                } else {
                    erroresContent.style.display = 'none';
                }
            }

            container.style.display = 'block';
        }

        // Limpiar resultados
        function limpiarResultados(sufijo) {
            document.getElementById('resultContainer' + sufijo).style.display = 'none';
            limpiarArchivo(sufijo);
            actualizarProgreso(0, sufijo);
        }
    </script>
@endsection
