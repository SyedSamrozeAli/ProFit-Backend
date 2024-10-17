<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MemberRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to false if you want to handle authorization differently
    }

    public function rules()
    {
        // Get the current HTTP method
        $method = $this->method();

        // Define validation rules based on the HTTP method
        switch ($method) {

            case 'POST':

                return [
                    'name' => 'required|string|max:255',
                    'member_email' => 'required|email|max:255|unique:members,member_email',
                    'CNIC' => 'required|string|min:13|max:13|unique:members,CNIC',
                    'age' => 'required|integer|min:15',
                    'weight' => 'required|numeric|min:25',
                    'height' => 'required|numeric|min:0',
                    'address' => 'required|string|max:255',
                    'gender' => 'required|in:male,female',
                    'health_issues' => 'nullable|string|max:255',
                    'phone_number' => 'required|string|max:15',
                    'DOB' => 'required|date|before:today',
                    'profile_image' => 'nullable|string|max:255',
                    'membership_type' => 'required|in:standard,premium',
                    'trainer_id' => 'nullable|integer|exists:trainers,trainer_id|required_if:membership_type,premium|prohibited_if:membership_type,standard',
                    'addmission_date' => 'required|date',
                    'membership_duration' => 'required|integer|in:3,6,12'
                ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Member name is required',
            'member_email.required' => 'Email is required',
            'age.required' => 'Age is required',
            'weight.required' => 'Weight is required',
            'height.required' => 'Height is required',
            'address.required' => 'Address is required',
            'gender.required' => 'Gender is required',
            'DOB.before' => 'Date of birth must be before today',
            'trainer_id.exists' => 'Trainer does not exist',
            'trainer_id.required_if' => 'Must select trainer for Premium Package',
            'trainer_id.prohibited_if' => 'Trainer cannot be selected for Standard Package'
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
