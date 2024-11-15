<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class MembershipRequest extends FormRequest
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

        // Define validation rules based on the HTTP method
        switch ($method) {
            case 'GET':
                return [
                    'membership_id' => 'required|exists:memberships,membership_id',
                ];
            case 'POST':
                return [
                    'membership_type' => 'required|unique:memberships,membership_type',
                ];
            case 'PUT':
                $membershipId = $this->route('membershipId'); // Retrieve the membershipId from the route
                return [
                    'membership_type' => 'required|unique:memberships,membership_type,' . $membershipId . ',membership_id',
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
