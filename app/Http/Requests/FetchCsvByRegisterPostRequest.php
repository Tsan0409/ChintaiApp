<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchCsvByRegisterPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prefecture_id' => 'required|exists:prefectures,id',
            'scraping_url' => 'required|url',
            'city_name' => 'required|max:10', 
            'city_kana_name' => 'required|max:50'
        ];
    }
}
