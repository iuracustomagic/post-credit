<?php

namespace App\Http\Requests\Order;

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
            'status' => 'required|string',
            'rate' => 'required',
            'items' => 'required|string',
            'email' => 'required',
            'first_name' => 'required|min:3|string',
            'last_name' => 'required|min:3|string',
            'surname' => 'required|min:3|string',
            'salesman_id' => 'required|integer',
            'birthday' => 'nullable',
            'phone' => 'required|min:3|string',
            'term_credit' => 'required|integer',
            'sum_credit' => 'required',
            'company_id' => 'required',
            'division_id' => 'required',
            'credit_type' => 'required|integer',

        ];
    }
}
