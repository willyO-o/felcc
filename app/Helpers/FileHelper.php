<?php

if (!function_exists('formatoPrecio')) {
    function formatoPrecio($valor) {
        return '$' . number_format($valor, 2);
    }
}
