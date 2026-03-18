<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Lectura;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLecturaRequest;
use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Ubicacion;

class LecturaController extends Controller
{

public function reseña($id){
        $reseña = Lectura::with('libros')->where('id_libro',$id)->first();
        
        if(!$reseña){
            return redirect()->back()->with('error','Reseña no encontrada');
        }

        return Inertia::render('books/reseña', [
            'reseña' => $reseña
        ]);
    }

    public function editar($id){
    $reseña = Lectura::with('libros')->findOrFail($id); 

    return Inertia::render('books/form/reseñaFormulario', ['reseña' => $reseña]);
    }

    public function update(StoreLecturaRequest $request, $id)
{
    $datos = $request->validated();

    $reseña = Lectura::findOrFail($id);
    $reseña->update($datos);

    return redirect()->route('books.reseña',$reseña->id)->with('message', 'Reseña actualizado con éxito');
}
public function informe()
{
    $user_id = auth()->id();

    // Filtro base: Lecturas que pertenecen a Libros, que pertenecen a Géneros del usuario
    $queryLecturasUsuario = Lectura::whereHas('libros.genero', function ($q) use ($user_id) {
        $q->where('user_id', $user_id);
    });

    // 1. Puntuaciones (Calculadas solo sobre las lecturas del usuario)
    $puntuaciones = (clone $queryLecturasUsuario)
        ->select('puntuacion', DB::raw('count(*) as total'))
        ->whereNotNull('puntuacion')
        ->groupBy('puntuacion')
        ->pluck('total', 'puntuacion');

    // 2. Estados
    $estados = (clone $queryLecturasUsuario)
        ->select('estado', DB::raw('count(*) as total'))
        ->groupBy('estado')
        ->pluck('total', 'estado');

    // 3. Total de libros (Escalamos: Libros -> Genero -> User)
    $totalLibros = Libro::whereHas('genero', function ($q) use ($user_id) {
        $q->where('user_id', $user_id);
    })->count();

    return Inertia::render('books/informe', [
        'stats' => [
            'estrellas' => [
                5 => $puntuaciones->get(5, 0),
                4 => $puntuaciones->get(4, 0),
                3 => $puntuaciones->get(3, 0),
                2 => $puntuaciones->get(2, 0),
                1 => $puntuaciones->get(1, 0),
                0 => $puntuaciones->get(0, 0),
            ],
            'estados' => [
                'terminados' => $estados->get('Terminado', 0),
                'leyendo'    => $estados->get('Leyendo', 0),
                'pendiente'  => $estados->get('Pendiente', 0),
                'abandonados' => $estados->get('Abandonado', 0),
            ],
            'total_libros' => $totalLibros
        ]
    ]);
}

}

