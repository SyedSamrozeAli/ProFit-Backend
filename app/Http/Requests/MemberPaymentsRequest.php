<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class MemberPaymentsRequest extends FormRequest
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
                    'member_id' => 'required|exists:members,member_id',
                    'membership_type' => 'required|exists:memberships,membership_type',
                    'payment_date' => 'required|date',
                    'payment_amount' => 'required|numeric|min:0',
                    'payment_method' => 'required|in:cash,online',
                    'paid_amount' => 'required|numeric|min:0',
                    'payment_reciept' => 'nullable|file|mimes:png,jpg,pdf'
                ];

            case 'GET':

                return [
                    'month' => 'required|integer|min:1|max:12',
                    'year' => 'required|integer|min:2010|max:2400',
                    'memberId' => 'nullable|exists:members_payments,member_id',
                ];

            default:
                return [];
        }

    }

    protected function failedValidation(Validator $validator)
    {
        // Get all error messages without field keys
        $errorMessages = $validator->errors()->all();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'message' => 'Validation errors occurred.',
            'errors' => $errorMessages, // Returning only the list of error messages
            'data' => []
        ], 422));
    }
}
