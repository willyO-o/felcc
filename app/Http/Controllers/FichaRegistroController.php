<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FichaRegistro;

class FichaRegistroController extends Controller
{
    //


    public function index() {}

    public function create() {}


    public function store(Request $request)
    {
        $datos = $request->all();

        // eliminar emogis y caracteres especiales de los campos de texto solo permitir , ; : / # . ' " -  _ espacios, parentesis saltos de linea, tabulaciones, letras numeros Mayusculas minusculas acentos y ñ
        foreach ($datos as $key => $campo) {
            if (is_string($campo)) {
                $datos[$key] = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s,;:\/#.\'"\-\_\(\)\n\r\t]/u', '', $campo);
                $datos[$key] = trim($datos[$key]);
            }
        }



        $fichaRegistro =  FichaRegistro::whereRaw('MD5(id) = ?', [$datos['codigo'] ?? null])->first();
        if (!$fichaRegistro) {
            $fichaRegistro = FichaRegistro::create($datos);
        }else {
            $fichaRegistro->update($datos);
        }



        return response()->json([
            'data' => [
                'url' => route('ficha-registro.pdf', ['codigo' => md5($fichaRegistro->id)]),
                'codigo' => md5($fichaRegistro->id),
            ],
            'message' => 'Ficha de registro creada exitosamente'
        ]);
    }
}
