<?php

namespace App\Http\Requests;

use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactUsRequest extends FormRequest
{
    use ResponseAPI;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name"=>"required|string|max:50",
            // "last_name"=>"required|string|max:50",
            "subject"=>"required|string|max:50",
            "email"=>"required|email|max:100",
            "phone"=>"required|integer",
            "message"=>"required|string",
            // "captcha"=>"required|captcha",
            // "country_code"=>"nullable|string|max:10"
        ];
    }

    public function messages()
    {
        return [
           
            "name.required" => "Full Name is required.",
            "name.string" => "Full Name should be a string.",
            "name.max" => "Full Name cannot exceed 100 characters.",
            "email.required" => "Email is required.",
            "email.email" => "Email must be a valid email address.",
            "phone.numeric" => "Phone Number must be numeric.",
            "phone.digits_between" => "Phone Number must be between 9 to 20 digits.",
            "message.max" => "Message cannot exceed 1000 characters.",
            
        ];
    }
    /**
    * Get the error messages for the defined validation rules.*
    * @return array
    */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error($validator->getMessageBag()->first(),422));
    }
}
