<?php

namespace Src\Factura\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado' => 'required|string|in:emitida,pagada,anulada',
            'observaciones' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'estado' => 'estado',
            'observaciones' => 'observaciones',
        ];
    }

    public function messages(): array
    {
        return [
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser emitida, pagada o anulada',
            'observaciones.max' => 'Las observaciones no pueden exceder los 1000 caracteres',
        ];
    }
}
