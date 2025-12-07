<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display the game board
     */
    public function index()
    {
        return view('game.index');
    }
}
