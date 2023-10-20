<?php

namespace App\Http\Requests\Division;

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
            'status' => 'required|integer',
            'name' => 'required|min:3|string',
            'address' => 'required|min:3|string',
            'shop_id' => 'required|string',
            'show_case_id' => 'required|string',
            'images' => 'nullable',
            'type' => 'required|integer',
            'company_id' => 'required|string',
            'rate_id' => 'required|integer',
            'plan_id' => 'nullable|integer',
            'installments' => 'nullable|array',
            'price_sms' => 'required|integer',
            'find_credit' => 'nullable',
            'find_credit_value' => 'nullable|string',
        ];
    }
}
