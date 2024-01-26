<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreMfoRequest extends FormRequest
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
//            'email' => 'required',
            'first_name' => 'required|min:2|string',
            'last_name' => 'required|min:2|string',
            'surname' => 'required|min:2|string',
            'salesman_id' => 'required|integer',
            'birthday' => 'nullable',
            'phone' => 'required|min:3|string',
            'term_credit' => 'nullable|integer',
            'sum_credit' => 'required',
            'company_id' => 'required',
            'division_id' => 'required',
            'date_sent' => 'nullable',
            'plan_term' => 'nullable|integer',
            'initial_fee' => 'nullable|integer',
            'find_credit' => 'nullable',
            'transfer_sum' => 'nullable',
            'password_id' => 'required|string',
            'password_code' => 'required|string',
            'password_date' => 'required',
            'password_by' => 'required|string',
            'address_index' => 'nullable|string',
            'address_region' => 'nullable|string',
            'address_city' => 'nullable|string',
            'address_street' => 'nullable|string',
            'address_house' => 'nullable|string',
            'address_block' => 'nullable|string',
            'address_flat' => 'nullable|string',
            'residence' => 'required|integer',
            'doc_set' => 'required|integer',

            'sms_code' => 'required|string',
            'birth_place' => 'required|string',
            'income_amount' => 'required|integer',



        ];
    }
}
