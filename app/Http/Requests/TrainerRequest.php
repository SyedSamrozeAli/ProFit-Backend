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

        // Define validation rules based on the HTTP method
        switch ($method) {

            // Validations for Creating a new trainer
            case 'POST':
                $trainerId = $this->route('trainerId'); // Assuming the trainer ID is passed in the route
                return [
                    'trainer_name' => 'required|string|min:3|max:50',
                    'trainer_email' => 'required|email|unique:trainers,trainer_email',
                    'CNIC' => ['required', 'string', 'regex:/^[0-9]{13}$/', 'unique:trainers,CNIC'], 
                    'gender' => 'required|string|in:male,female',
                    'DOB' => 'required|date|before_or_equal:2010-01-01',
                    'phone_number' => 'required|string|min:11|max:11|unique:trainers,phone_number',
                    'trainer_profile_image' => 'nullable',
                    'trainer_address' => 'required|string|min:10|max:255',
                    'experience' => 'required|integer|min:1|max:100',
                    'salary' => 'required|numeric|min:1000',
                    'rating' => 'required|numeric|min:0|max:5'
                ];

            // Validations for updating a trainer    
            case 'PUT':

                $trainerId = $this->route('trainerId'); 

                return [
                    'trainer_name' => 'string|min:3|max:50',
                    'trainer_email' => ['email', 'unique:trainers,trainer_email,' . $trainerId . ',trainer_id'], 
                    'CNIC' => ['string', 'regex:/^[0-9]{13}$/', 'unique:trainers,CNIC,' . $trainerId . ',id'], 
                    'gender' => 'string|in:male,female',
                    'DOB' => 'date|before_or_equal:2010-01-01',
                    'phone_number' => 'string|min:10|max:15|unique:trainers,phone_number',
                    'trainer_profile_image' => 'nullable',
                    'trainer_address' => 'string|min:10|max:255',
                    'experience' => 'integer|min:1|max:20',
                    'salary' => 'numeric|min:1000',
                    'rating' => 'numeric|min:0|max:5'
                ];

            // Validations when getting filtered data for trainers    
            case 'GET':

                $routeName = $this->route()->getName();
                if ($routeName == 'getFilteredData') {

                    return [
                        // Filter validations
                        'name' => 'nullable|string|max:255',
                        'email' => 'nullable|email|max:255',
                        'CNIC' => 'nullable|string|regex:/^[0-9]{13}$/', // CNIC with 13 digits
                        'gender' => 'nullable|in:Male,Female,Other',
                        'maxAge' => 'nullable|integer|min:1|max:100',
                        'minAge' => 'nullable|integer|min:1|max:100|lte:maxAge', // minAge must be <= maxAge
                        'minSalary' => 'nullable|numeric|min:0',
                        'maxSalary' => 'nullable|numeric|min:0|gte:minSalary', // maxSalary must be >= minSalary
                        'availability' => 'nullable|boolean', // For true/false availability status
                        'maxExperience' => 'nullable|integer|min:0',
                        'minExperience' => 'nullable|integer|min:0|lte:maxExperience', // minExperience must be <= maxExperience
                        'startHireDate' => 'nullable|date|before_or_equal:endHireDate',
                        'endHireDate' => 'nullable|date|after_or_equal:startHireDate',
                        'minRating' => 'nullable|numeric|min:0|max:5', // Assuming rating is on a 0-5 scale
                        'maxRating' => 'nullable|numeric|min:0|max:5|gte:minRating', // maxRating must be >= minRating

                        // Sorting and ordering validations
                        'orderByName' => 'nullable|in:asc,desc',
                        'orderBySalary' => 'nullable|in:asc,desc',
                        'orderByHireDate' => 'nullable|in:asc,desc',
                        'orderByRating' => 'nullable|in:asc,desc',
                    ];

                } else {
                    return [];
                }

            default:
                return []; // No validation rules for other HTTP methods (PATCH, DELETE)

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
