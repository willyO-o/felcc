<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\Importacion;
use App\Http\Controllers\RegistroCriminalController;
use App\Http\Controllers\MandamientoController;
use App\Http\Controllers\AuditarConsultasController;


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

    Route::get('mandamientos/importar', [Importacion::class, 'indexMandamientoImportar'])->name('importar.mandamientos.index');
    Route::post('mandamientos/importar', [Importacion::class, 'importarMandamientos'])->name('importar.mandamientos.importar');
    Route::resource('mandamientos', MandamientoController::class);
    Route::get('/mandamiento/{codigo}', [MandamientoController::class, 'showByCodigo'])->name('mandamientos.showByCodigo');
    Route::resource('tipos-mandamientos', App\Http\Controllers\TipoMandamientoController::class);
    Route::delete('/multimedia/{id}', [App\Http\Controllers\MultimediaController::class, 'destroy'])->name('multimedia.destroy');


    Route::get('consultas/mandamientos', [MandamientoController::class, 'consultarMandamientos'])->name('consultas.mandamientos');
    Route::get('consultas/registro-criminal', [RegistroCriminalController::class, 'consultarRegistroCriminal'])->name('consultas.registro-criminal');


    // Rutas de importación de personast
    Route::get('/personas/importar', [Importacion::class, 'index'])->name('personas.importar.index');
    Route::post('/personas/importar', [Importacion::class, 'store'])->name('personas.importar.store');
    Route::get('/personas/importar/plantilla', [Importacion::class, 'plantilla'])->name('personas.importar.plantilla');
    // Rutas de importación de telefonos
    Route::get('/telefonos/importar', [Importacion::class, 'indexTelefono'])->name('telefonos.importar.index');
    Route::post('/telefonos/importar', [Importacion::class, 'storeTelefono'])->name('telefonos.importar.store');
    Route::post('/imeis/importar', [Importacion::class, 'storeIMEI'])->name('imeis.importar.store');

    // Rutas de importación de vehiculos
    Route::get('/vehiculos/importar', [Importacion::class, 'indexVehiculo'])->name('vehiculos.importar.index');
    Route::post('/vehiculos/importar', [Importacion::class, 'storeVehiculo'])->name('vehiculos.importar.store');
    Route::post('/vehiculos/carguios/importar', [Importacion::class, 'storeCarguiosVehiculo'])->name('vehiculos.carguios.importar.store');
    Route::post('/vehiculos/inspecciones/importar', [Importacion::class, 'storeInspeccionesVehiculo'])->name('vehiculos.inspecciones.importar.store');



    Route::resource('personas', PersonaController::class);

    Route::resource('juzgados', App\Http\Controllers\JuzgadoController::class);
    Route::resource('delitos', App\Http\Controllers\DelitoController::class);
    Route::resource('paises', App\Http\Controllers\PaisController::class);
    Route::resource('divisiones', App\Http\Controllers\DivisionController::class);
    Route::resource('telefonos', App\Http\Controllers\TelefonoController::class);
    Route::post('/telefonos/{telefono}/vincular-persona', [App\Http\Controllers\TelefonoController::class, 'vincularPersona'])->name('telefonos.vincular-persona');
    Route::post('/telefonos/{telefono}/agregar-imei', [App\Http\Controllers\TelefonoController::class, 'agregarIMEI'])->name('telefonos.agregar-imei');

    Route::resource('imeis', App\Http\Controllers\ImeiController::class);
    Route::get('/telefonos-imeis-search', [App\Http\Controllers\TelefonoController::class, 'searchTelefonos'])->name('imeis.telefonos-search');
    Route::post('/imeis-vincular-telefono/{id}', [App\Http\Controllers\ImeiController::class, 'vincularTelefono'])->name('imeis.vincular-telefono');

    Route::resource('vehiculos', App\Http\Controllers\VehiculoController::class);
    Route::post('/vehiculos/{vehiculo}/vincular-persona', [App\Http\Controllers\VehiculoController::class, 'vincularPersona'])->name('vehiculos.vincular-persona');
    Route::delete('/vehiculos/{vehiculo}/desvincular-persona/{persona}', [App\Http\Controllers\VehiculoController::class, 'desvincularPersona'])->name('vehiculos.desvincular-persona');

    Route::get('/personas-search', [PersonaController::class, 'search'])->name('personas.search');


    Route::resource('registro-criminal', RegistroCriminalController::class);
    Route::get('/registro/{codigo}', [RegistroCriminalController::class, 'showByCodigo'])->name('registro-criminal.showByCodigo');

    // Usuarios
    Route::resource('usuarios', App\Http\Controllers\UserController::class);
    Route::post('usuarios/{id}/toggle-status', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('usuarios.toggle-status');

    // Perfil
    Route::get('/perfil', [App\Http\Controllers\ProfileController::class, 'index'])->name('perfil');
    Route::post('/perfil/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('perfil.password');

    // Auditar Consultas
    Route::resource('auditar-consultas', AuditarConsultasController::class, ['only' => ['index', 'show']]);
    Route::get('/auditar-consultas/filtros/modulos', [AuditarConsultasController::class, 'obtenerModulos'])->name('auditar-consultas.modulos');
    Route::get('/auditar-consultas/filtros/roles', [AuditarConsultasController::class, 'obtenerRoles'])->name('auditar-consultas.roles');


    Route::get('/registro-vista-previa/{id}', [RegistroCriminalController::class, 'vistaPrevia'])->name('registro.vista-previa');
    Route::get('/ficha-registro/pdf/{codigo}', [App\Http\Controllers\ReporteController::class, 'pdfFicha'])->name('ficha-registro.pdf');
    Route::post('/ficha-registro', [App\Http\Controllers\FichaRegistroController::class, 'store'])->name('ficha-registro.store');

});
