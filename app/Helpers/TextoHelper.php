<?php

if (!function_exists('eliminarEspaciosMultiples')) {
    function eliminarEspaciosMultiples($cadena)
    {

        if (!$cadena) {
            return $cadena;
        }

        $cadena = trim(preg_replace('/\s+/', ' ', $cadena));
        return $cadena;
    }
}



if (!function_exists('convertirMayusculas')) {
    function convertirMayusculas($cadena)
    {

        if (!$cadena) {
            return $cadena;
        }

        return mb_strtoupper($cadena, 'UTF-8');
    }
}

if (!function_exists('convertirMinusculas')) {
    function convertirMinusculas($cadena)
    {

        if (!$cadena) {
            return $cadena;
        }
        return mb_strtolower($cadena, 'UTF-8');
    }
}

if (!function_exists('nuloSiVacio')) {
    function nuloSiVacio($cadena)
    {

        if (empty(trim($cadena))) {
            return null;
        }
        return $cadena;
    }
}

if (!function_exists('campoDB')) {
    function campoDB($cadena)
    {

        if (!$cadena) {
            return nuloSiVacio($cadena);
        }
        $cadena = eliminarEspaciosMultiples($cadena);
        $cadena = convertirMayusculas($cadena);

        return $cadena;
    }
}
