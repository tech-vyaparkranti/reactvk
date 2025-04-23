<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PackageRequest extends FormRequest
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
            'title'=>"bail|required_if:action,update,insert|nullable|string|max:500",
            'category'=>"bail|required_if:action,update,insert|nullable",
            'package_class'=>"bail|required_if:action,update,insert|nullable",
            'package_details'=>"bail|required_if:action,update,insert|nullable",
            'price'=>"bail|required_if:action,update,insert|nullable|integer",
            "action"=>"bail|required|in:insert,update,enable,disable",
            'status'=>"bail|required_if:action,update,insert|nullable",
            'position' => [
                    'bail',
                    'required_if:action,update,insert',
                    'nullable',
                    'numeric',
                    Rule::unique('packages', 'position')->ignore($id),
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
