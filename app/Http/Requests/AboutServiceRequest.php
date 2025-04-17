<?php

namespace App\Http\Requests;

use App\Models\AboutService;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AboutServiceRequest extends FormRequest
{
    use ResponseAPI;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->id?true:false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            AboutService::ID=>"bail|required_if:action,update,enable,disable|nullable|exists:about_service,id",
            AboutService::SERVICE_NAME=>"bail|required_if:action,update,insert|nullable|string|max:500",
            AboutService::SERVICE_DETAILS=>"bail|nullable|string",
            AboutService::SERVICE_IMAGE=>"bail|required_if:action,insert|nullable|image|max:2048",
            AboutService::POSITION=>"required_if:action,update,insert|numeric|gt:0",
            "action"=>"bail|required|in:insert,update,enable,disable"
        ];
    }

    public function messages()
    {
        return [
            "position.required_if"=>"Sorting number is required.",
            "position.strnumericing"=>"Sorting number should be a number.",
            "position.gt"=>"Sorting number should be greater than 0.",

        ];
    }

    /**
    * Get the error messages for the defined validation rules.*
    * @return array
    */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),200));
    }
}
