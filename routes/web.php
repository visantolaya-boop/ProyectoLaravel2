<?php

use App\Http\Controllers\GeneroController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\UbicacionController;
use App\Models\Ubicacion;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use resources\js\Pages\Books;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

Route::get('/', function () {
    return Inertia::render('books/buscar', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
}); 

Route::redirect('/', '/buscar');

//Libros 
Route::get('/buscar', [LibroController::class, "buscar"])->name("books.buscar");
Route::get("/libros/{id}/editar",[LibroController::class, "editar"])->name('books.editar');
Route::put('/libros/{id}', [LibroController::class, 'update'])->name('books.update');

//Libro Creacion
Route::get('libros/crear',[LibroController::class, 'create'])->name('books.create');
Route::post('libros',[LibroController::class, 'store'])->name('books.store');

//Libros delete
Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('books.destroy');



//Reseñas
Route::get('/reseña/{id}', [LecturaController::class, "reseña"])->name("books.reseña");
Route::get('/reseña/{id}/editar', [LecturaController::class,"editar"])->name('reseñas.editar');
Route::put('/reseña/{id}', [LecturaController::class, 'update'])->name('reseñas.update');


//Informacion General
Route::get('/informacion', [LecturaController::class, 'informe'])->name('reseñas.informe');


//Ubicaciones
Route::get('/ubicacion/crear', [UbicacionController::class, 'create'])->name('ubi.create');
Route::post('/ubicacion',[UbicacionController::class, 'store'])->name("ubi.store");
Route::delete('/ubicacion/{id}', [UbicacionController::class, 'destroy'])->name('ubi.destroy');
Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubi.index');


//Generos
Route::get('/genero/crear', [GeneroController::class, 'create'])->name('genero.create');
Route::post('/genero',[GeneroController::class, 'store'])->name("genero.store");
Route::delete('/genero/{id}', [GeneroController::class, 'destroy'])->name('genero.destroy');
Route::get('/generos', [GeneroController::class, 'index'])->name('genero.index');

});

require __DIR__.'/auth.php';