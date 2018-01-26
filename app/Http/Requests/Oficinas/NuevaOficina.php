<?php

namespace App\Http\Requests\Oficinas;

use App\Http\Requests\Request;

class NuevaOficina extends Request
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
            'nombre'        => 'string|required',
            'telefono'        => 'string|required',
            'direccion'        => 'string|required',
            'email'        => 'string|required',
            'ciudad_id' => 'numeric|required|exists:ciudades,id',
        ];
    }
}