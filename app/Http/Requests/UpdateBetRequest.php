<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->id() === $this->bet->user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'match' => ['required', 'string', 'max:255'],
            'bet_size' => ['required', 'numeric', 'min:0'],
            'odd' => [
                'required',
                'numeric',
                Rule::when($this->user()->odd_type === 'american', ['min:-10000', 'max:100000']),
                Rule::when($this->user()->odd_type === 'decimal', ['min:1.001', 'max:1001'])
            ],
            'sport' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['required', 'date_format:H:i'],
            'bookie' => ['required', 'string', 'max:255'],
            'bet_type' => ['required', 'string', 'max:255'],
            'bet_description' => ['nullable', 'string', 'max:255'],
            'bet_pick' => ['required', 'string', 'max:255'],
            'result' => ['nullable', Rule::in([0, 1, 2])],
            'cashout' => ['exclude_unless:result,2', 'required', 'numeric', 'min:0']
        ];
    }
}
