<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'status' => 'nullable|string',
            'login' => 'required|min:3|string',
            'email' => 'required|email|unique:users,email,$this->id,id',
            'password' => 'required|min:6|string',

            'phone' => 'required|min:6|string',
            'role_id' => 'nullable|integer',
//            'password_id' => 'required|integer',
            'number' => 'required|min:6|string',
            'by' => 'required|min:2|string',
            'date' => 'required|date',
            'registration' => 'required|min:2|string',
            'created_by' => 'required|integer',
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
