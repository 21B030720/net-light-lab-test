<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            "name" => "required|min:3|max:255",
            "slug" =>
                "required|min:3|max:255|unique:categories,slug," . $this->id,
            "description" => "nullable",
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
            "name" => "Category name",
            "slug" => "Category slug",
            "description" => "Description",
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
            "name.required" => "The category name is required",
            "slug.required" => "The category slug is required",
            "slug.unique" => "This slug is already taken",
        ];
    }
}
