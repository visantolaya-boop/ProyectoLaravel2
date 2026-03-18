<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titulo' => 'required|string|max:255',
            'autor'  => 'required|string|max:255',
            'anio'   => 'required|integer|digits:4',
            'editorial' => 'string|required',
            'paginas' => 'integer|required|min:1',
            'genero_id' => 'required',
            'ubicacion_id' => 'required',
            'portada' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ];
    }

public function messages()
{
    return [
        'titulo.required' => 'El título del libro es obligatorio.',
        'titulo.max'      => 'El título no puede tener más de 255 caracteres.',

        'autor.required'  => 'Debes indicar quién es el autor.',
        'autor.max'       => 'El nombre del autor es demasiado largo.',

        'anio.required'   => 'El año de publicación es necesario.',
        'anio.integer'    => 'El año debe ser un número.',
        'anio.digits'     => 'El año debe tener exactamente 4 dígitos (ej: 1995).',

        'editorial.required' => 'La editorial es obligatoria.',

        'paginas.required' => 'Indica el número de páginas.',
        'paginas.integer'  => 'El número de páginas debe ser un valor numérico.',
        'paginas.min' => 'El numero debe ser superior a 1',

        'portada.image' => 'El archivo debe ser una imagen.',
        'portada.mimes' => 'La portada debe estar en formato: jpg, jpeg, png o webp.',
        'portada.max'   => 'La imagen es muy pesada. El máximo permitido son 2MB.',
    ];
}
}
