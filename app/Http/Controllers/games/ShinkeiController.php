<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShinkeiController extends Controller
{
    public function index(Request $request)
    {
        $level = $request->query('level', 2);
        
        $counts = [
            1 => 4,  // 8 cards
            2 => 8,  // 16 cards
            3 => 10, // 20 cards
            4 => 15  // 30 cards
        ];

        $pairCount = $counts[$level] ?? 8;
        
        $allEmojis = ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐣', '🐳', '🦄', '🐝', '🎨', '🚀', '🌈', '💎', '🔥'];
        $selectedEmojis = array_slice($allEmojis, 0, $pairCount);
        
        $cards = collect(array_merge($selectedEmojis, $selectedEmojis))->shuffle();

        return view('games.shinkei.index', compact('cards', 'level', 'pairCount'));
    }
}