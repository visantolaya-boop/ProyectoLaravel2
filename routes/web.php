<?php

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

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
}); 

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/libros', [LibroController::class, "index"])->name("books.libros");

Route::get('/buscar', [LibroController::class, "buscar"])->name("books.buscar");

Route::get("/libros/{id}/editar",[LibroController::class, "editar"])->name('books.editar');

Route::put('/libros/{id}', [LibroController::class, 'update'])->name('books.update');

Route::get('libros/crear',[LibroController::class, 'create'])->name('books.create');

Route::post('libros',[LibroController::class, 'store'])->name('books.store');

Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('books.destroy');

Route::get('/reseña/{id}', [LecturaController::class, "reseña"])->name("books.reseña");

Route::get('/reseña/{id}/editar', [LecturaController::class,"editar"])->name('reseñas.editar');

Route::put('/reseña/{id}', [LecturaController::class, 'update'])->name('reseñas.update');

Route::get('/informacion', [LecturaController::class, 'informe'])->name('reseñas.informe');

Route::get('/ubicacion/crear', [UbicacionController::class, 'create'])->name('ubi.create');

Route::post('/ubicacion',[UbicacionController::class, 'store'])->name("ubi.store");

Route::delete('/ubicacion/{id}', [UbicacionController::class, 'destroy'])->name('ubi.destroy');

Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubi.index');
require __DIR__.'/auth.php';