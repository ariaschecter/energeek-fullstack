<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public $validator;

    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() : array
    {
        $id = $this->route('id');
        if ($this->isMethod('post')) {
            return [
                'name'     => 'required|max:255',
                'username' => 'required|max:255|unique:users,username',
                'email'    => 'required|max:255|unique:users,email|email',
            ];
        } else {
            return [
                'name'     => 'required|max:255',
                'username' => ['required', 'max:255', Rule::unique('users')->ignore($id),],
                'email'    => ['required', 'max:255', Rule::unique('users')->ignore($id),],
            ];
        }
    }
}
