<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OtherExpensePaymentsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();


        switch ($method) {

            case 'POST':
                return [
                    'expense_category' => 'required|exists:expense_categories,expense_category_name',
                    'amount' => 'required|numeric',
                    'expense_date' => 'required|date',
                    'expense_status' => 'required|exists:payment_status,status_name',
                    'payment_method' => 'required|in:cash,online',
                    'payment_reciept' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ];
            case 'GET':

                return [
                    'expenseId' => 'nullable|exists:expense,expense_id',
                    'month' => 'nullable|integer|min:1|max:12',
                    'year' => 'nullable|integer|min:2010|max:2400',
                ];
            default:
                return [];
        }


    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'message' => $validator->errors(),
            'data' => []
        ], 422));
    }
}