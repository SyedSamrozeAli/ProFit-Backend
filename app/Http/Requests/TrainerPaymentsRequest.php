<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class TrainerPaymentsRequest extends FormRequest
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
                    'trainer_id' => 'required|exists:trainers,trainer_id',
                    'payment_date' => 'required|date',
                    'salary' => 'required|numeric|min:0',
                    'payment_amount' => [
                        'required',
                        'numeric',
                        'min:0',
                        function ($attribute, $value, $fail) {
                            if ($value !== $this->input('salary')) {
                                $fail("The $attribute must be equal to the salary.");
                            }
                        }
                    ],
                    'payment_method' => 'required|in:cash,online',
                    'paid_amount' => 'required|numeric|min:0',
                    'payment_reciept' => 'nullable|file|mimes:png,jpg,pdf'
                ];
            case 'GET':

                return [
                    'month' => 'required|integer|min:1|max:12',
                    'year' => 'required|integer|min:2010|max:2400',
                    'trainerId' => 'nullable|exists:trainers_payments,trainer_id',
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
