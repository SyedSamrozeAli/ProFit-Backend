<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminAuthRequest extends FormRequest
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
        $routeName = $this->route()->getName();
        if ($routeName == 'login') {

            return [
                'email' => 'required|string|email|exists:admin,email',
                'password' => 'required|string',
                // 'recaptchaToken' => 'required|string',
            ];
        } else if ($routeName == 'forgotPassword') {
            return [
                'email' => 'required|string|email|exists:admin,email',
            ];

        } else if ($routeName == 'resetPassword') {
            return [
                'token' => 'required|string|exists:password_reset_tokens,token',
                'password' => 'required|string|confirmed',
                'password_confirmation' => 'required',
            ];

        } else {
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
