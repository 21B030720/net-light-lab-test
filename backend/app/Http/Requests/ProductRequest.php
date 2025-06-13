<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "category_id" => "required|exists:categories,id",
            "name" => "required|min:3|max:255",
            "slug" =>
                "required|min:3|max:255|unique:products,slug," . $this->id,
            "description" => "nullable",
            "price" => "required|numeric|min:0",
            "stock" => "required|integer|min:0",
            "image_url" => "nullable|image|max:2048",
            "is_active" => "boolean",
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            "category_id" => "Category",
            "name" => "Product name",
            "slug" => "Product slug",
            "description" => "Description",
            "price" => "Price",
            "stock" => "Stock quantity",
            "image_url" => "Product image",
            "is_active" => "Active status",
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            "category_id.required" => "The category is required",
            "category_id.exists" => "The selected category does not exist",
            "name.required" => "The product name is required",
            "slug.required" => "The product slug is required",
            "slug.unique" => "This slug is already taken",
            "price.required" => "The product price is required",
            "price.numeric" => "The price must be a valid number",
            "price.min" => "The price cannot be negative",
            "stock.required" => "The stock quantity is required",
            "stock.integer" => "The stock must be a whole number",
            "stock.min" => "The stock cannot be negative",
            "image_url.image" => "The file must be an image",
        ];
    }
}
