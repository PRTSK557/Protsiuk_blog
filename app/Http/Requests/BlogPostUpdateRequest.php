<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostUpdateRequest extends FormRequest
{
    /**
     * Визначає, чи має користувач право робити цей запит.
     *
     * @return bool
     */
    public function authorize()
    {
        // Можна зробити перевірку авторизації, але для лабораторної достатньо true
        return true;
    }

    /**
     * Правила валідації для запиту.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:200',
            'slug' => 'nullable|max:200',
            'excerpt' => 'nullable|max:500',
            'content_raw' => 'required|string|min:5|max:10000',
            'category_id' => 'required|integer|exists:blog_categories,id',
        ];
    }
}
