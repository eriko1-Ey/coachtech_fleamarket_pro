<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'content' => ['required', 'string', 'max:400'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png'],
        ];
    }

    public function messages()
    {
        return [
            'content.required' => '本文は必須です。',
            'content.max' => '本文は400文字以内で入力してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '画像の形式はjpegまたはpngのみ使用できます。',
        ];
    }
}
