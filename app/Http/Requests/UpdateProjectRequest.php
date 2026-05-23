<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'tables' => ['present', 'array'],
            'relationships' => ['present', 'array'],
            'notes' => ['present', 'array'],
            'subjectAreas' => ['present', 'array'],
            'database' => ['nullable', 'string', 'in:mysql,postgresql,transactsql,sqlite,mariadb,oraclesql,generic'],
        ];
    }
}
