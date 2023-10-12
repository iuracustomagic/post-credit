<?php

namespace App\Http\Requests\User;

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
            'status' => 'nullable|integer',
            'login' => 'required|min:3|string',
            'email' => 'required|email|unique:users,email,$this->id,id',
            'password' => 'required|min:6|string',
            'phone' => 'required|min:6|string',
            'role_id' => 'nullable|integer',
            'manager_id' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'ref_number' => 'nullable|string',
            'credit_1' => 'nullable',
            'credit_2' => 'nullable',
            'credit_3' => 'nullable',
            'credit_4' => 'nullable',
            'credit_15' => 'nullable',
            'sms' => 'nullable',
            'referral' => 'nullable',
        ];
    }
}
