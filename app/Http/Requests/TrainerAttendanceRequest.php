<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class TrainerAttendanceRequest extends FormRequest
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

            case 'GET':
                return [
                    'attendance_date' => 'date'
                ];

            case 'POST':

                return [
                    'attendance_date' => 'required|date',
                    'attendance' => 'required|array',
                    'attendance.*.trainer_id' => 'required|exists:trainers,trainer_id',
                    'attendance.*.check_in_time' => 'required_if:attendance.*.attendance_status,Present|nullable|date_format:H:i:s',
                    'attendance.*.check_out_time' => 'required_if:attendance.*.attendance_status,Present|nullable|date_format:H:i:s|after:attendance.*.check_in_time',
                    'attendance.*.attendance_status' => 'required|in:Present,Absent',
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

    public function messages()
    {
        return [
            'attendance_date.exists' => "Attendance for this date is not recorded",
            'attendance.*.trainer_id.exists' => 'Cannot update as attendance of some trainer has not been recorded'
        ];
    }
}
