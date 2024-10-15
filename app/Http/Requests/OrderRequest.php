<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|numeric|max:2000',
            'currency' => 'required|string|in:TWD,USD'
        ];
    }

    public function messages()
    {
        return [
            'price.max' => 'Price is over 2000',
            'currency.in' => 'Currency format is wrong',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 400));
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $name = $this->input('name');

            if (!preg_match('/^[A-Za-z\s]+$/', $name)) {
                $validator->errors()->add('name', 'Name contains non-English characters');
            }

            if (!preg_match('/^[A-Z][a-z]*(\s[A-Z][a-z]*)*$/', $name)) {
                $validator->errors()->add('name', 'Name is not capitalized');
            }
        });
    }

}
