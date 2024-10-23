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
        return true; // TODO : change true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:150',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:150',
            'phone_number' => 'required|digits:10',
            'type' => 'required|integer',
            'status' => 'nullable|integer',
        ];
    }

    /**
     * Get the validation errors messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name',
            'name.min' => 'Name must be atleast 5 chars long',
            'name.max' => 'Name must not be more thhan 150 chars',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email is already taken. Please try with other email address',
            'password.required' => 'Please entrer your password',
            'password.min' => 'Password must be atleast 5 chars long',
            'password.max' => 'Password must not be more thhan 150 chars',
            'phone_number.required' => 'Please enter your phone number',
            'phone_number.digits' => 'Phone number must be 10 digits number',
        ];
    }

}
