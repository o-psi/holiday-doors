<?php

namespace Tests\Feature;

use App\Models\Door;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_votes_with_valid_data(): void
    {
        config(['voting.enabled' => true]);

        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);
        $door3 = Door::create(['name' => 'Charlie', 'image_path' => 'doors/door3.jpg']);

        $response = $this->post('/vote', [
            'voter_name' => 'John Doe',
            'votes' => [$door1->id, $door2->id, $door3->id],
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Thanks for voting, John Doe!');

        $this->assertDatabaseHas('votes', [
            'voter_name' => 'John Doe',
            'door_id' => $door1->id,
            'rank' => 1,
        ]);

        $this->assertDatabaseHas('votes', [
            'voter_name' => 'John Doe',
            'door_id' => $door2->id,
            'rank' => 2,
        ]);

        $this->assertDatabaseHas('votes', [
            'voter_name' => 'John Doe',
            'door_id' => $door3->id,
            'rank' => 3,
        ]);
    }

    public function test_can_submit_partial_votes(): void
    {
        config(['voting.enabled' => true]);

        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);

        $response = $this->post('/vote', [
            'voter_name' => 'John Doe',
            'votes' => [$door1->id, $door2->id],
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseCount('votes', 2);
    }

    public function test_cannot_vote_without_voter_name(): void
    {
        config(['voting.enabled' => true]);

        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);

        $response = $this->post('/vote', [
            'votes' => [$door1->id],
        ]);

        $response->assertSessionHasErrors(['voter_name']);
        $this->assertDatabaseCount('votes', 0);
    }

    public function test_cannot_vote_without_any_votes(): void
    {
        config(['voting.enabled' => true]);

        $response = $this->post('/vote', [
            'voter_name' => 'John Doe',
            'votes' => [],
        ]);

        $response->assertSessionHasErrors(['votes']);
        $this->assertDatabaseCount('votes', 0);
    }

    public function test_cannot_vote_for_nonexistent_door(): void
    {
        config(['voting.enabled' => true]);

        $response = $this->post('/vote', [
            'voter_name' => 'John Doe',
            'votes' => [999],
        ]);

        $response->assertSessionHasErrors(['votes.0']);
        $this->assertDatabaseCount('votes', 0);
    }

    public function test_replaces_existing_votes_for_same_voter(): void
    {
        config(['voting.enabled' => true]);

        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);
        $door3 = Door::create(['name' => 'Charlie', 'image_path' => 'doors/door3.jpg']);

        // First vote
        $this->post('/vote', [
            'voter_name' => 'John Doe',
            'votes' => [$door1->id, $door2->id],
        ]);

        $this->assertDatabaseCount('votes', 2);

        // Second vote - should replace first vote
        $this->post('/vote', [
            'voter_name' => 'John Doe',
            'votes' => [$door3->id, $door2->id, $door1->id],
        ]);

        $this->assertDatabaseCount('votes', 3);

        // Check new rankings
        $this->assertDatabaseHas('votes', [
            'voter_name' => 'John Doe',
            'door_id' => $door3->id,
            'rank' => 1,
        ]);

        $this->assertDatabaseHas('votes', [
            'voter_name' => 'John Doe',
            'door_id' => $door2->id,
            'rank' => 2,
        ]);

        $this->assertDatabaseHas('votes', [
            'voter_name' => 'John Doe',
            'door_id' => $door1->id,
            'rank' => 3,
        ]);
    }

    public function test_different_voters_can_vote_independently(): void
    {
        config(['voting.enabled' => true]);

        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);

        $this->post('/vote', [
            'voter_name' => 'John Doe',
            'votes' => [$door1->id],
        ]);

        $this->post('/vote', [
            'voter_name' => 'Jane Smith',
            'votes' => [$door2->id],
        ]);

        $this->assertDatabaseCount('votes', 2);

        $this->assertDatabaseHas('votes', [
            'voter_name' => 'John Doe',
            'door_id' => $door1->id,
        ]);

        $this->assertDatabaseHas('votes', [
            'voter_name' => 'Jane Smith',
            'door_id' => $door2->id,
        ]);
    }

    public function test_vote_has_door_relationship(): void
    {
        $door = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $vote = Vote::create([
            'voter_name' => 'John Doe',
            'door_id' => $door->id,
            'rank' => 1,
        ]);

        $this->assertInstanceOf(Door::class, $vote->door);
        $this->assertEquals($door->id, $vote->door->id);
    }

    public function test_get_points_returns_correct_values(): void
    {
        $door = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);

        $vote1 = Vote::create(['voter_name' => 'Alice', 'door_id' => $door->id, 'rank' => 1]);
        $vote2 = Vote::create(['voter_name' => 'Bob', 'door_id' => $door->id, 'rank' => 2]);
        $vote3 = Vote::create(['voter_name' => 'Charlie', 'door_id' => $door->id, 'rank' => 3]);

        $this->assertEquals(3, $vote1->getPoints());
        $this->assertEquals(2, $vote2->getPoints());
        $this->assertEquals(1, $vote3->getPoints());
    }

    public function test_ranking_calculation_for_multiple_doors(): void
    {
        config(['voting.enabled' => true]);

        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);
        $door3 = Door::create(['name' => 'Charlie', 'image_path' => 'doors/door3.jpg']);

        // Voter 1: door1=1st, door2=2nd, door3=3rd
        $this->post('/vote', [
            'voter_name' => 'Voter1',
            'votes' => [$door1->id, $door2->id, $door3->id],
        ]);

        // Voter 2: door2=1st, door1=2nd, door3=3rd
        $this->post('/vote', [
            'voter_name' => 'Voter2',
            'votes' => [$door2->id, $door1->id, $door3->id],
        ]);

        // Voter 3: door1=1st, door3=2nd, door2=3rd
        $this->post('/vote', [
            'voter_name' => 'Voter3',
            'votes' => [$door1->id, $door3->id, $door2->id],
        ]);

        // Calculate points:
        // door1: 3 + 2 + 3 = 8 points
        // door2: 2 + 3 + 1 = 6 points
        // door3: 1 + 1 + 2 = 4 points

        $this->assertEquals(8, $door1->fresh()->getTotalPoints());
        $this->assertEquals(6, $door2->fresh()->getTotalPoints());
        $this->assertEquals(4, $door3->fresh()->getTotalPoints());
    }

    public function test_cannot_vote_when_voting_is_disabled(): void
    {
        config(['voting.enabled' => false]);

        $door1 = Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);
        $door2 = Door::create(['name' => 'Bob', 'image_path' => 'doors/door2.jpg']);

        $response = $this->post('/vote', [
            'voter_name' => 'John Doe',
            'votes' => [$door1->id, $door2->id],
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Voting is currently disabled.');
        $this->assertDatabaseCount('votes', 0);
    }

    public function test_voting_form_hidden_when_disabled(): void
    {
        config(['voting.enabled' => false]);

        Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Voting Not Open Yet');
        $response->assertDontSee('Submit Votes');
    }

    public function test_voting_form_visible_when_enabled(): void
    {
        config(['voting.enabled' => true]);

        Door::create(['name' => 'Alice', 'image_path' => 'doors/door1.jpg']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Submit Votes');
        $response->assertDontSee('Voting Not Open Yet');
    }
}
