<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;

class HayaoshiController extends Controller
{
    /**
     * Show the Hayaoshi menu (tutorial/main selection).
     */
    public function index()
    {
        return view('games.Hayaoshi.index');
    }

    /**
     * Show the tutorial mode.
     */
    public function tutorial()
    {
        return view('games.Hayaoshi.play', ['initialTutorial' => true]);
    }

    /**
     * Show the main mode.
     */
    public function main()
    {
        return view('games.Hayaoshi.play', ['initialTutorial' => false]);
    }
}
