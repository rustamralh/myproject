<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'string',
            ],
            'status' => [
                'required',
                'string',
            ],
        ];
        $postRulesPrefix = 'posts.*.';

        $postRules  = [
            $postRulesPrefix . 'id' => ['nullable','numeric'],
            $postRulesPrefix . 'title' => ['required','string'],
            $postRulesPrefix . 'content' => ['required','string']
        ];


        return (collect($rules)->merge(collect($postRules)))->toArray();
    }
}
