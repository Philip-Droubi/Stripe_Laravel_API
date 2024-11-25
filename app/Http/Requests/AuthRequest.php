<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class AuthRequest extends FormRequest
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
            'register' =>  $this->registerRules(),
            'login'   =>  $this->loginRules(),
        };
    }

    public function registerRules(): array
    {
        return [
            "name" => ["required", "string", "between:2,80"],
            "password" => [
                "required",
                "string",
                "max:255",
                Password::min(8)
                    ->letters()
                    ->numbers(),
                "confirmed"
            ],
            "email" => ["required", "string", "email", "unique:users,email"],
        ];
    }

    public function loginRules(): array
    {
        return [
            "email" => ["required", "string", "exists:users,email"],
            "password" => ["required", "string", "max:255"],
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
