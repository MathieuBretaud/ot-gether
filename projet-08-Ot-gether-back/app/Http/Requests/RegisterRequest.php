<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'pseudo' => 'required|unique:users,pseudo',
            'lastname' => 'required',
            'firstname' => 'required',
            'city' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'picture' => 'required',
            'is_admin' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Un email est requis',
            'email.unique' => 'Ce email est déjà pris',
            'password.required' => 'Un mot de passe est requis',
            'city.required' => 'Une ville est requise',
            'pseudo.required' => 'Un Pseudo est requis',
            'pseudo.unique' => 'Ce pseudo est déjà pris',
            'lastname.required' => 'Un Prénom est requis',
            'firstname.required' => 'Un Nom est requis',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_admin' => false,
            'picture' => 'https://picsum.photos/200/200',
        ]);
    }
}
