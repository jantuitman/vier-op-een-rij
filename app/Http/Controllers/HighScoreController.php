<?php

namespace App\Http\Controllers;

use App\Models\HighScore;
use Illuminate\Http\Request;

class HighScoreController extends Controller
{
    /**
     * Display high scores page
     */
    public function index(Request $request)
    {
        $query = HighScore::query()->orderBy('turns', 'asc');

        // Filter by difficulty if provided
        if ($request->has('difficulty') && $request->difficulty !== 'all') {
            $query->where('difficulty', $request->difficulty);
        }

        $highScores = $query->get();

        return view('scores.index', compact('highScores'));
    }

    /**
     * Store a new high score
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'turns' => 'required|integer|min:4',
            'difficulty' => 'required|in:EASY,MEDIUM,HARD',
        ]);

        HighScore::create($validated);

        return redirect()->route('scores.index')
            ->with('success', 'High score saved!');
    }
}
