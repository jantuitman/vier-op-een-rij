@extends('layouts.app')

@section('title', 'High Scores - Vier op een Rij')

@section('content')
<div class="scores-container">
    <h2>High Scores</h2>

    <div class="filter-controls">
        <form method="GET" action="{{ route('scores.index') }}" class="filter-form">
            <label for="difficulty">Filter by difficulty:</label>
            <select name="difficulty" id="difficulty" onchange="this.form.submit()">
                <option value="all" {{ request('difficulty', 'all') === 'all' ? 'selected' : '' }}>All</option>
                <option value="EASY" {{ request('difficulty') === 'EASY' ? 'selected' : '' }}>EASY</option>
                <option value="MEDIUM" {{ request('difficulty') === 'MEDIUM' ? 'selected' : '' }}>MEDIUM</option>
                <option value="HARD" {{ request('difficulty') === 'HARD' ? 'selected' : '' }}>HARD</option>
            </select>
        </form>
    </div>

    @if($highScores->isEmpty())
        <div class="no-scores">
            <p>No high scores yet. Be the first to win and set a record!</p>
            <a href="{{ route('game.index') }}" class="btn-play">Play Game</a>
        </div>
    @else
        <table class="scores-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Turns</th>
                    <th>Difficulty</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($highScores as $index => $score)
                    <tr>
                        <td class="rank">{{ $index + 1 }}</td>
                        <td class="name">{{ $score->name }}</td>
                        <td class="turns">{{ $score->turns }}</td>
                        <td class="difficulty">
                            <span class="badge badge-{{ strtolower($score->difficulty) }}">
                                {{ $score->difficulty }}
                            </span>
                        </td>
                        <td class="date">{{ $score->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
