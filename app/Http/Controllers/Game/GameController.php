<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\Repositories\Interfaces\PDRepositoryInterface;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with([
            'master' => function($q) {
                $q->select('id', 'name');
            },
            'winners' => function($q) {
                $q->select('id', 'game_id', 'faction_id')
                    ->with([
                        'faction' => function($q) {
                            $q->select('id', 'group_id')
                                ->with([
                                    'group' => function($q) {
                                        $q->select('id', 'title', 'alias', 'title_for_games');
                                    }
                                ]);
                        }
                    ]);
            }
        ])
        ->orderBy('number', 'DESC')
        ->get();

        $games->each(function(&$value) {
            $value->info = ['name' => $value->name, 'id' => $value->id];
            $value->getWinnersString();
        });

        return view('games/list', compact('games'));
    }

    public function details($id)
    {
        $game = Game::select('id', 'setting_id', 'master_id', 'number', 'name', 'length', 'date', 'link', 'status', 'players_count')
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
                'setting' => function($q) {
                    $q->select('id', 'name');
                },
                'roles' => function($q) {
                    $q->with([
                        'player' => function($q) {
                            $q->select('id', 'name');
                        },
                        'role',
                        'faction' => function($q) {
                            $q->select('id', 'group_id', 'alias', 'sort')
                                ->with([
                                    'group' => function($q) {
                                        $q->select('id', 'alias');
                                    }
                                ]);
                        },
                        'status' => function($q) {
                            $q->select('id', 'name');
                        },
                        'timeStatus' => function($q) {
                            $q->select('id', 'name');
                        }
                    ]);
                }
            ])
            ->findOrFail($id);

        if(!$game->roles->isEmpty()) {
            $game->roles = $game->roles->sortBy('faction.sort')->groupBy('faction.sort')->map(function($q) use ($game){
                return $q->sortByDesc(function($value) use ($game){
                    $day = $value->day === null ? $game->length + 1 : $value->day;
                    $time = $value->timeStatus === null ? (int)false : $value->timeStatus->id;
                    return $day . $time;
                }, SORT_NATURAL);
            });
            $game->rolesSorted = [];
            foreach($game->roles as $roles) {
                $game->rolesSorted = array_merge($game->rolesSorted, $roles->toArray());
            }
        }
        
        return view('games/details', compact('game'));
    }

    public function list()
    {
        $games = Game::with([
            'master' => function($q) {
                $q->select('id', 'name');
            },
            'winners' => function($q) {
                $q->select('id', 'game_id', 'faction_id')
                    ->with([
                        'faction' => function($q) {
                            $q->select('id', 'group_id')
                                ->with([
                                    'group' => function($q) {
                                        $q->select('id', 'title', 'alias', 'title_for_games');
                                    }
                                ]);
                        }
                    ]);
            }
        ])
        ->get();

        $games->each(function(&$value) {
            $value->info = ['name' => $value->name, 'id' => $value->id];
            $value->getWinnersString();
        });
        
        return response()->json($games);
    }

    public function show(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:games,id'
        ]);

        $game = Game::select('id', 'setting_id', 'master_id', 'number', 'name', 'length', 'date', 'link', 'status', 'players_count')
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
                'setting' => function($q) {
                    $q->select('id', 'name');
                },
                'roles' => function($q) {
                    $q->with([
                        'player' => function($q) {
                            $q->select('id', 'name');
                        },
                        'role',
                        'faction' => function($q) {
                            $q->select('id', 'group_id', 'alias', 'sort')
                                ->with([
                                    'group' => function($q) {
                                        $q->select('id', 'alias');
                                    }
                                ]);
                        },
                        'status' => function($q) {
                            $q->select('id', 'name');
                        },
                        'timeStatus' => function($q) {
                            $q->select('id', 'name');
                        }
                    ]);
                }
            ])
            ->findOrFail($request->id);

        if(!$game->roles->isEmpty()) {
            $game->roles = $game->roles->sortBy('faction.sort')->groupBy('faction.sort')->map(function($q) use ($game){
                return $q->sortByDesc(function($value) use ($game){
                    $day = $value->day === null ? $game->length + 1 : $value->day;
                    $time = $value->timeStatus === null ? (int)false : $value->timeStatus->id;
                    return $day . $time;
                }, SORT_NATURAL);
            });
            $game->rolesSorted = [];
            foreach($game->roles as $roles) {
                $game->rolesSorted = array_merge($game->rolesSorted, $roles->toArray());
            }
        }
        
        return response()->json($game);
    }
	
	public function votes()
    {
        dd(app(PDRepositoryInterface::class)->parseVotes());
        return view('games/votes');
    }
}
