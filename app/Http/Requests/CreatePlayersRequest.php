<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlayersRequest extends FormRequest
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
            'players' => 'required|array|min:1',
            'players.*' => 'sometimes|array|min:1',
            'players.*.name' => 'sometimes|string|max:255',
            'players.*.profile' => 'sometimes|string|nullable|max:255',
        ];
    }
}
