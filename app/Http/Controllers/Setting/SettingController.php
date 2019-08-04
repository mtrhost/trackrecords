<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class SettingController extends Controller
{
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
