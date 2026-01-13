<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShinkeiController extends Controller
{
    public function index()
    {
        $emojis = ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼'];

        $cards = collect(array_merge($emojis, $emojis))->shuffle();

        return view('games.shinkei.index', compact('cards'));
    }
}