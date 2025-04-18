<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VacancyRequest extends FormRequest
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
                'id' => 'bail|required_if:action,update,enable,disable|nullable',
                'title' => "bail|required_if:action,update,insert|nullable|string|max:500",
                'department' => "bail|required_if:action,update,insert|nullable|string",
                'location' => "bail|required_if:action,update,insert|nullable|string",
                'job_type' => "bail|required_if:action,update,insert|nullable|string",
                'description' => "bail|required_if:action,update,insert|nullable|string",
                'requirement' => "bail|required_if:action,update,insert|nullable|string",
                'benefits' => "bail|required_if:action,update,insert|nullable|string",
                'status' => "bail|required_if:action,update,insert|nullable|string",
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
                'data' => null,
            ], 422)
        );
    }
}
