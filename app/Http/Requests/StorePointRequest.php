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

    public function messages()
    {
        return [
            'judge_id.unique' => 'You have already given grading to this candidate.',
        ];
    }
}
