/**
 * DataTable para Mandamientos
 * Sistema de Gestión de Mandamientos de Aprehensión - FELCC
 */


(function () {
    'use strict';


    /**
     * Mostrar alertas con SweetAlert2
     */
    function showAlert(message, type) {
        const config = {
            title: type === 'success' ? '¡Éxito!' : '¡Error!',
            text: message,
            icon: type,
            confirmButtonText: 'Aceptar',
            confirmButtonClass: 'btn btn-primary w-xs mt-2',
            buttonsStyling: false,
            showCloseButton: true
        };

        Swal.fire(config);
    }




    $(document).on('submit', '#mandamientoForm', function (e) {
        e.preventDefault();

        const datos = new FormData(this);

        const form = $(this);

        datos.append('_token', $('meta[name="csrf-token"]').attr('content'));

        const boton = form.find('button[type="submit"]');

        boton.prop('disabled', true).find('i').removeClass('ri-save-line').addClass('fa-solid fa-spinner fa-spin');

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: datos,
            processData: false,
            contentType: false
        }).done(function (response) {

            notification(response.success, "Mandamiento Registrado")
            $('#mandamientoForm')[0].reset();
            $('#id_tipo_mandamiento, #id_delito, #id_juzgado, #id_persona').val(null).trigger('change');
            $('#fecha_ejecucion').closest('.caja').addClass('d-none');
            $('#estado').val(null).trigger('change');


        }).fail(function (xhr) {
            console.error('Error:', xhr);
            processError(xhr);
        }).always(function () {
            boton.prop('disabled', false).find('i').removeClass('fa-solid fa-spinner fa-spin').addClass('ri-save-line');
        })


    });

    $(document).on('change', 'input[name="estado"]', function () {

        ($(this).val() === 'EJECUTADO' || $(this).val() === 'CANCELADO') ? $('#fecha_ejecucion').closest('.caja').removeClass('d-none') : $('#fecha_ejecucion').closest('.caja').addClass('d-none');
    });


    /* cargar datos parametricos tipo mandamiento */

    let tiposMandamientos = [];

    function cargarTiposMandamientos() {
        $.get('/tipos-mandamientos')
            .done(function (data) {
                tiposMandamientos = data;
                inicializarSelectTipoMandamiento();

            })
            .fail(function (error) {

            });
    }

    cargarTiposMandamientos();


    function inicializarSelectTipoMandamiento() {

        $('#id_tipo_mandamiento').select2({
            placeholder: 'Seleccione un tipo de mandamiento',
            allowClear: true,
            language: {
                noResults: function () {
                    return "No se encontraron tipos de mandamientos";
                }
            },
            width: '100%',
            tags: true,
            data: tiposMandamientos.map(function (tipo) {
                return {
                    id: tipo.id,
                    text: tipo.tipo_mandamiento
                };
            }),
            createTag: function (params) {
                const term = params.term.trim();
                if (term === '') {
                    return null;
                }
                // Verificar si ya existe
                const existe = tiposMandamientos.some(function (tipo) {
                    return tipo.tipo_mandamiento.toUpperCase() === term.toUpperCase();
                });
                if (existe) {
                    return null;
                }
                return {
                    id: 'new:' + term.toUpperCase(),
                    text: term.toUpperCase() + ' (Añadir nuevo)',
                    newTag: true
                };
            }
        }).on('select2:select', function (e) {
            const data = e.params.data;
            if (data.newTag) {
                const nuevoTipo = data.text.replace(' (Añadir nuevo)', '');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/tipos-mandamientos',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        tipo_mandamiento: nuevoTipo
                    },
                    success: function (response) {
                        if (response.success || response.id) {
                            const newId = response.id || response.data.id;
                            const $select = $('#id_tipo_mandamiento');

                            // Eliminar la opción temporal con el prefijo 'new:'
                            $select.find('option[value="new:' + nuevoTipo + '"]').remove();

                            // Verificar si ya existe una opción con ese ID para evitar duplicados
                            if ($select.find('option[value="' + newId + '"]').length === 0) {
                                let nuevoOption = new Option(nuevoTipo, newId, true, true);
                                $select.append(nuevoOption);
                            }

                            $select.val(newId).trigger('change');

                            // Agregar al array local solo si no existe
                            const yaExiste = tiposMandamientos.some(function (tipo) {
                                return tipo.id === newId;
                            });

                            if (!yaExiste) {
                                tiposMandamientos.push({
                                    id: newId,
                                    tipo_mandamiento: nuevoTipo
                                });
                            }

                            showAlert('Tipo de mandamiento agregado correctamente', 'success');
                        } else {
                            showAlert(response.message || 'Error al agregar el tipo de mandamiento', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        showAlert('Error al agregar el tipo de mandamiento', 'error');
                        // Limpiar la selección si falla
                        $('#id_tipo_mandamiento').val(null).trigger('change');
                    }
                });
            }
        });
    }


    let tiposDelitos = [];

    function cargarTiposDelitos() {
        $.get('/delitos')
            .done(function (data) {
                tiposDelitos = data;

                inicializarSelectDelito();

            })
            .fail(function (error) {

            });
    }

    cargarTiposDelitos();

    function inicializarSelectDelito() {

        $('#id_delito').select2({
            placeholder: 'Seleccione un delito',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function () {
                    return "No se encontraron delitos";
                }
            },
            tags: true,
            data: tiposDelitos.map(function (delito) {
                return {
                    id: delito.id,
                    text: delito.nombre_delito
                };
            }),
            createTag: function (params) {
                const term = params.term.trim();
                if (term === '') {
                    return null;
                }
                // Verificar si ya existe
                const existe = tiposDelitos.some(function (delito) {
                    return delito.nombre_delito.toUpperCase() === term.toUpperCase();
                });
                if (existe) {
                    return null;
                }
                return {
                    id: 'new:' + term.toUpperCase(),
                    text: term.toUpperCase() + ' (añadir nuevo)',
                    newTag: true
                };
            }
        }).on('select2:select', function (e) {
            const data = e.params.data;
            if (data.newTag) {
                const nuevoDelito = data.text.replace(' (añadir nuevo)', '');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/delitos',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        nombre_delito: nuevoDelito
                    },
                    success: function (response) {
                        if (response.success || response.id) {
                            const newId = response.id || response.data.id;
                            const $select = $('#id_delito');

                            // Eliminar la opción temporal con el prefijo 'new:'
                            $select.find('option[value="new:' + nuevoDelito + '"]').remove();

                            // Verificar si ya existe una opción con ese ID para evitar duplicados
                            if ($select.find('option[value="' + newId + '"]').length === 0) {
                                let nuevoOption = new Option(nuevoDelito, newId, true, true);
                                $select.append(nuevoOption);
                            }

                            $select.val(newId).trigger('change');

                            // Agregar al array local solo si no existe
                            const yaExiste = tiposDelitos.some(function (delito) {
                                return delito.id === newId;
                            });

                            if (!yaExiste) {
                                tiposDelitos.push({
                                    id: newId,
                                    nombre_delito: nuevoDelito
                                });
                            }

                            showAlert('Delito agregado correctamente', 'success');
                        } else {
                            showAlert(response.message || 'Error al agregar el delito', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        showAlert('Error al agregar el delito', 'error');
                        // Limpiar la selección si falla
                        $('#id_delito').val(null).trigger('change');
                    }
                });
            }

        });

    }


    let juzgados = [];

    function cargarJuzgados() {
        $.get('/juzgados')
            .done(function (data) {
                juzgados = data;
                inicializarSelectJuzgado();
            })
            .fail(function (error) {

            });
    }

    cargarJuzgados();

    function inicializarSelectJuzgado() {

        $('#id_juzgado').select2({
            placeholder: {
                id: '',
                text: 'Seleccione un juzgado'
            },
            language: {
                noResults: function () {
                    return "No se encontraron juzgados";
                }
            },
            allowClear: true,
            width: '100%',
            tags: true,
            data: juzgados.map(function (juzgado) {
                return {
                    id: juzgado.id,
                    text: juzgado.nombre_juzgado
                };
            }),
            createTag: function (params) {
                const term = params.term.trim();
                if (term === '') {
                    return null;
                }
                // Verificar si ya existe
                const existe = juzgados.some(function (juzgado) {
                    return juzgado.nombre_juzgado.toUpperCase() === term.toUpperCase();
                });
                if (existe) {
                    return null;
                }
                return {
                    id: 'new:' + term.toUpperCase(),
                    text: term.toUpperCase() + ' (añadir nuevo)',
                    newTag: true
                };
            }
        }).on('select2:select', function (e) {
            const data = e.params.data;
            if (data.newTag) {
                const nuevoJuzgado = data.text.replace(' (añadir nuevo)', '');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/juzgados',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        nombre_juzgado: nuevoJuzgado
                    },
                    success: function (response) {
                        if (response.success || response.id) {
                            const newId = response.id || response.data.id;
                            const $select = $('#id_juzgado');

                            // Eliminar la opción temporal con el prefijo 'new:'
                            $select.find('option[value="new:' + nuevoJuzgado + '"]').remove();

                            // Verificar si ya existe una opción con ese ID para evitar duplicados
                            if ($select.find('option[value="' + newId + '"]').length === 0) {
                                let nuevoOption = new Option(nuevoJuzgado, newId, true, true);
                                $select.append(nuevoOption);
                            }

                            $select.val(newId).trigger('change');

                            // Agregar al array local solo si no existe
                            const yaExiste = juzgados.some(function (juzgado) {
                                return juzgado.id === newId;
                            });

                            if (!yaExiste) {
                                juzgados.push({
                                    id: newId,
                                    nombre_juzgado: nuevoJuzgado
                                });
                            }

                            showAlert('Juzgado agregado correctamente', 'success');
                        } else {
                            showAlert(response.message || 'Error al agregar el juzgado', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        showAlert('Error al agregar el juzgado', 'error');
                        // Limpiar la selección si falla
                        $('#id_juzgado').val(null).trigger('change');
                    }
                });
            }
        });
    }



    inicializarSelectPersona();
    function inicializarSelectPersona() {
        $('#id_persona').select2({
            placeholder: 'Seleccione una persona',
            allowClear: true,
            language: {
                noResults: function () {
                    return "No se encontraron personas";
                }
            },
            ajax: {
                url: '/personas-search',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // término de búsqueda
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (persona) {
                            return {
                                id: persona.id,
                                text: `${persona.nombres} ${persona.apellidos || ""} - CI: ${persona.ci || ""}`
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });

    }

    FilePond.registerPlugin(
        // encodes the file as base64 data
        FilePondPluginFileEncode,
        // validates the size of the file
        FilePondPluginFileValidateSize,
        // corrects mobile image orientation
        FilePondPluginImageExifOrientation,
        // previews dropped images
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
    );


    $("#btnPersona").on('click', function (e) {
        e.preventDefault();

        let swalInstance = Swal.fire({
            title: 'Añadir Nueva Persona',
            html: /*html */`
                <form id="swal-persona-form" enctype="multipart/form-data" action="#" method="POST">
                    <input type="text" id="swal-ci" name="ci" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Cédula de Identidad (opcional)" >
                    <input type="text" id="swal-nombres" name="nombres" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Nombres" required>
                    <input type="text" id="swal-apellidos" name="apellidos" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Apellidos" required>
                    <input type="number" id="swal-celular" name="celular" class="form-control form-control-sm txtMayuscula mb-2" placeholder="Número de Celular (opcional)" >
                    <input type="date" id="swal-fecha_nacimiento" name="fecha_nacimiento" class="form-control form-control-sm mb-2" placeholder="Fecha de Nacimiento (opcional)">
                    <textarea id="swal-direccion" name="direccion" class="form-control form-control-sm mb-2" rows="3" placeholder="Dirección (opcional)"></textarea>
                    <input type="file" id="swal-foto" name="fotos" class="form-control form-control-sm mb-2" multiple accept="image/*" data-allow-reorder="true" data-max-file-size="3MB" data-max-files="3">
                </form>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            allowOutsideClick: false,   // 👈 clave
            allowEscapeKey: false,      // opcional (tecla ESC)
            allowEnterKey: false,       // opcional (ENTER)
            didOpen: () => {

                const inputElement = document.querySelector('#swal-foto');
                const confirmButton = Swal.getConfirmButton();
                FilePond.create(inputElement, {
                    storeAsFile: false,
                    allowMultiple: true,
                    labelIdle: 'Arrastra y suelta tu foto o <span class="filepond--label-action">Selecciona</span>',
                    imagePreviewHeight: 140,
                    // imageCropAspectRatio: '1:1',
                    //limitar a 3 imagenes
                    acceptedFileTypes: ['image/*'],
                    labelFileTypeNotAllowed: 'Archivo no válido',
                    labelMaxFilesExceeded: 'Demasiados archivos',
                    labelIdle: 'Arrastra o Suba hasta 3 imágenes (opcional)<br> <span class="filepond--label-action">Seleccionar</span>',
                    // data: {
                    //     type: 'avatar'
                    // },
                    // tamaño máximo de archivo 3MB
                    allowFileSizeValidation: true,
                    maxFileSize: '2MB',
                    labelMaxFileSize: 'Tamaño máximo de archivo es {filesize}',
                    labelMaxFileSizeExceeded: 'Archivo demasiado grande',
                    imageResizeTargetWidth: 200,
                    imageResizeTargetHeight: 200,
                    stylePanelLayout: 'compact stacked',
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProgressIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                    //solo permitir imagenes
                    acceptedFileTypes: ['image/*'],
                    labelFileTypeNotAllowed: 'Archivo no válido. Solo se permiten imágenes.',
                    onaddfilestart: () => {
                        confirmButton.disabled = true;
                        confirmButton.innerText = 'Procesando...';
                    },

                    // 2. Cuando el archivo termina de cargarse/procesarse con éxito
                    onprocessfile: (error) => {
                        if (!error) {
                            confirmButton.disabled = false;
                            confirmButton.innerText = 'Guardar';
                        }
                    },
                    onremovefile: () => {
                        const currentFiles = FilePond.find(document.querySelector('#swal-foto')).getFiles();
                        // Si no hay archivos procesándose actualmente, rehabilitar
                        const isProcessing = currentFiles.some(f => f.status === 2 || f.status === 3);
                        if (!isProcessing) {
                            confirmButton.disabled = false;
                            confirmButton.innerText = 'Guardar';
                        }
                    },

                    // 4. En caso de error en la carga del archivo
                    onaddfile: (error, file) => {
                        // Verificamos si hay archivos pendientes de carga técnica en el pool
                        const isBusy = FilePond.find(document.querySelector('#swal-foto')).getFiles().some(f => f.status !== 2 && f.status !== 5);

                        if (!isBusy) {
                            confirmButton.disabled = false;
                            confirmButton.innerText = 'Guardar';
                        }
                    },
                });
            },
            preConfirm: async () => { // 1. Agregamos async

                const formulario = document.getElementById('swal-persona-form');
                if (!formulario.checkValidity()) {
                    formulario.classList.add('was-validated');
                    Swal.showValidationMessage('<span class="text-danger">Por favor, complete todos los campos obligatorios.</span>');
                    return false;
                }


                let formData = new FormData(document.getElementById('swal-persona-form'));
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Obtener archivos de FilePond
                let pond = FilePond.find(document.querySelector('#swal-foto'));
                let files = pond.getFiles().map(fileItem => fileItem.file);
                files.forEach((file) => formData.append('fotos[]', file));

                // deshabilitar el botón de confirmación mientras se procesa la solicitud y mostrar un texto de carga
                // const confirmButton = Swal.getConfirmButton();
                // confirmButton.disabled = true;
                // confirmButton.innerText = 'Guardando...';
                Swal.showLoading();



                try {
                    // 2. Envolvemos el AJAX en una Promesa y usamos await
                    const response = await new Promise((resolve, reject) => {
                        $.ajax({
                            url: '/personas',
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': token },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: (res) => resolve(res),
                            error: (err) => reject(err)
                        });
                    });

                    Swal.hideLoading();

                    console.log('Servidor respondió:', response);

                    // 3. Si todo salió bien, puedes mostrar el éxito dentro del mismo modal
                    Swal.update({
                        icon: 'success',
                        title: 'Persona agregada',
                        html: 'La persona se guardó correctamente.',
                        showConfirmButton: false,
                        confirmButtonText: 'Aceptar'
                    });
                    // cerrar popup de agregar persona

                    // agregar la nueva persona al select del formulario principal
                    const newOption = new Option(`${response.datos.nombres} ${response.datos.apellidos || ''} - ${response.datos.ci || ''}`, response.datos.id, true, true);
                    $('#id_persona').append(newOption).trigger('change');

                    setTimeout(() => {
                        Swal.close();
                    }, 1500);

                    // IMPORTANTE: retornamos false para que el modal NO se cierre
                    // hasta que el usuario haga clic en el nuevo botón "Aceptar"
                    return false;

                } catch (xhr) {
                    console.log(xhr);

                    // Si hay error (422, 500, etc), el modal se queda abierto y muestra el error
                    const msg = xhr.responseJSON?.message || 'Error al guardar';
                    Swal.showValidationMessage(` <span class="text-danger">Error: ${msg}</span>`);
                    return false;
                }

            },

            // prevenir que el modal se cierre automáticamente para mostrar la alerta personalizada


        }).then((result) => {
            if (result.isConfirmed) {
                // prevenir que el swal se cierre automáticamente para mostrar la alerta personalizada


                // swalInstance.showLoading();
            }
        });


    })


    $(document).on('input', '#estado', function () {
        const valor = $(this).val();

        // verificar si en la cadena ingresada se encuentra la palabra "EJECUTADO" o "CANCELADO"

        if (!valor) {
            return;
        }

        if (valor.toUpperCase().includes('EJECUTADO')) {

            $(".requerido_ejecutado").show().find('input').attr('required', true);
        } else {
            $(".requerido_ejecutado").hide().find('input').attr('required', false);
        }


    })



})();
