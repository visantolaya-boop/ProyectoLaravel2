<?php

namespace App\Http\Controllers;

use Inertia\Inertia;


use App\Http\Controllers\Controller;
use App\Models\Lectura;
use App\Models\Libro;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::whereHas('ubicacion', function ($q) {
            $q->where('user_id', auth()->id());
        });

        return Inertia::render('books/libros', [
            'libros' => $libros
        ]);
    }

    public function buscar(Request $request)
    {
        $libroQuery = Libro::whereHas('ubicacion', function ($q) {
            $q->where('user_id', auth()->id());
        });


        $this->realizarBusqueda($libroQuery, $request);
        $libroQuery->with('lecturas');
        $libroQuery->with('ubicacion');
        $this->filtrosBusqueda($libroQuery, $request);
        // Ejecutamos la paginación
        $libros = $libroQuery->paginate(10)->appends(request()->query());
        $ubicaciones = Ubicacion::where('user_id', auth()->id())->get();

        return Inertia::render('books/buscar', [
            'libros'  => $libros,
            'ubicaciones' => $ubicaciones,
        ]);
    }

    protected function realizarBusqueda($query, $request)
    {
        $query->when($request->search, function ($subq, $search) {
            $subq->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('autor', 'like', "%{$search}%")
                    ->orWhere('editorial', 'like', "%{$search}%")
                    ->orWhere('genero', 'like', "%{$search}%")
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
    }

    public function editar($id)
    {
        $libro = Libro::whereHas('ubicacion', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        $ubicaciones = Ubicacion::where('user_id', auth()->id())->get();
        return Inertia::render('books/form/libroFormulario', [
            'libro' => $libro,
            'ubicaciones' => $ubicaciones,
            'isUpdating' => true
        ]);
    }

    public function update(Request $request, $id)
    {
        $libro = Libro::whereHas('ubicacion', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $datos = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor'  => 'required|string|max:255',
            'anio'   => 'required|integer|digits:4',
            'editorial' => 'string|required',
            'paginas' => 'integer|required',
            'genero' => 'string|required',
            'ubicacion_id' => 'required',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

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

        return Inertia::render('books/form/libroFormulario', [
            'libro' => [
                'titulo' => '',
                'autor' => '',
                'anio' => '',
                'editorial' => '',
                'paginas' => '',
                'genero' => '',
                'ubicacion_id' => null,
                'portada' => null,


            ],
            'ubicaciones' => $ubicaciones,
            'isUpdating' => false
        ]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo'    => 'required|string|max:255',
            'autor'     => 'required|string|max:255',
            'anio'      => 'required|integer|digits:4',
            'editorial' => 'required|string',
            'paginas'   => 'required|integer',
            'genero'    => 'required|string',
            'ubicacion_id' => 'required',
            'portada'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

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
