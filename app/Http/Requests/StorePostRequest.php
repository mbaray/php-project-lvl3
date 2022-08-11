<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'url.name' => 'required|url|max:255',
        ];
    }

//    /**
//     * Configure the validator instance.
//     *
//     * @param  \Illuminate\Validation\Validator  $validator
//     * @return void
//     */
//    public function withValidator($validator)
//    {
//        $validator->after(function ($validator) {
//            if ($this->somethingElseIsInvalid()) {
//                $validator->errors()->add('field', 'Something is wrong with this field!');
//            }
//        });
//    }
}

