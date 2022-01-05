<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Achievement\Achievement;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::
            with([
                'players' => function($q) {
                    $q->select('players.id', 'players.name');
                }
            ])
            ->orderBy('sort', 'ASC')
            ->get();

        return view('achievements/list', compact('achievements'));
    }
}
