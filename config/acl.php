<?php

return [
    'permissions' => [
        'superadmin' => [
            'users_all',
            'mandamientos_all',
            'personas_all',
            'vehiculos_all',
            'telefonos_all',
            'imeis_all',
            'importar_all',
            'consulta_all',
            'registro-criminal_all',
            'reporte_all',
            'ciudadanos_all',
            'vehiculos_padron_all',
        ],
        'administrador' => [
            'users_all',
            'mandamientos_all',
            'personas_all',
            'vehiculos_all',
            'telefonos_all',
            'imeis_all',
            'importar_all',
            'consulta_all',
            'registro-criminal_all',
            'reporte_all',
            'ciudadanos_all',
        ],
        'tecnico_felcc' => [
            'consulta_mandamientos',
            'personas_crear',
            'mandamientos_crear',
            'ciudadanos_all',

        ],
        'tecnico_daci' => [
            'registro-criminal_crear',
            'registro-criminal_listar',
            'personas_crear',
            'personas_listar',
            'mandamientos_listar',
            'mandamientos_crear',
            'vehiculos_listar',
            'vehiculos_crear',
            'vehiculos_vincular',
            'telefonos_listar',
            'telefonos_crear',
            'imeis_listar',
            'imeis_crear',
            'consulta_mandamientos',
            'consulta_registro-criminal',

        ],
        'consultor_felcc' => [
            'consulta_mandamientos',
        ],
        'consultor_daci' => [
            'consulta_mandamientos',
            'consulta_personas',

            'consulta_registro-criminal',
            'consulta_vehiculos',
            'consulta_telefonos',
        ],
    ],
];
