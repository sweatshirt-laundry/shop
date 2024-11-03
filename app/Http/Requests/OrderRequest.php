<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
            "customer" => "required|string|min:2|max:255",
            "items" => "required|array|min:1",
            "items.*.category" => "required|in:pants,shirt,jacket,bed-sheet",
            "items.*.quantity" => "required|integer|min:1",
        ];
    }
    public function messages(): array
    {
        return [
            "customer.required" => "Customer name is required",
            "items" => "Items must exist in the order",
            "items.min" => "At least one item must exist",
            "items.*.category.required" => "Item category must be specified",
            "items.*.category.in" => "Invalid category",
            "item.*.quantity.required" => "Item quantity must be specified",
            "item.*.quantity.min" => "Item quantity must be at least 1",
            "item.*.quantity.integer" => "Item quantity must be an integer",
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator,response()->json($validator->errors(), 422));
    }
}
