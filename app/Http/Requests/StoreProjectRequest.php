<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'content' => ['required', 'array'],
            'content.tables' => ['present', 'array'],
            'content.relationships' => ['present', 'array'],
            'content.notes' => ['present', 'array'],
            'content.subjectAreas' => ['present', 'array'],
            'content.database' => ['nullable', 'string', 'in:mysql,postgresql,transactsql,sqlite,mariadb,oraclesql,generic'],
        ];
    }
}
