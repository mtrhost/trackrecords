<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGameRequest extends FormRequest
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
            'setting_id' => 'required|integer|exists:settings,id',
            'number' => 'required|integer',
            'length' => 'required|integer',
            'master_id' => 'required|integer|exists:players,id',
            'date' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'status' => 'sometimes|string|nullable',
            'players_count' => 'required|integer',
            'active_count' => 'required|integer',
            'mafia_count' => 'required|integer',
            'neutral_count' => 'required|integer',

            'roles' => 'required_without:status|array|min:1',
            'roles.*' => 'required_without:status|array',
            'roles.*.player_id' => 'required_without:status|integer|exists:players,id',
            'roles.*.role_id' => 'required_without:status|integer|exists:roles,id',
            'roles.*.faction_id' => 'required_without:status|integer|exists:factions,id',
            'roles.*.status_id' => 'required_without:status|integer|exists:game_statuses,id',
            'roles.*.day' => 'sometimes|integer|min:1',
            'roles.*.time_status_id' => 'sometimes|integer|exists:game_time_statuses,id',
        ];
    }
}
