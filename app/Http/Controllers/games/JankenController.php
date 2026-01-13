<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JankenController extends Controller
{
    // ゲーム画面を表示するメソッド
    public function index()
    {
        // resources/views/games/janken/index.blade.php を表示する
        return view('games.janken.index');
    }
}