<?php

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    const NAME_MIN = 10;

    const NAME_MAX = 150;

    const DESCRIPTION_MAX = 1000;

    protected $allowedStatus;

    public function __construct()
    {
        $this->allowedStatus = array_map(function($c)
        {
            return $c->value;
        },Status::cases());
    }

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
        $id = $this->request->get('task_id');
        $rules = [
            'task_id' => [
                ($this->isMethod('put') ? 'required' : 'nullable'),
                'integer'
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],
            'building_id' => [
                'required',
                'integer',
                'exists:buildings,id'
            ],
            'name' => [
                'required',
                'string',
                'min:' . self::NAME_MIN,
                'max:' . self::NAME_MAX,
                'unique:App\Models\Task,name,' . $id . ',id'
            ],
            'description' => [
                'required',
                'string',
                'max:' . self::DESCRIPTION_MAX,
            ],
            'status' => [
                ($this->isMethod('put') ? 'required' : 'nullable'),
                'integer',
                Rule::in($this->allowedStatus)
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
            'task_id.required' => __('The task id field is required.'),
            'user_id.required' => __('The user id field is required.'),
            'user_id.exists' => __('The selected user id is invalid.'),
            'building_id.required' => __('The building id field is required.'),
            'building_id.exists' => __('The selected building id is invalid.'),
            'name.required' => __('The :attribute field is required.', ['attribute' => __('name')]),
            'name.unique' => __('There is already a :attribute with this name.', ['attribute' => __('task')]),
            'name.min' => __('The :attribute must be at least :min characters.', ['attribute' => __('task'), 'min' => self::NAME_MIN]),
            'name.max' => __('The :attribute may not be greater than :max characters.', ['attribute' => __('task'), 'max' => self::NAME_MAX]),
            'description.max' => __('The :attribute may not be greater than :max characters.', ['attribute' => __('task'), 'max' => self::DESCRIPTION_MAX]),
            'status.required' => __('The status field is required.'),
            'status.in' => __('The status field must exist in allowed status: ' . implode(', ', $this->allowedStatus))
        ];
    }
}
