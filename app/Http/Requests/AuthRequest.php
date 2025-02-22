<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthRequest extends FormRequest
{
    const PASS_MIN = 8;

    const NAME_MIN = 10;

    const NAME_MAX = 200;

    const EMAIL_MAX = 200;

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
        $id = $this->request->get('user_id');
        return [
            'user_id' => [
                ($this->isMethod('put') ? 'required' : 'nullable'),
                'integer'
            ],
            'name' => [
                'required',
                'string',
                'min:' . self::NAME_MIN,
                'max:' . self::NAME_MAX
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:' . self::EMAIL_MAX,
                'unique:App\Models\User,email,' . $id . ',id',
                'regex:/^([0-9a-zA-Z]+([_.-]?[0-9a-zA-Z]+)*@[0-9a-zA-Z]+[0-9,a-z,A-Z,.,-]*(.){1}[a-zA-Z]{2,4})+$/'
            ],
            'password' => [
                ($this->isMethod('post') ? 'required' : Rule::requiredIf (fn () => $this->request->get('password'))),
                'required',
                'string',
                'min:' . self::PASS_MIN,
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%*?&\.\(\)_\-\+=\[\]\{\}\|\:;\<\>\"\,\^~])[A-Za-z\d@#$!%*?&\.\(\)_\-\+=\[\]\{\}\|\:;\<\>\"\,\^~]{' . self::PASS_MIN . ',}$/'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => __('The user id field is required.'),
            'name.required' => __('The name field is required.'),
            'name.min' => __('The name must be at least :min characters.', ['min' => self::NAME_MIN]),
            'name.max' => __('The name may not be greater than :max characters.', ['max' => self::NAME_MAX]),
            'email.required' => __('The email field is required.'),
            'email.max' => __('The email may not be greater than :max characters.', ['max' => self::EMAIL_MAX]),
            'email.regex' => __('The email field format is invalid.'),
            'email.email' => __('The email field must be a valid email address.'),
            'password.required' => __('The password field is required.'),
            'password.min' => __('The password must be at least :min characters.', ['min' => self::PASS_MIN]),
            'password.regex' => __('Password format is invalid. Must contain at least 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character.'),
        ];
    }
}
