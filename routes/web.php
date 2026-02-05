<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/velzon/{file?}', function (string $file = 'index') {
    return view('velzon.' . $file);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// Mandamientos
Route::resource('mandamientos', App\Http\Controllers\MandamientoController::class);
Route::resource('tipos-mandamientos', App\Http\Controllers\TipoMandamientoController::class);
Route::resource('personas', PersonaController::class);
Route::resource('juzgados', App\Http\Controllers\JuzgadoController::class);
Route::resource('delitos', App\Http\Controllers\DelitoController::class);
Route::get('/personas-search', [PersonaController::class, 'search'])->name('personas.search');


