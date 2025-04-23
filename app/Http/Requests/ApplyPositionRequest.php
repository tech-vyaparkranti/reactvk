<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplyPositionRequest extends FormRequest
{
    use ResponseAPI;

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
            "name"=>"required|string|max:50",
            "position_analytics" => "required|string",
            "department"=>"required|string|max:50",
            "email"=>"required|email|max:100",
            "phone"=>"required|integer",
            "resume"=>"required|file",
            "cover_letter"=>"required|string",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),422));
    }
}
