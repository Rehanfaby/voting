<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'judge_id' => [
                'required',
                'exists:users,id',
                Rule::unique('points')->where(function ($query) {
                    return $query->where('candidate_id', request('candidate_id'));
                })->ignore($this->point) // ignore current row on update
            ],
            'candidate_id' => 'required|exists:employees,id',
            'depth' => 'required|numeric|min:0|max:20',
//            'diction' => 'required|numeric|min:0|max:10',
            'accuracy' => 'required|numeric|min:0|max:30',
            'interpretation' => 'required|numeric|min:0|max:20',
//            'technique' => 'required|numeric|min:0|max:10',
//            'stage_presence' => 'required|numeric|min:0|max:10',
            'song_choice' => 'required|numeric|min:0|max:10',
            'overall_presentation' => 'required|numeric|min:0|max:20',
//            'adaptability' => 'required|numeric|min:0|max:5',
//            'audience_interaction' => 'required|numeric|min:0|max:5',
        ];
    }

    public function messages()
    {
        return [
            'judge_id.unique' => 'You have already given grading to this candidate.',
            'accuracy.max' => 'Accuracy cannot be more than 30 (30_Max).',
            'song_choice.max' => 'Song choice cannot be more than 10 (10_Max).',
            'depth.max' => 'Depth and atmosphere cannot be more than 20 (20_Max).',
            'interpretation.max' => 'Interpretation cannot be more than 20 (20_Max).',
            'overall_presentation.max' => 'Overall presentation cannot be more than 20 (20_Max).',
            '*.numeric' => 'Scores may include decimals (for example 2.5 or 12.25).',
        ];
    }
}
