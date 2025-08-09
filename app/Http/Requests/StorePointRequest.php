<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePointRequest extends FormRequest
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
            'judge_id' => 'required|exists:judges,id',
            'candidate_id' => 'required|exists:employees,id',
            'depth' => 'required|integer|min:0|max:20',
            'diction' => 'required|integer|min:0|max:10',
            'accuracy' => 'required|integer|min:0|max:10',
            'interpretation' => 'required|integer|min:0|max:10',
            'technique' => 'required|integer|min:0|max:10',
            'stage_presence' => 'required|integer|min:0|max:10',
            'song_choice' => 'required|integer|min:0|max:10',
            'overall_presentation' => 'required|integer|min:0|max:10',
            'adaptability' => 'required|integer|min:0|max:5',
            'audience_interaction' => 'required|integer|min:0|max:5',
        ];
    }
}
