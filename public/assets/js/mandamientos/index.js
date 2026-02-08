/**
 * DataTable para Mandamientos
 * Sistema de Gestión de Mandamientos de Aprehensión - FELCC
 */


(function () {
    'use strict';

    // Variable global para la tabla
    let mandamientosTable;




    /**
     * Inicializar botones de eliminación
     */
    function initDeleteButtons() {
        $('.btn-delete').off('click').on('click', function () {
            deleteId = $(this).data('id');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    }




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

    /**
     * Función para recargar la tabla (puede ser llamada desde fuera)
     */
    window.reloadMandamientosTable = function () {
        if (mandamientosTable) {
            mandamientosTable.ajax.reload(null, false);
        }
    };


    $(document).on('submit', '#mandamientoForm', function (e) {
        e.preventDefault();

        const datos = new FormData(this);

        const form = $(this);

        datos.append('_token', $('meta[name="csrf-token"]').attr('content'));


        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: datos,
            processData: false,
            contentType: false
        }).done(function (response) {
            console.log(response);

            if (response.success) {

                // showAlert(response.message || 'Mandamiento guardado correctamente', 'success');
                // $('#miModal').modal('hide');
                // window.reloadMandamientosTable();
            } else {
                showAlert(response.message || 'Error al guardar el mandamiento', 'error');
            }
        }).fail(function (xhr) {
            console.error('Error:', xhr);
            showAlert('Error al guardar el mandamiento', 'error');
        });


    });


    $(document).on('click', '.openModal', function (e) {
        e.preventDefault();

        const url = $(this).val();
        const miModal = new bootstrap.Modal(document.getElementById('miModal'));
        miModal.show();


    })


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
            dropdownParent: $('#miModal'),
            placeholder: 'Seleccione un tipo de mandamiento',
            allowClear: true,
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
            dropdownParent: $('#miModal'),
            placeholder: 'Seleccione un delito',
            allowClear: true,
            width: '100%',
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
            dropdownParent: $('#miModal'),
            placeholder: 'Seleccione un juzgado',
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
            dropdownParent: $('#miModal'),
            placeholder: 'Seleccione una persona',
            allowClear: true,
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
                                text: `${persona.nombre} ${persona.paterno || ""} ${persona.materno || ""} - CI: ${persona.ci || ""}`
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });

    }


    $("#btnPersona").on('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Añadir Nueva Persona',
            html: `
                <input type="text" id="swal-nombre" class="form-control form-control-sm mb-2" placeholder="Nombre" required>
                <input type="text" id="swal-paterno" class="form-control form-control-sm mb-2" placeholder="Apellido Paterno" required>
                <input type="text" id="swal-materno" class="form-control form-control-sm mb-2" placeholder="Apellido Materno">
                <input type="text" id="swal-ci" class="form-control form-control-sm mb-2" placeholder="Cédula de Identidad" required>
                <input type="number" id="swal-celular" class="form-control form-control-sm mb-2" placeholder="Número de Celular">
                <input type="date" id="swal-fecha_nacimiento" class="form-control form-control-sm mb-2" placeholder="Fecha de Nacimiento">
                <textarea id="swal-direccion" class="form-control form-control-sm mb-2" rows="3" placeholder="Dirección"></textarea>
                <in
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            preConfirm: () => {
                const nombre = document.getElementById('swal-nombre').value;
                const paterno = document.getElementById('swal-paterno').value;
                const materno = document.getElementById('swal-materno').value;
                const ci = document.getElementById('swal-ci').value;

                if (!nombre || !paterno || !ci) {
                    Swal.showValidationMessage('Por favor, complete todos los campos obligatorios.');

                    document.getElementById('swal-nombre').classList.add(nombre ? 'is-valid' : 'is-invalid');
                    document.getElementById('swal-paterno').classList.add(paterno ? 'is-valid' : 'is-invalid');
                    document.getElementById('swal-ci').classList.add(ci ? 'is-valid' : 'is-invalid');

                    return;
                }

                return { nombre: nombre, paterno: paterno, materno: materno, ci: ci };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const personaData = result.value;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/personas',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: personaData,
                    success: function (response) {
                        if (response.success || response.id) {
                            const newId = response.id || response.data.id;
                            const newText = `${personaData.nombre} ${personaData.paterno} ${personaData.materno || ""} - CI: ${personaData.ci || ""}`;
                            const $select = $('#id_persona');

                            let nuevoOption = new Option(newText, newId, true, true);
                            $select.append(nuevoOption).trigger('change');

                            showAlert('Persona agregada correctamente', 'success');
                        } else {
                            showAlert(response.message || 'Error al agregar la persona', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        showAlert('Error al agregar la persona', 'error');
                    }
                });
            }
        });


    })

})();
