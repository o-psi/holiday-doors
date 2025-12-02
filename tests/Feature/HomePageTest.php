<?php

namespace Tests\Feature;

use App\Models\Door;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_home_page_shows_all_doors(): void
    {
        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);
        $door3 = Door::create(['name' => 'Charlie', 'image_path' => 'doors/door3.jpg']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Alice');
        $response->assertSee('Bob');
        $response->assertSee('Charlie');
    }

    public function test_home_page_shows_results_with_correct_rankings(): void
    {
        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);
        $door3 = Door::create(['name' => 'Charlie', 'image_path' => 'doors/door3.jpg']);

        // Create votes
        Vote::create(['voter_name' => 'Voter1', 'door_id' => $door1->id, 'rank' => 1]); // 3 pts
        Vote::create(['voter_name' => 'Voter1', 'door_id' => $door2->id, 'rank' => 2]); // 2 pts
        Vote::create(['voter_name' => 'Voter1', 'door_id' => $door3->id, 'rank' => 3]); // 1 pt

        Vote::create(['voter_name' => 'Voter2', 'door_id' => $door2->id, 'rank' => 1]); // 3 pts
        Vote::create(['voter_name' => 'Voter2', 'door_id' => $door1->id, 'rank' => 2]); // 2 pts
        Vote::create(['voter_name' => 'Voter2', 'door_id' => $door3->id, 'rank' => 3]); // 1 pt

        // door1: 3 + 2 = 5 points
        // door2: 2 + 3 = 5 points
        // door3: 1 + 1 = 2 points

        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertViewHas('results');

        $results = $response->viewData('results');
        
        // Verify results are calculated correctly
        $this->assertIsArray($results);
        $this->assertCount(3, $results);

        // Find door1 results
        $door1Result = collect($results)->firstWhere('door.id', $door1->id);
        $this->assertEquals(5, $door1Result['total_points']);
        $this->assertEquals(1, $door1Result['first_choice']);
        $this->assertEquals(1, $door1Result['second_choice']);
        $this->assertEquals(0, $door1Result['third_choice']);

        // Find door2 results
        $door2Result = collect($results)->firstWhere('door.id', $door2->id);
        $this->assertEquals(5, $door2Result['total_points']);
        $this->assertEquals(1, $door2Result['first_choice']);
        $this->assertEquals(1, $door2Result['second_choice']);
        $this->assertEquals(0, $door2Result['third_choice']);

        // Find door3 results
        $door3Result = collect($results)->firstWhere('door.id', $door3->id);
        $this->assertEquals(2, $door3Result['total_points']);
        $this->assertEquals(0, $door3Result['first_choice']);
        $this->assertEquals(0, $door3Result['second_choice']);
        $this->assertEquals(2, $door3Result['third_choice']);
    }

    public function test_home_page_sorts_results_by_total_points_descending(): void
    {
        $door1 = Door::create(['name' => 'Low Score', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'High Score', 'image_path' => 'doors/door2.jpg']);
        $door3 = Door::create(['name' => 'Medium Score', 'image_path' => 'doors/door3.jpg']);

        // Give door2 the highest score
        Vote::create(['voter_name' => 'Voter1', 'door_id' => $door2->id, 'rank' => 1]); // 3 pts
        Vote::create(['voter_name' => 'Voter2', 'door_id' => $door2->id, 'rank' => 1]); // 3 pts
        Vote::create(['voter_name' => 'Voter3', 'door_id' => $door2->id, 'rank' => 1]); // 3 pts
        // door2: 9 points

        // Give door3 medium score
        Vote::create(['voter_name' => 'Voter1', 'door_id' => $door3->id, 'rank' => 2]); // 2 pts
        Vote::create(['voter_name' => 'Voter2', 'door_id' => $door3->id, 'rank' => 2]); // 2 pts
        // door3: 4 points

        // Give door1 lowest score
        Vote::create(['voter_name' => 'Voter1', 'door_id' => $door1->id, 'rank' => 3]); // 1 pt
        // door1: 1 point

        $response = $this->get('/');
        $results = $response->viewData('results');

        // Check order: door2 (9pts), door3 (4pts), door1 (1pt)
        $this->assertEquals($door2->id, $results[0]['door']->id);
        $this->assertEquals(9, $results[0]['total_points']);

        $this->assertEquals($door3->id, $results[1]['door']->id);
        $this->assertEquals(4, $results[1]['total_points']);

        $this->assertEquals($door1->id, $results[2]['door']->id);
        $this->assertEquals(1, $results[2]['total_points']);
    }

    public function test_home_page_shows_doors_with_no_votes(): void
    {
        $door1 = Door::create(['name' => 'Popular Door', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Unpopular Door', 'image_path' => 'doors/door2.jpg']);

        Vote::create(['voter_name' => 'Voter1', 'door_id' => $door1->id, 'rank' => 1]);

        $response = $this->get('/');
        $results = $response->viewData('results');

        $this->assertCount(2, $results);

        $door2Result = collect($results)->firstWhere('door.id', $door2->id);
        $this->assertEquals(0, $door2Result['total_points']);
        $this->assertEquals(0, $door2Result['total_votes']);
    }

    public function test_home_page_displays_when_no_doors_exist(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('doors');
        $response->assertViewHas('results');

        $doors = $response->viewData('doors');
        $results = $response->viewData('results');

        $this->assertCount(0, $doors);
        $this->assertCount(0, $results);
    }

    public function test_home_page_displays_when_doors_exist_but_no_votes(): void
    {
        Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Alice');
        $response->assertSee('Bob');

        $results = $response->viewData('results');
        $this->assertCount(2, $results);

        foreach ($results as $result) {
            $this->assertEquals(0, $result['total_points']);
            $this->assertEquals(0, $result['total_votes']);
        }
    }

    public function test_home_page_passes_doors_to_view(): void
    {
        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);

        $response = $this->get('/');

        $response->assertViewHas('doors', function ($doors) use ($door1, $door2) {
            return $doors->count() === 2 &&
                   $doors->contains('id', $door1->id) &&
                   $doors->contains('id', $door2->id);
        });
    }
}
