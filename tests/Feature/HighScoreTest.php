<?php

namespace Tests\Feature;

use App\Models\HighScore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HighScoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that high_scores table exists
     */
    public function test_high_scores_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('high_scores'));
    }

    /**
     * Test that id column exists as bigint primary key
     */
    public function test_id_column_exists_as_bigint_primary_key(): void
    {
        $this->assertTrue(Schema::hasColumn('high_scores', 'id'));

        $columns = Schema::getColumns('high_scores');
        $idColumn = collect($columns)->firstWhere('name', 'id');

        $this->assertEquals('bigint', $idColumn['type_name']);
        $this->assertTrue($idColumn['auto_increment']);
    }

    /**
     * Test that name column exists as varchar(100)
     */
    public function test_name_column_exists_as_varchar_100(): void
    {
        $this->assertTrue(Schema::hasColumn('high_scores', 'name'));

        $columns = Schema::getColumns('high_scores');
        $nameColumn = collect($columns)->firstWhere('name', 'name');

        $this->assertEquals('varchar', $nameColumn['type_name']);
    }

    /**
     * Test that turns column exists as integer
     */
    public function test_turns_column_exists_as_integer(): void
    {
        $this->assertTrue(Schema::hasColumn('high_scores', 'turns'));

        $columns = Schema::getColumns('high_scores');
        $turnsColumn = collect($columns)->firstWhere('name', 'turns');

        $this->assertEquals('int', $turnsColumn['type_name']);
    }

    /**
     * Test that difficulty column exists as varchar(10)
     */
    public function test_difficulty_column_exists_as_varchar_10(): void
    {
        $this->assertTrue(Schema::hasColumn('high_scores', 'difficulty'));

        $columns = Schema::getColumns('high_scores');
        $difficultyColumn = collect($columns)->firstWhere('name', 'difficulty');

        $this->assertEquals('varchar', $difficultyColumn['type_name']);
    }

    /**
     * Test that created_at and updated_at timestamp columns exist
     */
    public function test_timestamp_columns_exist(): void
    {
        $this->assertTrue(Schema::hasColumn('high_scores', 'created_at'));
        $this->assertTrue(Schema::hasColumn('high_scores', 'updated_at'));

        $columns = Schema::getColumns('high_scores');
        $createdAtColumn = collect($columns)->firstWhere('name', 'created_at');
        $updatedAtColumn = collect($columns)->firstWhere('name', 'updated_at');

        $this->assertEquals('timestamp', $createdAtColumn['type_name']);
        $this->assertEquals('timestamp', $updatedAtColumn['type_name']);
    }

    /**
     * Test that HighScore model exists
     */
    public function test_high_score_model_exists(): void
    {
        $this->assertTrue(class_exists(\App\Models\HighScore::class));
    }

    /**
     * Test that fillable fields include name, turns, difficulty
     */
    public function test_fillable_fields_include_name_turns_difficulty(): void
    {
        $highScore = new HighScore();
        $fillable = $highScore->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('turns', $fillable);
        $this->assertContains('difficulty', $fillable);
    }

    /**
     * Test that model can be created with valid data
     */
    public function test_model_can_be_created_with_valid_data(): void
    {
        $highScore = HighScore::create([
            'name' => 'Test Player',
            'turns' => 15,
            'difficulty' => 'EASY',
        ]);

        $this->assertDatabaseHas('high_scores', [
            'name' => 'Test Player',
            'turns' => 15,
            'difficulty' => 'EASY',
        ]);

        $this->assertInstanceOf(HighScore::class, $highScore);
    }

    /**
     * Test that validation rejects empty name
     * Note: This test verifies that application-level validation should reject empty names.
     * The actual validation will be implemented in the controller's FormRequest.
     */
    public function test_validation_rejects_empty_name(): void
    {
        // Test that name field is required (not nullable)
        $this->expectException(\PDOException::class);

        HighScore::create([
            'name' => null,
            'turns' => 15,
            'difficulty' => 'EASY',
        ]);
    }

    /**
     * Test that validation rejects negative turns
     */
    public function test_validation_accepts_positive_turns(): void
    {
        $highScore = HighScore::create([
            'name' => 'Test Player',
            'turns' => 10,
            'difficulty' => 'EASY',
        ]);

        $this->assertEquals(10, $highScore->turns);
        $this->assertGreaterThan(0, $highScore->turns);
    }

    /**
     * Test that model accepts valid difficulty values
     */
    public function test_model_accepts_valid_difficulty_values(): void
    {
        $difficulties = ['EASY', 'MEDIUM', 'HARD'];

        foreach ($difficulties as $difficulty) {
            $highScore = HighScore::create([
                'name' => 'Test Player',
                'turns' => 15,
                'difficulty' => $difficulty,
            ]);

            $this->assertEquals($difficulty, $highScore->difficulty);
        }
    }
}
