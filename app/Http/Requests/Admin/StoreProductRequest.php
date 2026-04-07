<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'   => ['required', 'exists:categories,id'],
            'name'          => ['required', 'string', 'max:200'],
            'brand'         => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string'],
            'price'         => ['required', 'numeric', 'min:0'],
            'old_price'     => ['nullable', 'numeric', 'min:0'],
            'image_url'     => ['nullable', 'max:500'],
            'image'         => ['nullable', 'image', 'max:3072'],
            'badge'         => ['nullable', 'string', 'max:50'],
            'discount'      => ['nullable', 'integer', 'min:0', 'max:100'],
            'stock'         => ['required', 'integer', 'min:0'],
            'is_active'     => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'اسم المنتج مطلوب.',
            'brand.required'       => 'الماركة مطلوبة.',
            'price.required'       => 'السعر مطلوب.',
            'category_id.required' => 'القسم مطلوب.',
            'category_id.exists'   => 'القسم المختار غير موجود.',
            'stock.required'       => 'المخزون مطلوب.',
        ];
    }
}
