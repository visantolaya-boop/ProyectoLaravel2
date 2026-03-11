<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Lectura;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Libro;

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

    public function update(Request $request, $id)
{
    $datos = $request->validate([
        'estado' => 'required|string',
        'puntuacion'=>'required|integer',
        'final_lectura'=>'date|nullable',
        'inicio_lectura'=>'date|required',
        'tiempo_lectura'=>'integer|nullable',
        'reseña'=>'string|max:255|nullable',
    ]);

    $reseña = Lectura::findOrFail($id);
    $reseña->update($datos);

    return redirect()->route('books.reseña',$reseña->id)->with('message', 'Reseña actualizado con éxito');
}

 public function informe()
{
    $puntuaciones = Lectura::select('puntuacion', DB::raw('count(*) as total'))
        ->whereNotNull('puntuacion')
        ->groupBy('puntuacion')
        ->pluck('total', 'puntuacion'); 

    $estados = Lectura::select('estado', DB::raw('count(*) as total'))
        ->groupBy('estado')
        ->pluck('total', 'estado');

    
 
    return Inertia::render('books/informe', [
        'stats' => [
            'estrellas' => [
                'cinco'  => $puntuaciones->get(5, 0),
                'cuatro' => $puntuaciones->get(4, 0),
                'tres'   => $puntuaciones->get(3, 0),
                'dos'    => $puntuaciones->get(2, 0),
                'uno'    => $puntuaciones->get(1, 0),
                'cero'   => $puntuaciones->get(0, 0),
            ],
            'estados' => [
                'terminados' => $estados->get('Terminado', 0),
                'leyendo'    => $estados->get('Leyendo', 0),
                'pendiente'  => $estados->get('Pendiente', 0),
                'abandonados'  => $estados->get('Abandonado', 0),
            ],
            'total_libros' => Libro::count()
        ]
        ]);
}



}

