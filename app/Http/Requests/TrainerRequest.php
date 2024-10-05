<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TrainerRequest extends FormRequest
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
                    'trainer_name' => 'required|string|min:3|max:50',
                    'trainer_email' => 'required|email|unique:trainers,trainer_email',
                    'CNIC' => 'required|string',// we have to add unique constraint and 13 characters constraint in CNIC but in the end.
                    'age' => 'required|integer|max:50',
                    'gender' => 'required|string|in:male,female',
                    'DOB' => 'required|date|after_or_equal:2010-01-01',
                    'phone_number' => 'required|string|min:10|max:15|unique:trainers,phone_number',
                    'trainer_profile_image' => 'string|unique:trainers',
                    'trainer_address' => 'required|string|min:10|max:100',
                    'experience' => 'required|integer|min:1|max:20',
                    'salary' => 'required|numeric|min:1000',
                    'hourly_rate' => 'required|numeric|min:500',
                ];

            case 'PUT':

                $trainerId = $this->route('trainerId'); // Assuming the trainer ID is passed in the route

                return [
                    'trainer_name' => 'string|min:3|max:50',
                    'trainer_email' => 'email|unique:trainers,trainer_email' . $trainerId, // trainerId is concatenated so that unique values are check by ignoring the current trainerId
                    'CNIC' => 'string',// we have to add unique constraint and 13 characters constraint in CNIC but in the end.
                    'age' => 'integer|max:50',
                    'gender' => 'string|in:male,female',
                    'DOB' => 'date|after_or_equal:2010-01-01',
                    'phone_number' => 'string|min:10|max:15|unique:trainers,phone_number',
                    'trainer_profile_image' => 'string|unique:trainers',
                    'trainer_address' => 'string|min:10|max:100',
                    'experience' => 'integer|min:1|max:20',
                    'salary' => 'numeric|min:1000',
                    'hourly_rate' => 'numeric|min:500',
                ];

            default:
                return []; // No validation rules for other HTTP methods (GET, PATCH, DELETE)

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
