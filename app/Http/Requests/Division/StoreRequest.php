<?php

namespace App\Http\Requests\Division;

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
            'status' => 'required|integer',
            'name' => 'required|min:3|string',
            'address' => 'required|min:3|string',
            'shop_id' => 'required|min:35|string',
            'show_case_id' => 'required|min:35|string',
            'created_by' => 'required|integer',
            'type' => 'required|integer',
            'company_id' => 'required|string',
            'rate_id' => 'required|integer',
            'plan_id' => 'required|integer',
            'installments' => 'nullable|array',
            'price_sms' => 'required|integer',
            'price_sms_mfo' => 'required|integer',
            'find_credit' => 'nullable',
            'hide_find_credit' => 'nullable',
            'find_credit_value' => 'nullable|string',
            'find_mfo' => 'nullable',
            'hide_find_mfo' => 'nullable',
            'find_mfo_value' => 'nullable|string',
            'rate_if_off' => 'required|integer',
            'segment_id' => 'nullable',

        ];
    }
}
