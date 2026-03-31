@extends('layouts.app')

@section('page-title', 'Importar Personas desde CSV')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('personas.index') }}">Personas</a></li>
            <li class="breadcrumb-item active">Importar</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Importación Masiva de Personas</h5>
                </div>
                <div class="card-body">
                    {{-- Área de carga --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="border-2 border-dashed rounded-3 p-5 text-center" id="dropZone" style="border-color: #dee2e6; cursor: pointer; transition: all 0.3s;">
                                <div id="uploadIcon">
                                    <i class="ri-upload-cloud-2-line" style="font-size: 3rem; color: #0ab39c;"></i>
                                    <p class="text-muted mt-3 mb-1">Arrastra tu archivo aquí o haz clic para seleccionar</p>
                                    <small class="text-muted">Formatos: CSV, XLSX, XLS (máx. 10MB)</small>
                                </div>
                                <input type="file" id="archivoInput" accept=".csv,.xlsx,.xls" style="display: none;">
                            </div>
                        </div>
                    </div>

                    {{-- Info archivo --}}
                    <div id="infoArchivo" style="display: none;">
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong id="nombreArchivo"></strong>
                                    <small id="tamañoArchivo" class="d-block text-muted"></small>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="limpiarArchivo()">Cambiar</button>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="row gap-2">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary w-100" id="btnImportar" style="display: none;" onclick="importarArchivo()">
                                <i class="ri-download-line me-1"></i> Importar Personas
                            </button>
                        </div>
                    </div>

                    {{-- Barra de progreso --}}
                    <div id="progressContainer" style="display: none;" class="mt-3">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <span id="progressText">0%</span>
                            </div>
                        </div>
                        <p class="text-muted small mt-2" id="progressInfo">Procesando...</p>
                    </div>

                    {{-- Resultados --}}
                    <div id="resultContainer" style="display: none;" class="mt-4">
                        <div class="alert" id="resultAlert"></div>

                        <div id="statsContent"></div>

                        <div id="erroresContent" style="display: none;">
                            <h6 class="mt-3 mb-2">Errores encontrados:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="tablaErrores">
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
                            <a href="{{ route('personas.index') }}" class="btn btn-primary">
                                <i class="ri-check-line me-1"></i> Ver Personas
                            </a>
                            <button type="button" class="btn btn-secondary" onclick="limpiarResultados()">
                                <i class="ri-refresh-line me-1"></i> Importar Otro
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel de referencia --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Guía de Campos</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionCampos">
                        {{-- Encabezados requeridos --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRequired">
                                    <i class="ri-checkbox-circle-line me-2 text-success"></i> Campos Obligatorios
                                </button>
                            </h2>
                            <div id="collapseRequired" class="accordion-collapse collapse show" data-bs-parent="#accordionCampos">
                                <div class="accordion-body p-2">
                                    <ul class="list-unstyled small">
                                        <li class="mb-2">
                                            <strong>NOMBRE</strong><br>
                                            <small class="text-muted">Nombre completo de la persona. Se parseará automáticamente en nombres y apellidos.</small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Campos opcionales básicos --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBasic">
                                    <i class="ri-file-text-line me-2 text-info"></i> Datos Básicos
                                </button>
                            </h2>
                            <div id="collapseBasic" class="accordion-collapse collapse" data-bs-parent="#accordionCampos">
                                <div class="accordion-body p-2">
                                    <ul class="list-unstyled small">
                                        <li class="mb-2">
                                            <strong>CI</strong><br>
                                            <small class="text-muted">Cédula de Identidad (solo números)</small>
                                        </li>
                                        <li class="mb-2">
                                            <strong>responsable</strong><br>
                                            <small class="text-muted">Nombre del responsable/investigador</small>
                                        </li>
                                        <li class="mb-2">
                                            <strong>ESTADO</strong><br>
                                            <small class="text-muted">Estado de investigación de la persona</small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Datos SEGIP --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSEGIP">
                                    <i class="ri-identity-card-line me-2 text-primary"></i> Datos SEGIP
                                </button>
                            </h2>
                            <div id="collapseSEGIP" class="accordion-collapse collapse" data-bs-parent="#accordionCampos">
                                <div class="accordion-body p-2">
                                    <p class="small text-muted mb-2">🔄 <strong>Parsing Automático</strong></p>
                                    <p class="small mb-2">La columna <strong>DATOS_SEGIP</strong> contiene toda la información del SEGIP. El sistema extrae automáticamente:</p>
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
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample">
                                    <i class="ri-file-csv-line me-2 text-warning"></i> Ejemplo CSV
                                </button>
                            </h2>
                            <div id="collapseExample" class="accordion-collapse collapse" data-bs-parent="#acordionCampos">
                                <div class="accordion-body p-2">
                                    <pre class="small bg-light p-2 rounded" style="overflow-x: auto;"><code>N REGISTRO;NOMBRE;CI;DATOS_SEGIP;responsable;ESTADO
1;JUAN PEREZ GARCIA;1234567;Fecha de Nacimiento: 15/01/1990...;JHONY;En investigación
2;MARIA LOPEZ QUISPE;7654321;Fecha de Nacimiento: 22/03/1985...;ENZO;Pendiente</code></pre>
                                </div>
                            </div>
                        </div>

                        {{-- Validaciones --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseValidation">
                                    <i class="ri-error-warning-line me-2 text-danger"></i> Validaciones
                                </button>
                            </h2>
                            <div id="collapseValidation" class="accordion-collapse collapse" data-bs-parent="#acordionCampos">
                                <div class="accordion-body p-2">
                                    <ul class="list-unstyled small">
                                        <li class="mb-2">⚠️ Los CIs duplicados se actualizarán</li>
                                        <li class="mb-2">✓ Las fechas se validan automáticamente</li>
                                        <li class="mb-2">✓ Los géneros se normalizan (MASCULINO/FEMENINO)</li>
                                        <li class="mb-2">✓ Los países se buscan en la BD</li>
                                        <li class="mb-2">✓ Los registros vacíos se ignoran</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Descargar plantilla --}}
                    <div class="mt-3">
                        <a href="{{ route('personas.importar.plantilla') }}" class="btn btn-sm btn-outline-primary w-100">
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
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-bold">{{ \App\Models\Persona::count() }}</div>
                                <small class="text-muted">Total Personas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-bold">Máx 10 MB</div>
                                <small class="text-muted">Tamaño Archivo</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #dropZone:hover {
            border-color: #0ab39c !important;
            background-color: rgba(10, 179, 156, 0.05);
        }

        #dropZone.dragover {
            border-color: #0ab39c !important;
            background-color: rgba(10, 179, 156, 0.1);
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let archivoSeleccionado = null;

        const dropZone = document.getElementById('dropZone');
        const archivoInput = document.getElementById('archivoInput');

        // Drag and drop
        dropZone.addEventListener('click', () => archivoInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                seleccionarArchivo(files[0]);
            }
        });

        archivoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                seleccionarArchivo(e.target.files[0]);
            }
        });

        function seleccionarArchivo(archivo) {
            archivoSeleccionado = archivo;
            document.getElementById('uploadIcon').style.display = 'none';
            document.getElementById('infoArchivo').style.display = 'block';
            document.getElementById('btnImportar').style.display = 'block';

            document.getElementById('nombreArchivo').textContent = archivo.name;
            document.getElementById('tamañoArchivo').textContent =
                `${(archivo.size / 1024 / 1024).toFixed(2)} MB`;
        }

        function limpiarArchivo() {
            archivoSeleccionado = null;
            archivoInput.value = '';
            document.getElementById('uploadIcon').style.display = 'block';
            document.getElementById('infoArchivo').style.display = 'none';
            document.getElementById('btnImportar').style.display = 'none';
        }

        function importarArchivo() {
            if (!archivoSeleccionado) {
                Swal.fire('Error', 'Selecciona un archivo', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('archivo', archivoSeleccionado);
            formData.append('_token', '{{ csrf_token() }}');

            document.getElementById('progressContainer').style.display = 'block';
            document.getElementById('btnImportar').disabled = true;

            // Simular progreso
            let progress = 0;
            const progressInterval = setInterval(() => {
                if (progress < 90) {
                    progress += Math.random() * 30;
                    actualizarProgreso(Math.min(progress, 90));
                }
            }, 200);

            fetch('{{ route('personas.importar.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    clearInterval(progressInterval);
                    actualizarProgreso(100);

                    setTimeout(() => {
                        document.getElementById('progressContainer').style.display = 'none';
                        mostrarResultados(data);
                        document.getElementById('btnImportar').disabled = false;
                    }, 500);
                })
                .catch(err => {
                    clearInterval(progressInterval);
                    document.getElementById('btnImportar').disabled = false;
                    Swal.fire('Error', 'Error al importar: ' + err.message, 'error');
                });
        }

        function actualizarProgreso(valor) {
            document.getElementById('progressBar').style.width = valor + '%';
            document.getElementById('progressBar').setAttribute('aria-valuenow', valor);
            document.getElementById('progressText').textContent = Math.round(valor) + '%';
        }

        function mostrarResultados(data) {
            const container = document.getElementById('resultContainer');
            const alert = document.getElementById('resultAlert');
            const statsContent = document.getElementById('statsContent');
            const erroresContent = document.getElementById('erroresContent');

            if (data.error) {
                alert.className = 'alert alert-danger';
                alert.innerHTML = `<i class="ri-error-warning-line me-2"></i> ${data.error}`;
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
                            <div class="text-center p-3 ${data.errores.length > 0 ? 'bg-warning' : 'bg-success'} bg-opacity-10 rounded">
                                <div class="h4 ${data.errores.length > 0 ? 'text-warning' : 'text-success'} mb-0">${data.errores.length}</div>
                                <small class="text-muted">Errores</small>
                            </div>
                        </div>
                    </div>
                `;

                // Errores si los hay
                if (data.errores.length > 0) {
                    erroresContent.style.display = 'block';
                    const tbody = document.querySelector('#tablaErrores tbody');
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

        function limpiarResultados() {
            document.getElementById('resultContainer').style.display = 'none';
            limpiarArchivo();
            actualizarProgreso(0);
        }
    </script>
@endsection
