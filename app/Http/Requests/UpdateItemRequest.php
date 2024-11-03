<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            "quantity" => "integer|min:1|required_without:category",
            "category" => "in:pants,shirts,jacket,bed-sheet|required_without:quantity",
        ];
    }
    public function messages(): array
    {
        return [
            "quantity.integer" => "Quantity must be an integer",
            "quantity.min" => "Quantity must be at least one",
            "category.in" => "Invalid category",
            "required_without" => "No property has been specified for the update",
        ];
    }
}
