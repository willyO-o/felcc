/**
 * DataTable para Mandamientos
 * Sistema de Gestión de Mandamientos de Aprehensión - FELCC
 */

(function() {
    'use strict';

    // Variable global para la tabla
    let mandamientosTable;
    let deleteId = null;

    /**
     * Inicializar cuando el DOM esté listo
     */
    document.addEventListener('DOMContentLoaded', function() {
        initDataTable();
        initDeleteModal();
    });

    /**
     * Inicializar DataTable con configuración server-side
     */
    function initDataTable() {
        mandamientosTable = $('#mandamientos-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: window.location.href,
                type: 'GET',
                error: function(xhr, error, code) {
                    console.error('Error en DataTables:', error);
                    showAlert('Error al cargar los datos', 'error');
                }
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    data: 'hoja_ruta',
                    name: 'hoja_ruta',
                    width: '12%'
                },
                {
                    data: 'nombre_completo',
                    name: 'nombre_completo',
                    width: '15%'
                },
                {
                    data: 'ci',
                    name: 'ci',
                    width: '10%'
                },
                {
                    data: 'tipo_mandamiento',
                    name: 'tipo_mandamiento',
                    width: '12%'
                },
                {
                    data: 'tipo_documento',
                    name: 'tipo_documento',
                    width: '10%'
                },
                {
                    data: 'delito',
                    name: 'delito',
                    width: '12%'
                },
                {
                    data: 'juzgado',
                    name: 'juzgado',
                    width: '12%'
                },
                {
                    data: 'estado',
                    name: 'estado',
                    width: '10%'
                },
                {
                    data: 'acciones',
                    name: 'acciones',
                    orderable: false,
                    searchable: false,
                    width: '12%'
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
            language: {
                processing: "Procesando...",
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                loadingRecords: "Cargando...",
                zeroRecords: "No se encontraron registros coincidentes",
                emptyTable: "No hay datos disponibles en la tabla",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último"
                },
                aria: {
                    sortAscending: ": activar para ordenar la columna de manera ascendente",
                    sortDescending: ": activar para ordenar la columna de manera descendente"
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            responsive: true,
            autoWidth: false,
            drawCallback: function() {
                // Re-inicializar eventos de eliminación después de cada redibujado
                initDeleteButtons();
            }
        });
    }

    /**
     * Inicializar botones de eliminación
     */
    function initDeleteButtons() {
        $('.btn-delete').off('click').on('click', function() {
            deleteId = $(this).data('id');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    }

    /**
     * Inicializar modal de eliminación
     */
    function initDeleteModal() {
        $('#confirmDelete').on('click', function() {
            if (deleteId) {
                deleteMandamiento(deleteId);
            }
        });
    }

    /**
     * Eliminar mandamiento
     */
    function deleteMandamiento(id) {
        // Obtener el token CSRF
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/mandamientos/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Cerrar modal
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            deleteModal.hide();

            if (data.success) {
                showAlert(data.message || 'Mandamiento eliminado correctamente', 'success');
                // Recargar la tabla
                mandamientosTable.ajax.reload(null, false);
            } else {
                showAlert(data.message || 'Error al eliminar el mandamiento', 'error');
            }

            deleteId = null;
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al eliminar el mandamiento', 'error');

            // Cerrar modal
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            if (deleteModal) {
                deleteModal.hide();
            }

            deleteId = null;
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
    window.reloadMandamientosTable = function() {
        if (mandamientosTable) {
            mandamientosTable.ajax.reload(null, false);
        }
    };

})();
