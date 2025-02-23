<?php

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
{
    const COMMENT_MAX = 1000;

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
        $id = $this->request->get('comment_id');
        $rules = [
            'comment_id' => [
                ($this->isMethod('put') ? 'required' : 'nullable'),
                'integer'
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],
            'task_id' => [
                'required',
                'integer',
                'exists:tasks,id'
            ],
            'comment' => [
                'required',
                'string',
                'max:' . self::COMMENT_MAX,
            ],
        ];

        return $rules;
    }

    /**
     * Get error validation.
     */
    public function messages(): array
    {
        return [
            'comment_id.required' => __('The comment id field is required.'),
            'user_id.required' => __('The user id field is required.'),
            'user_id.exists' => __('The selected user id is invalid.'),
            'task_id.required' => __('The task id field is required.'),
            'task_id.exists' => __('The selected building id is invalid.'),
            'comment.required' => __('The comment field is required.'),
            'comment.max' => __('The :attribute may not be greater than :max characters.', ['attribute' => __('task'), 'max' => self::COMMENT_MAX]),
        ];
    }
}
