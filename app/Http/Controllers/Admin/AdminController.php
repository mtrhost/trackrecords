<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\Game;
use App\Player;
use App\Faction;
use App\Http\Requests\CreatePlayersRequest;
use App\Http\Requests\CreateSettingRequest;
use App\Setting;
use App\GameStatus;
use App\GameTimeStatus;
use App\Http\Requests\CreateGameRequest;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin/index');
    }

    public function roles()
    {
        return view('admin/roles');
    }

    public function players()
    {
        return view('admin/players');
    }

    public function setting()
    {
        $players = Player::select('id', 'name')->get()->toArray();
        $roles = Role::select('id', 'name')->get()->toArray();
        $factions = Faction::select('id', 'name', 'color', 'alias', 'group_id')->get()->toArray();

        return view('admin/setting', compact('players', 'roles', 'factions'));
    }

    public function game()
    {
        $settings = Setting::select('id', 'name')
            ->with([
                'settingRoles' => function($q) {
                    $q->select('id', 'setting_id', 'role_id', 'faction_id')
                        ->with([
                            'role' => function($q) {
                                $q->select('id', 'name');
                            },
                            'faction' => function($q) {
                                $q->select('id', 'name', 'alias', 'color', 'group_id');
                            }
                        ]);
                }
            ])
            ->get()->toArray();
            
        $players = Player::select('id', 'name')->get()->toArray();
        $factions = Faction::select('id', 'name', 'color', 'alias', 'group_id')->get()->toArray();
        $statuses = GameStatus::select('id', 'name')->get()->toArray();
        $timeStatuses = GameTimeStatus::select('id', 'name')->get()->toArray();

        return view('admin/game', compact('settings', 'players', 'factions', 'statuses', 'timeStatuses'));
    }

    public function saveRoles(Request $request)
    {
        $this->validate($request, [
            'roles' => 'required|array|min:1',
            'roles.*' => 'sometimes|string|max:255'
        ]);
        
        $transaction = DB::transaction(function () use ($request) {
            $requestData = $request->toArray();
            foreach($requestData['roles'] as $role) {
                if(Role::where('name', $role)->exists())
                    continue;

                $roleModel = new Role(['name' => $role]);
                if(!$roleModel->save()) {
                    DB::rollback();
                    return false;
                }
            }
        });

        return response()->json($transaction);
    }

    public function savePlayers(CreatePlayersRequest $request) 
    {
        $transaction = DB::transaction(function () use ($request) {
            $requestData = $request->toArray();
            foreach($requestData['players'] as $player) {
                if(Player::where('name', $player['name'])->exists())
                    continue;

                $model = new Player();
                $model->fill($player);
                if(!$model->save()) {
                    DB::rollback();
                    return false;
                }
            }

            return true;
        });

        return response()->json($transaction);
    }

    public function saveSetting(CreateSettingRequest $request) 
    {
        $transaction = DB::transaction(function () use ($request) {
            $requestData = $request->toArray();
            if(!Setting::saveWithRoles($requestData)) {
                DB::rollback();
                return false;
            }

            return true;
        });

        return response()->json($transaction);
    }

    public function saveGame(CreateGameRequest $request) 
    {
        $transaction = DB::transaction(function () use ($request) {
            $requestData = $request->toArray();
            $game = new Game();
            if(!$game->saveWithRoles($requestData)) {
                DB::rollback();
                return false;
            }

            return true;
        });

        return response()->json($transaction);
    }
}
