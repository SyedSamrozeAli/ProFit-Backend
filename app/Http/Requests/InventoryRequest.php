<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class InventoryRequest extends FormRequest
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
        // Get the current HTTP method
        $method = $this->method();

        switch ($method) {

            case 'POST':
                return [

                    'item_name' => 'string',
                    'description' => 'string',
                    'quantity' => 'integer',
                    'cost_per_unit' => 'numeric',
                    'supplier_name' => 'string',
                    'purchase_date' => 'date',
                    'category' => 'in:Accessories,Cardio Equipment,Free Weights,Resistance Machine',
                    'warranty_period' => 'integer',
                ];

            case 'GET':
                return [];

            case 'PUT':
                return [];

            default;
                return [];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        // Get all error messages without field keys
        $errorMessages = $validator->errors()->all(); // This will return a simple array of error messages

        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'message' => 'Validation errors occurred.',
            'errors' => $errorMessages, // Returning only the list of error messages
            'data' => []
        ], 422));
    }
}
