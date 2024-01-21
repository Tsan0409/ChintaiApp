<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExecDeepLearningPostRequest extends FormRequest
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
            'city_id' => 'required|exists:cities,id',
            'room_area' => 'required|numeric|max:120|min:1',
            'building_age' => 'required|numeric|max:50|min:1',
            'room_count' => 'required|numeric|max:5|min:1',
            'distance' => 'required|numeric|max:60|min:1',
            'room_plan' => 'required'
        ];
    }
}
