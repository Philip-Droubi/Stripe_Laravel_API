<?php

namespace App\Http\Requests;

use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    use ApiResponser;
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
        return match ($this->route()->getActionMethod()) {
            "store" => $this->storeRules(),
            "update" => $this->updateRules(),
        };
    }

    public function storeRules()
    {
        return [
            "name" => ["required", "max:255"],
            "price" => ["required", "numeric", "between:1,999999"],
            "amount" => ["required", "integer", "between:1,9999"],
        ];
    }

    public function updateRules()
    {
        return [
            "name" => ["required", "max:255"],
            "price" => ["required", "numeric", "between:1,999999"],
            "amount" => ["required", "integer", "between:1,9999"],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->fail($validator->errors()->first()));
    }

    public function messages()
    {
        return [];
    }
}
