<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Achievement;

class AchievementController extends Controller
{
    public function list()
    {
        $achievements = Achievement::
            with([
                'players' => function($q) {
                    $q->select('players.id', 'players.name');
                }
            ])
            ->orderBy('sort', 'ASC')
            ->get();

        return response()->json($achievements);
    }
}
