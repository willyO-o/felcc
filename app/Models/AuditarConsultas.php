<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditarConsultas extends Model
{
    //

    protected $table = 'auditar_consultas';

    protected $fillable = [
        'user_id',
        'rol_usuario',
        'modulo',
        'criterios_consulta',
        'cantidad_resultados',
        'ids_accedidos',
        'ip_usuario',
        'user_agent',
        'identificador',
    ];

    protected $casts = [
        'criterios_consulta' => 'array',
        'ids_accedidos' => 'array',
    ];


    static  function registrar($user, $modulo, $request)
    {
        self::create([
            'user_id' => $user->id,
            'rol_usuario' => $user->role ? $user->role->nombre : 'desconocido',
            'modulo' => $modulo,
            'criterios_consulta' => [
                'tipo_filtro' => $request->get('tipo_filtro'),
                'busqueda' => $request->get('search'),
                'fecha_inicio' => $request->get('fecha_inicio'),
                'fecha_fin' => $request->get('fecha_fin'),
            ],
            'cantidad_resultados' => $request->get('cantidad_resultados', 0),
            'ip_usuario' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'identificador' => $request->get('identificador', null),
        ]);
    }

    static function agregarIdsAccedidos($identificador, $id, $modulo)
    {
        $registro = self::where('identificador', $identificador)->first();
        if ($registro) {
            //formato de objeto [{id: 1, fecha_acceso: '2024-06-01T12:00:00Z'}, {...}]
            $idsExistentes = $registro->ids_accedidos ?? [];
            $idsActualizados = array_merge($idsExistentes, [['id' => $id, 'fecha_acceso' => now()->toDateTimeString(), 'modulo' => $modulo]]);
            $registro->ids_accedidos = $idsActualizados;
            $registro->save();
        }
    }
}
