<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgBlog\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
            'content' => 'required|max:255',
            'category_id' => 'required',
            'user_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('validation.required', ['attribute' => __('PkgBlog::category.title')]),
            'title.max' => __('validation.titleMax'),
            'slug.required' => __('validation.required', ['attribute' => __('PkgBlog::category.slug')]),
            'slug.max' => __('validation.slugMax'),
            'content.required' => __('validation.required', ['attribute' => __('PkgBlog::category.content')]),
            'content.max' => __('validation.contentMax'),
            'category_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.category_id')]),
            'category_id.max' => __('validation.category_idMax'),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.user_id')]),
            'user_id.max' => __('validation.user_idMax')
        ];
    }
}
