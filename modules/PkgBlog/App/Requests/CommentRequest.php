<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgBlog\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|max:255',
            'user_id' => 'required',
            'article_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => __('validation.required', ['attribute' => __('PkgBlog::category.content')]),
            'content.max' => __('validation.contentMax'),
            'user_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.user_id')]),
            'user_id.max' => __('validation.user_idMax'),
            'article_id.required' => __('validation.required', ['attribute' => __('PkgBlog::category.article_id')]),
            'article_id.max' => __('validation.article_idMax')
        ];
    }
}
