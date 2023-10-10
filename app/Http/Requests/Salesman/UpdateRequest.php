<?php

namespace App\Http\Requests\Salesman;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|min:3|string',
            'last_name' => 'required|min:3|string',
            'surname' => 'required|min:3|string',
            'status' => 'nullable|integer',
            'login' => 'required|min:3|string',
            'phone' => 'required|min:6|string',
            'password' => 'nullable',
            'role_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'division_id' => 'nullable|integer',

        ];
    }
}
