$(function () {
    "use strict";

    let paises = [];

    function cargarPaises() {
        $.get('/paises')
            .done(function (data) {
                paises = data;
                inicializarSelectPais();
            })
            .fail(function (error) {

            });
    }

    cargarPaises();
    function inicializarSelectPais() {

        $('#id_pais').select2({
            // dropdownParent: $('#miModal'),
            placeholder: {
                id: '',
                text: 'Seleccione un país'
            },
            language: {
                noResults: function () {
                    return "No se encontraron países";
                }
            },
            allowClear: true,
            width: '100%',
            // tags: true,
            theme: 'bootstrap-5',
            data: paises.map(function (pais) {
                return {
                    id: pais.id,
                    text: pais.gentilicio.toUpperCase()
                };
            }),

        }).val("").trigger('change');
    }

    let divisiones = [];

    function cargarDivision() {
        $.get('/divisiones')
            .done(function (data) {
                divisiones = data;

                inicializarSelectDivision();

            })
            .fail(function (error) {

            });
    }

    cargarDivision();

    function inicializarSelectDivision() {

        $('#id_division').select2({
            placeholder: 'Seleccione una división',
            allowClear: true,
            theme: 'bootstrap-5',
            width: '100%',
            language: {
                noResults: function () {
                    return "No se encontraron divisiones";
                }
            },
            tags: true,
            data: divisiones.map(function (division) {
                return {
                    id: division.id,
                    text: division.division.toUpperCase()
                };
            }),
            createTag: function (params) {
                const term = params.term.trim();
                if (term === '') {
                    return null;
                }
                // Verificar si ya existe
                const existe = divisiones.some(function (division) {
                    return division.division.toUpperCase() === term.toUpperCase();
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
                const nuevaDivision = data.text.replace(' (añadir nuevo)', '');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/divisiones',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: {
                        division: nuevaDivision
                    },
                    success: function (response) {
                        const newId = response.id || response.data.id;
                        const $select = $('#id_division');

                        // Eliminar la opción temporal con el prefijo 'new:'
                        $select.find('option[value="new:' + nuevaDivision + '"]').remove();

                        // Verificar si ya existe una opción con ese ID para evitar duplicados
                        if ($select.find('option[value="' + newId + '"]').length === 0) {
                            let nuevoOption = new Option(nuevaDivision, newId, true, true);
                            $select.append(nuevoOption);
                        }

                        $select.val(newId).trigger('change');

                        // Agregar al array local solo si no existe
                        const yaExiste = divisiones.some(function (division) {
                            return division.id === newId;
                        });

                        if (!yaExiste) {
                            divisiones.push({
                                id: newId,
                                division: nuevaDivision
                            });
                        }

                        notification(response.message,"División añadida");
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr);
                        processError(xhr);
                        // Limpiar la selección si falla
                        $('#id_division').val(null).trigger('change');
                    }
                });
            }

        }).val("").trigger('change');

    }




    //** procesar formulario */

    $("#form-registro").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
        }).done(function (response) {
            console.log(response);

        }).fail(function (error) {
            console.error(error);
        });

    })



});
