<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSettingRequest extends FormRequest
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
            'players_count' => 'required|integer|min:1',
            'author_id' => 'required|integer|exists:players,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'required|array',
            'roles.*.role_id' => 'required|integer|exists:roles,id',
            'roles.*.faction_id' => 'required|integer|exists:factions,id'
        ];
    }
}
