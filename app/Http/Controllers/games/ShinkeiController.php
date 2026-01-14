<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShinkeiController extends Controller
{
    public function index(Request $request)
    {
        // Get level from URL, default to 2
        $level = $request->query('level', 2);
        
        // Define pairs based on level
        $counts = [
            1 => 4,  // 8 cards total
            2 => 8,  // 16 cards total
            3 => 10, // 20 cards total
            4 => 15  // 30 cards total (Hard!)
        ];

        $pairCount = $counts[$level] ?? 8;
        
        $allEmojis = ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐣', '🐳', '🦄', '🐝', '🎨'];
        $selectedEmojis = array_slice($allEmojis, 0, $pairCount);
        
        // Create pairs and shuffle
        $cards = collect(array_merge($selectedEmojis, $selectedEmojis))->shuffle();

        return view('games.shinkei.index', compact('cards', 'level', 'pairCount'));
    }
}