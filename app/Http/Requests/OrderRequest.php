<?php

namespace App\Http\Requests;

use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
        };
    }

    public function storeRules()
    {
        return [
            "products" => ["required", "array", "min:1", "max:20"],
            "products.*.id" => ["required", Rule::exists('products', 'id')->whereNot('amount', 0)],
            "products.*.amount" => ["required", 'integer', "between:1,99"],
        ];
    }

    public function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException($this->fail($validator->errors()->first()));
    }

    public function messages(): array
    {
        return [];
    }
}
