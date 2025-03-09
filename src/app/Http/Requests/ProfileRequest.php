<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'post_code' => [
                'required',
                'string',
                'regex:/^\d{3}-\d{4}$/',
            ],
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は255文字以内で入力してください。',
            'post_code.required' => '郵便番号を入力してください。',
            'post_code.max' => '郵便番号は最大10文字までです。',
            'post_code.regex' => '郵便番号は「000-0000」の形式で入力してください。',
            'address.required' => '住所を入力してください。',
            'address.max' => '住所は255文字以内で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',
            'profile_image.image' => '画像ファイルをアップロードしてください。',
            'profile_image.mimes' => 'JPEG, PNG, JPG, GIF のみアップロードできます。',
            'profile_image.max' => '画像サイズは 2MB 以下にしてください。',
        ];
    }
}
