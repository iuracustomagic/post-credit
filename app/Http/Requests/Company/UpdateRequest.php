<?php

namespace App\Http\Requests\Company;

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
            'email' => 'required|email|string',
            'password' => 'nullable|string',
            'phone' => 'required|min:6|string',
            'number' => 'required|min:6|string',
            'by' => 'required|min:2|string',
            'date' => 'required|date',
            'registration' => 'required|min:2|string',
            'name'=>'required|min:2|string',
            'inn'=>'required|min:2|string',
            'ogrn'=>'required|min:2|string',
            'address'=>'required|min:2|string',
            'checking_account'=>'required|min:2|string',
            'bank_name'=>'required|min:2|string',
            'correspond_account'=>'required|min:2|string',
            'bik'=>'required|min:2|string',
            'images'=>'nullable',
        ];
    }
}
