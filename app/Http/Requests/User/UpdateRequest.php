<?php

namespace App\Http\Requests\User;

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
            'phone' => 'required|min:6|string',
            'manager_id' => 'nullable|integer',
            'password' => 'nullable',
            'credit_1' => 'nullable',
            'credit_2' => 'nullable',
            'credit_3' => 'nullable',
            'credit_4' => 'nullable',
            'sms' => 'nullable',
            'referral' => 'nullable',
        ];
    }
}
