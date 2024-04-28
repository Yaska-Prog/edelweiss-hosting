<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGaunRequest extends FormRequest
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
            'kode' => 'required|unique:gauns',
            'gambar' => ['required', 'image', 'mimes:jpeg,png,gif,jpg'],
            'warna' => 'required',
            'harga' => 'required',
            'usia' => 'required',
        ];
    }
}
