<?php

namespace App\Http\Requests;

use App\Helpertraites\ResponseStatusCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateProductRequest extends FormRequest
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
            
            "images.*" => "required|image",
            "name" => "required|string", 
            "price" => "required|numeric", 
            "description" => "required|string", 
            "color" => "required|string",
            "ram" => "required|string|json",
            "contete" => "required|numeric",
            "admin_id" => "required|numeric",
            "catigory_id" => "required|numeric", 
            
        ];
    }

    public function failedValidation(Validator $validator){
      
       $errors = $validator->errors();
       throw new HttpResponseException(response()->json([
        "state" => "error",
        "message" => "you must implemnt all field",
        "error-code" => Response::HTTP_BAD_REQUEST,
        "errors" => $errors,
    ]));
    }
}
