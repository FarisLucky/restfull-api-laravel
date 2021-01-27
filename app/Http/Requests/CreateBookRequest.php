<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CreateBookRequest extends FormRequest
{
    /**
     * authorize request
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * rules validation request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.type' => 'required|in:books',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.publication_year' => 'required|string',
        ];
    }
}
