<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TeamRequest extends FormRequest
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
        $id = $this->input('id');   
        return [
                'id' => 'bail|required_if:action,update,enable,disable|nullable',
                'image' => "bail|required_if:action,insert|nullable|image|max:2048",
                'name' => "bail|required_if:action,update,insert|nullable|string|max:500",
                'designation' => "bail|required_if:action,update,insert|nullable|string",
                'position' => [
                    'bail',
                    'required_if:action,update,insert',
                    'nullable',
                    'numeric',
                    Rule::unique('team_infos', 'position')->ignore($id),
                ],
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
