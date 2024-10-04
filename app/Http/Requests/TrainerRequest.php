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
