<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'sometimes|string|nullable|max:255', 
            'players_count' => 'sometimes|integer|nullable|min:1',
            'author' => 'sometimes|string|nullable|max:255',
        ]);
        $settings = Setting::select('id', 'name', 'players_count', 'author_id')
            ->when($request->filled('name'), function ($q) use ($validated) {
                return $q->where('name', 'LIKE', '%' . $validated['name'] . '%');
            })
            ->when($request->filled('players_count'), function ($q) use ($validated) {
                return $q->where('players_count', $validated['players_count']);
            })
            ->when($request->filled('author'), function ($q) use ($validated) {
                return $q->whereHas('author', function($q) use ($validated) {
                    $q->where('name', 'LIKE', '%' . $validated['author'] . '%');
                });
            })
            ->with([
                'author' => function($q){
                    $q->select('id', 'name');
                }
            ])->paginate(20);
            
        return view('settings/list', compact('settings'))->with(array_filter($request->all()));
    }
    
    public function list()
    {
        $settings = Setting::with([
            'author' => function($q){
                $q->select('id', 'name');
            }
        ])->get();

        $settings->each(function(&$value){
            $value->info = ['name' => $value->name, 'id' => $value->id];
        });

        return response()->json($settings);
    }

    public function show(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:settings,id'
        ]);

        $setting = Setting::with([
            'author' => function($q){
                $q->select('id', 'name');
            },
            'settingRoles' => function($q) {
                $q->with([
                    'role',
                    'faction' => function($q) {
                        $q->select('id', 'group_id', 'alias', 'name')
                            ->with([
                                'group' => function($q) {
                                    $q->select('id', 'title', 'alias', 'title_for_games');
                                }
                            ]);
                    }
                ]);
            },
            'games' => function($q) {
                $q->select('id', 'setting_id', 'master_id', 'name')
                    ->with([
                        'winners' => function($q) {
                            $q->select('id', 'game_id', 'faction_id')
                                ->with([
                                    'faction' => function($q) {
                                        $q->select('id', 'group_id', 'alias', 'name')
                                            ->with([
                                                'group' => function($q) {
                                                    $q->select('id', 'title', 'alias', 'title_for_games');
                                                }
                                            ]);
                                    }
                                ]);
                        },
                        'master' => function($q) {
                            $q->select('id', 'name');
                        },
                    ]);
            }
        ])->findOrFail($request->id);

        $setting->games->each(function(&$value) {
            $value->info = ['name' => $value->name, 'id' => $value->id];
            $value->getWinnersString();
        });
        $setting->rolesSorted = array_values($setting->settingRoles->groupBy('faction.id')->toArray());
        unset($setting->settingRoles);

        return response()->json($setting);
    }
}
