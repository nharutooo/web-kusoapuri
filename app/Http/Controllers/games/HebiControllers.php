<?php

namespace App\Http\Controllers\games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HebiControllers extends Controller
{
    private array $ladders = [
        1 => 38, 4 => 14, 9 => 31, 21 => 42,
        28 => 84, 36 => 44, 51 => 67,
        71 => 91, 80 => 100,
    ];

    private array $snakes = [
        16 => 6, 47 => 26, 49 => 11,
        56 => 53, 62 => 19, 64 => 60,
        87 => 24, 93 => 73, 95 => 75, 98 => 78,
    ];

    public function index()
    {
        return view('games.hebi.index', [
            'p1' => session('p1', 1),
            'p2' => session('p2', 1),
            'turn' => session('turn', 1),
            'dice' => session('dice'),
            'message' => session('message'),
            'ladders' => $this->ladders,
            'snakes' => $this->snakes,
        ]);
    }

    public function roll()
    {
        $dice = rand(1, 6);
        $turn = session('turn', 1);

        $p1 = session('p1', 1);
        $p2 = session('p2', 1);

        $current = $turn === 1 ? $p1 : $p2;
        $new = $current + $dice;

        if ($new > 100) {
            $new = $current;
        }

        $message = "🎲 Player {$turn} rolled {$dice}";

        if (isset($this->ladders[$new])) {
            $new = $this->ladders[$new];
            $message .= " 🪜 climbed!";
        } elseif (isset($this->snakes[$new])) {
            $new = $this->snakes[$new];
            $message .= " 🐍 bitten!";
        }

        if ($new === 100) {
            session()->flush();
            session(['message' => "🏆 Player {$turn} wins!"]);
            return redirect()->route('game.index');
        }

        if ($turn === 1) {
            $p1 = $new;
            $turn = 2;
        } else {
            $p2 = $new;
            $turn = 1;
        }

        session([
            'p1' => $p1,
            'p2' => $p2,
            'turn' => $turn,
            'dice' => $dice,
            'message' => $message,
        ]);

        return redirect()->route('game.index');
    }

    public function reset()
    {
        session()->flush();
        return redirect()->route('game.index');
    }
}
