<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLecturaRequest extends FormRequest
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
        'estado'         => 'required|string',
        'puntuacion'     => 'required|integer',
        'inicio_lectura' => 'required|date|before_or_equal:today',
        'final_lectura'  => 'nullable|date|after_or_equal:inicio_lectura|before_or_equal:today',
        'tiempo_lectura' => 'nullable|integer|min:0', 
        'reseña'         => 'nullable|string|max:255',
    ];
}

public function messages()
{
    return [
        'inicio_lectura.required'        => 'Dinos cuándo empezaste a leer.',
        'inicio_lectura.before_or_equal' => 'La fecha de inicio no puede ser futura.',
        
        'final_lectura.after_or_equal'   => 'La fecha de fin no puede ser anterior al inicio.',
        'final_lectura.before_or_equal'  => 'No puedes terminar un libro en el futuro.',
        
        'tiempo_lectura.min'             => 'El tiempo de lectura no puede ser negativo.',
        'tiempo_lectura.integer'         => 'El tiempo debe ser un número de días entero.',

        'puntuacion.required'            => '¡No olvides valorar el libro!',
        'estado.required'                => 'Selecciona si estás leyendo, terminado o abandonado.',
    ];
}
}
