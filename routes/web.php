<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;



Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Route::get('/velzon/{file?}', function (string $file = 'index') {
    return view('velzon.' . $file);
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::group(['middleware' => ['auth']], function () {


    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    // Mandamientos

    Route::get('mandamientos/importar', [App\Http\Controllers\ImportarPersonasController::class, 'indexMandamientoImportar'])->name('importar.mandamientos.index');
    Route::post('mandamientos/importar', [App\Http\Controllers\ImportarPersonasController::class, 'importarMandamientos'])->name('importar.mandamientos.importar');
    Route::resource('mandamientos', App\Http\Controllers\MandamientoController::class);
    Route::resource('tipos-mandamientos', App\Http\Controllers\TipoMandamientoController::class);
    Route::delete('/multimedia/{id}', [App\Http\Controllers\MultimediaController::class, 'destroy'])->name('multimedia.destroy');


    Route::get('consultas/mandamientos', [App\Http\Controllers\MandamientoController::class, 'consultarMandamientos'])->name('consultas.mandamientos');
    // Route::get('consultas/registro-criminal', [App\Http\Controllers\RegistroCriminalController::class, 'consultarRegistroCriminal'])->name('consultas.registro-criminal');


    // Rutas de importación de personas
    Route::get('/personas/importar', [App\Http\Controllers\ImportarPersonasController::class, 'index'])->name('personas.importar.index');
    Route::post('/personas/importar', [App\Http\Controllers\ImportarPersonasController::class, 'store'])->name('personas.importar.store');
    Route::get('/personas/importar/plantilla', [App\Http\Controllers\ImportarPersonasController::class, 'plantilla'])->name('personas.importar.plantilla');
    Route::resource('personas', PersonaController::class);

    Route::resource('juzgados', App\Http\Controllers\JuzgadoController::class);
    Route::resource('delitos', App\Http\Controllers\DelitoController::class);
    Route::resource('paises', App\Http\Controllers\PaisController::class);
    Route::resource('divisiones', App\Http\Controllers\DivisionController::class);
    Route::resource('telefonos', App\Http\Controllers\TelefonoController::class);
    Route::post('/telefonos/{telefono}/vincular-persona', [App\Http\Controllers\TelefonoController::class, 'vincularPersona'])->name('telefonos.vincular-persona');
    Route::post('/telefonos/{telefono}/agregar-imei', [App\Http\Controllers\TelefonoController::class, 'agregarIMEI'])->name('telefonos.agregar-imei');

    Route::resource('vehiculos', App\Http\Controllers\VehiculoController::class);

    Route::get('/personas-search', [PersonaController::class, 'search'])->name('personas.search');


    Route::resource('registro-criminal', App\Http\Controllers\RegistroCriminalController::class);

    // Usuarios
    Route::resource('usuarios', App\Http\Controllers\UserController::class);
    Route::post('usuarios/{id}/toggle-status', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('usuarios.toggle-status');

    // Perfil
    Route::get('/perfil', [App\Http\Controllers\ProfileController::class, 'index'])->name('perfil');
    Route::post('/perfil/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('perfil.password');
});
