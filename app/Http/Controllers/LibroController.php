<?php

namespace App\Http\Controllers;

use Inertia\Inertia;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLibroRequest;
use App\Models\Genero;
use App\Models\Lectura;
use App\Models\Libro;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibroController extends Controller
{

    public function buscar(Request $request)
    {
        $libroQuery = Libro::whereHas('ubicacion', function ($q) {
            $q->where('user_id', auth()->id());
        });


        $this->realizarBusqueda($libroQuery, $request);
        $libroQuery->with('lecturas');
        $libroQuery->with('ubicacion');
        $libroQuery->with('genero');

        $this->filtrosBusqueda($libroQuery, $request);
        // Ejecutamos la paginación
        $libros = $libroQuery->paginate(10)->appends(request()->query());
        $ubicaciones = Ubicacion::where('user_id', auth()->id())->get();
        $generos = Genero::where('user_id', auth()->id())->get();

        return Inertia::render('books/buscar', [
            'libros'  => $libros,
            'ubicaciones' => $ubicaciones,
            'generos' => $generos,
        ]);
    }

    protected function realizarBusqueda($query, $request)
    {
        $query->when($request->search, function ($subq, $search) {
            $subq->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('autor', 'like', "%{$search}%")
                    ->orWhere('editorial', 'like', "%{$search}%")
                    ->orWhere('anio', 'like', "%{$search}%");
            });
        });
    }

    protected function filtrosBusqueda($query, $request)
    {

        if ($request->has('puntuacion') && $request->puntuacion !== null && $request->puntuacion !== '') {
            $query->whereHas('lecturas', function ($subq) use ($request) {
                $subq->where('puntuacion', '=', (int)$request->puntuacion);
            });
        }

        $query->when($request->estado, function ($subq, $search) {
            $subq->whereHas('lecturas', function ($q) use ($search) {
                $q->where('estado', 'like', $search);
            });
        });

        $query->when($request->ubicacion, function ($subq, $search) {
            $subq->whereHas('ubicacion', function ($q) use ($search) {
                $q->where('nombre', 'like', $search);
            });
        });

        $query->when($request->genero, function ($subq, $search) {
            $subq->whereHas('genero', function ($q) use ($search) {
                $q->where('nombre', 'like', $search);
            });
        });
    }

    public function editar($id)
    {
        $libro = Libro::whereHas('ubicacion', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        $ubicaciones = Ubicacion::where('user_id', auth()->id())->get();
        $generos = Genero::where('user_id', auth()->id())->get();

        return Inertia::render('books/form/libroFormulario', [
            'libro' => $libro,
            'ubicaciones' => $ubicaciones,
            'generos' => $generos,
            'isUpdating' => true
        ]);
    }

    public function update(StoreLibroRequest $request, $id)
    {
        $libro = Libro::whereHas('ubicacion', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $datos = $request->validated();

        //Logica de la imagen
        if ($request->hasFile('portada')) {
            if ($libro->portada) {
                Storage::disk('public')->delete($libro->portada);
            }

            $datos['portada'] = $request->file('portada')->store('portada', 'public');
        } else {
            unset($datos['portada']);
        }


        $libro->update($datos);

        return redirect()->route('books.buscar')->with('message', 'Libro actualizado con éxito');
    }

    public function destroy($id)
    {
        $libro = Libro::whereHas('ubicacion', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $libro->delete();

        return redirect()->route('books.buscar')->with('message', 'Libro eliminado correctamente');
    }


    public function create()
    {
        $ubicaciones = Ubicacion::where('user_id', auth()->id())->get();
        $generos = Genero::where('user_id', auth()-> id())-> get();
        return Inertia::render('books/form/libroFormulario', [
            'libro' => [
                'titulo' => '',
                'autor' => '',
                'anio' => '',
                'editorial' => '',
                'paginas' => '',
                'genero_id' => null,
                'ubicacion_id' => null,
                'portada' => null,


            ],
            'generos' => $generos,
            'ubicaciones' => $ubicaciones,
            'isUpdating' => false
        ]);
    }

    public function store(StoreLibroRequest $request)
    {
        $datos = $request->validated();

        if ($request->hasFile('portada')) {
            $datos['portada'] = $request->file('portada')->store('portada', 'public');
        }
        $libro = Libro::create($datos);

        Lectura::create([
            'id_libro'       => $libro->id,
            'estado'         => "Pendiente",
            'puntuacion'     => 0,
            'final_lectura'  => null,
            'inicio_lectura' => now()->format('Y-m-d'),
            'tiempo_lectura' => 0,
            'reseña'         => null,
        ]);

        return redirect()->route('books.buscar')->with('message', 'Libro creado con éxito');
    }
}
