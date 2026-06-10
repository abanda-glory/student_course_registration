<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'title' => 'sometimes|required|string|unique:courses,title,' . $id . '|max:255',
            'code' => 'sometimes|required|string|unique:courses,code,' . $id . '|max:100',
            'description' => 'sometimes|required|string',
            'credit_hours' => 'sometimes|required|integer|min:1'
        ];
    }
}
