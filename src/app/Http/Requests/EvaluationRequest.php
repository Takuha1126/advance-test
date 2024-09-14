<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png|max:8192',
        ];
    }

    /**
     * Get custom error messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'rating.required' => '評価は必須です。',
            'rating.integer' => '評価は整数でなければなりません。',
            'rating.between' => '評価は1から5の間でなければなりません。',
            'comment.string' => 'コメントは文字列でなければなりません。',
            'comment.max' => 'コメントは400文字以内でなければなりません。',
            'image.image' => 'ファイルは画像でなければなりません。',
            'image.mimes' => '画像はjpegまたはpng形式でなければなりません。',
            'image.max' => '画像は8MB以内でなければなりません。',
        ];
    }
}
