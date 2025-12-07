<?php

namespace Database\Seeders;

use App\Models\HighScore;
use Illuminate\Database\Seeder;

class HighScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing scores
        HighScore::truncate();

        // Seed test data with various turns and difficulties
        HighScore::create(['name' => 'Alice', 'turns' => 12, 'difficulty' => 'EASY']);
        HighScore::create(['name' => 'Bob', 'turns' => 8, 'difficulty' => 'MEDIUM']);
        HighScore::create(['name' => 'Charlie', 'turns' => 15, 'difficulty' => 'HARD']);
        HighScore::create(['name' => 'Diana', 'turns' => 6, 'difficulty' => 'EASY']);
        HighScore::create(['name' => 'Eve', 'turns' => 20, 'difficulty' => 'MEDIUM']);
        HighScore::create(['name' => 'Frank', 'turns' => 10, 'difficulty' => 'HARD']);
        HighScore::create(['name' => 'Grace', 'turns' => 18, 'difficulty' => 'EASY']);

        echo "Seeded 7 high scores\n";
    }
}
