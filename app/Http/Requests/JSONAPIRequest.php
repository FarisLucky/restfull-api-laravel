<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JSONAPIRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            'data' => 'required|array',
            'data.id' => ($this->method() === Request::METHOD_PATCH) ? 'required|string' : "string",
            'data.type' => ['required', Rule::in(
                array_keys(config('jsonapispec.resources')))
            ],
            'data.attributes' => 'required|array',

            'data.relationships' => 'array',
            'data.relationships.*.data' => 'required|array',

            "data.relationships.*.data.id" => [Rule::requiredIf($this->has('data.relationships.*.data.type')), 'string'],
            "data.relationships.*.data.type" => [
                Rule::requiredIf($this->has('data.relationships.*.data.id')),
                Rule::in(array_keys(config("jsonapispec.resources")))
            ],

            "data.relationships.*.data.*.id" => [Rule::requiredIf($this->has('data.relationships.*.data.*.0')), 'string'],
            "data.relationships.*.data.*.type" => [
                Rule::requiredIf($this->has('data.relationships.*.data.*.i0d')),
                Rule::in(array_keys(config("jsonapispec.resources")))
            ],
        ];
        return $this->mergeConfigRules($rules);
    }

    public function mergeConfigRules(array $rules): array
    {
        $type = $this->input('data.type');

        if ($type && config("jsonapispec.resources{$type}")) {
            switch ($this->method) {
                case 'PATCH':
                    $rules = array_merge($rules, config("jsonapispec.resources.{$type}.validationRules.update"));
                    break;
                case 'POST':
                default:
                    $rules = array_merge($rules, config("jsonapispec.resources.{$type}.validationRules.create"));
                    break;
            }
        }
        return $rules;
    }
}
