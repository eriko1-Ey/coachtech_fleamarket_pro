<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
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
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|string',
            'brand' => 'nullable|string|max:255',
            'categories' => 'required|json',
            'product_images.*' => 'image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください。',
            'name.max' => '商品名は255文字以内で入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'price.required' => '商品価格を入力してください。',
            'price.numeric' => '商品価格は数値で入力してください。',
            'price.min' => '商品価格は1円以上で入力してください。',
            'condition.required' => '商品の状態を選択してください。',
            'categories.required' => 'カテゴリーを選択してください。',
            'categories.*.exists' => '選択されたカテゴリーが無効です。',
            'product_images.*.image' => '画像ファイルをアップロードしてください。',
            'product_images.*.mimes' => '拡張子はJPEGもしくは、PNGでアップロードしてください。',
            'product_images.*.max' => '画像サイズは 2MB 以下にしてください。',
        ];
    }
}
