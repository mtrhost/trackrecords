<?php

namespace App\Http\Controllers\Player;

use App\Dictionaries\Player\PlayerStatusDictionary;
use App\Http\Controllers\Controller;
use App\Models\Faction\Faction;
use App\Models\Player\Player;
use App\Services\Player\PlayerService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * @var PlayerService
     */
    protected $service;

    /**
     * @param PlayerService $service
     */
    public function __construct(PlayerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $players = Player::select('id', 'name', 'profile', 'last_game', \DB::raw('LOWER(LEFT(name, 1)) AS sort_letter'))
            ->with([
                'statistics' => function($q){
                    $q->select('id', 'player_id', 'games_count_no_role', 'wins_no_role', 'games_count_active', 'wins_active',
                    'games_count_mafia', 'wins_mafia', 'games_count_neutral', 'wins_neutral');
                }
            ])
            ->groupBy('last_game', 'name', 'id', 'profile')
            //->orderByRaw("last_game DESC NULLS LAST, name ASC")
            ->orderByRaw("last_game DESC, name ASC")
            ->get()->each(function(&$value){
                $value->isActive = $value->isActive();
            });
            
        return view('players/list', compact('players'));
    }

    public function details($id)
    {
        $start = microtime(true);
        $player = Player::with([
            'statistics',
            'gameRoles',
            'games' => function($q) use ($id) {
                $q->with([
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
                    },
                    'roles' => function($q) use ($id) {
                        $q->where('player_id', $id)
                            ->with([
                                'role', 'status', 'timeStatus',
                                'faction' => function($q) {
                                    $q->select('id', 'group_id', 'alias', 'name')
                                        ->with([
                                            'group' => function($q) {
                                                $q->select('id', 'title', 'alias', 'title_for_games');
                                            }
                                        ]);
                                }
                            ]);
                    }
                ]);
            },
            'gamesMastered' => function($q) use ($id) {
                $q->with([
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
                    },
                    'roles' => function($q) use ($id) {
                        $q->where('player_id', $id)
                            ->with([
                                'role', 'status', 'timeStatus',
                                'faction' => function($q) {
                                    $q->select('id', 'group_id', 'alias', 'name')
                                        ->with([
                                            'group' => function($q) {
                                                $q->select('id', 'title', 'alias', 'title_for_games');
                                            }
                                        ]);
                                }
                            ]);
                    }
                ]);
            },
            'achievements',
            'partners'
        ])
        ->withCount(['gamesMastered'])
        ->findOrFail($id);

        $this->service->updatePlayerAvatar($player);
        //@todo за последней активностью лучше обращаться отдельным запросом, замедляет загрузку на 3 секунды
        $this->service->parseProfile($player);
        $player->last_game = Carbon::parse($player->last_game)->toDateString();
        $player->status_label = PlayerStatusDictionary::getValueByKey($player->status);
        $player->getWinrate();
        $player->factions = Faction::select('factions.id', 'group_id', 'color', 'faction_groups.alias')
            ->join('faction_groups', 'faction_groups.id', '=', 'factions.group_id')
            ->with(['group' => function($q){ $q->select('id', 'alias'); }])->get()->groupBy('alias');
        $player->roleRate = $player->getRoleRate();
        $player->lightningsCount = $player->getLightningsCount();
        $player->cityNegativeActionsRate = $player->getCityNegativeActionsRate();
        $player->mafiaAverageDaysSurvived = $player->getMafiaAverageDaysSurvived(2);
        $player->games = $player->games->sortByDesc('number')->values();

        return view('players/details', compact('player'));
    }

    public function statistics()
    {
        $statistics = Player::select('id', 'name', 'last_game')
            ->active()
            ->whereHas('statistics')
            ->with([
                'statistics'
            ])
            ->withCount('achievements')
            ->orderBy('last_game', 'ASC')
            ->get()->each(function(&$value){
                $value->gamesCount = $value->getGamesCount(true);
                $value->gamesCountWoMastered = $value->getGamesCount();
                $value->lightningsCount = $value->getLightningsCount();
                $value->getWinrate(2);
                $value->winrateCivilian = $value->getCivilianWinrate(2);
                $value->statistics->civilian_games_count;
                $value->roleRate = $value->getRoleRate(2);
                $value->cityNegativeActionsRate = $value->getCityNegativeActionsRate(2);
                $value->activeAverageDaysSurvived = $value->getActiveAverageDaysSurvived(2);
                $value->mafiaAverageDaysSurvived = $value->getMafiaAverageDaysSurvived(2);
                $value->neutralAverageDaysSurvived = $value->getNeutralAverageDaysSurvived(2);
                $value->routeLink = route('player.details', $value->id);
                $value->isActive = $value->isActive();
            });
            
        return view('players/statistics', compact('statistics'));
    }

    public function getLastActivity(Request $request)
    {
        $validated = $this->validate($request, ['id' => 'required|integer|exists:players,id']);
        $player = Player::findOrFail($validated['id']);
        $this->service->parseProfile($player);
        return response()->json($player->last_active);
    }

    public function playersList()
    {
        $playersGroup = Player::select('id', 'name', 'profile', 'last_game', \DB::raw('LOWER(LEFT(name, 1)) AS sort_letter'))
            ->with([
                'statistics' => function($q){
                    $q->select('id', 'player_id', 'games_count_no_role', 'wins_no_role', 'games_count_active', 'wins_active',
                    'games_count_mafia', 'wins_mafia', 'games_count_neutral', 'wins_neutral');
                }
            ])
            ->orderBy('name', 'ASC')
            ->get()->groupBy('sort_letter');

        foreach($playersGroup as &$group) {
            foreach($group as &$player) {
                $player->last_game = Carbon::parse($player->last_game)->toDateString();
                $player->getWinrate();
            }
        }

        return response()->json(array_values($playersGroup->toArray()));
    }

    public function show(Request $request)
    {
        $this->validate($request, ['id' => 'required|integer|exists:players,id']);

        $player = Player::with([
            'statistics',
            'gameRoles',
            'games' => function($q) use ($request) {
                $q->with([
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
                    },
                    'roles' => function($q) use ($request) {
                        $q->where('player_id', $request->id)
                            ->with([
                                'role', 'status', 'timeStatus',
                                'faction' => function($q) {
                                    $q->select('id', 'group_id', 'alias', 'name')
                                        ->with([
                                            'group' => function($q) {
                                                $q->select('id', 'title', 'alias', 'title_for_games');
                                            }
                                        ]);
                                }
                            ]);
                    }
                ]);
            },
            'achievements'
        ])
        ->withCount(['gamesMastered'])
        ->findOrFail($request->id);

        $player = $this->service->updatePlayerAvatar($player);
        $player->last_game = Carbon::parse($player->last_game)->toDateString();
        $player->getWinrate();
        $player->factions = Faction::select('factions.id', 'group_id', 'color', 'faction_groups.alias')
            ->join('faction_groups', 'faction_groups.id', '=', 'factions.group_id')
            ->with(['group' => function($q){ $q->select('id', 'alias'); }])->get()->groupBy('alias');
        $player->roleRate = $player->getRoleRate();
        $player->lightningsCount = $player->getLightningsCount();
        $player->cityNegativeActionsRate = $player->getCityNegativeActionsRate();
        $player->mafiaAverageDaysSurvived = $player->getMafiaAverageDaysSurvived(2);
        $player->games->each(function(&$value) {
            $value->info = ['name' => $value->name, 'id' => $value->id];
            $value->getWinnersString();
            $value->getRoleString();
            $value->getStatusString();
        });

        return response()->json($player);
    }

    public function parsePlayerProfile(Request $request)
    {
        $this->validate($request, ['id' => 'required|integer|exists:players,id']);

        $player = Player::select('id', 'profile')->findOrFail($request->id);
        $this->service->parseProfile($player);

        return response()->json($player);
    }

    /*public function statistics()
    {
        $statistics = Player::select('id', 'name')
        ->whereHas('statistics')
        ->with([
            'statistics'
        ])
        ->get()->each(function(&$value){
            $value->gamesCount = $value->getGamesCount(true);
            $value->gamesCountWoMastered = $value->getGamesCount();
            $value->lightningsCount = $value->getLightningsCount();
            $value->getWinrate(2);
            $value->winrateCivilian = $value->getCivilianWinrate(2);
            $value->statistics->civilian_games_count;
            $value->roleRate = $value->getRoleRate(2);
            $value->cityNegativeActionsRate = $value->getCityNegativeActionsRate(2);
            $value->mafiaAverageDaysSurvived = $value->getMafiaAverageDaysSurvived(2);
        });
        return response()->json($statistics);
    }*/
}
