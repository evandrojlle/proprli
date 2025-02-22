<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    const NAME_MIN = 10;

    const NAME_MAX = 150;

    const DESCRIPTION_MAX = 1000;
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
        ];

        return $rules;
    }

    /**
     * Get error validation.
     */
    public function messages(): array
    {
        return [
            'task_id.required' => __('The :attribute id field is required.', ['attribute' => __('task')]),
            'building_id.required' => __('The :attribute id field is required.', ['attribute' => __('building')]),
            'building_id.exists' => __('The selected building id is invalid.'),
            'name.required' => __('The :attribute field is required.', ['attribute' => __('task')]),
            'name.unique' => __('There is already a :attribute with this name.', ['attribute' => __('task')]),
            'name.min' => __('The :attribute must be at least :min characters.', ['attribute' => __('task'), 'min' => self::NAME_MIN]),
            'name.max' => __('The :attribute may not be greater than :max characters.', ['attribute' => __('task'), 'max' => self::NAME_MAX]),
            'description.max' => __('The :attribute may not be greater than :max characters.', ['attribute' => __('task'), 'max' => self::DESCRIPTION_MAX]),
        ];
    }
}
