<?php

namespace Tests\Feature;

use App\Models\Door;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DoorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_can_upload_door_with_valid_data(): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        $file = UploadedFile::fake()->image('door.jpg');

        $response = $this->post('/doors', [
            'name' => 'John Doe',
            'image' => $file,
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Door uploaded successfully!');

        $this->assertDatabaseHas('doors', [
            'name' => 'John Doe',
        ]);

        $door = Door::first();
        $this->assertNotNull($door->image_path);
        Storage::disk('public')->assertExists($door->image_path);
    }

    public function test_cannot_upload_door_without_name(): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        $file = UploadedFile::fake()->image('door.jpg');

        $response = $this->post('/doors', [
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('doors', 0);
    }

    public function test_cannot_upload_door_without_image(): void
    {
        $response = $this->post('/doors', [
            'name' => 'John Doe',
        ]);

        $response->assertSessionHasErrors(['image']);
        $this->assertDatabaseCount('doors', 0);
    }

    public function test_cannot_upload_door_with_invalid_image_type(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->post('/doors', [
            'name' => 'John Doe',
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['image']);
        $this->assertDatabaseCount('doors', 0);
    }

    public function test_cannot_upload_door_with_image_too_large(): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD extension is not installed.');
        }

        $file = UploadedFile::fake()->image('door.jpg')->size(6000); // 6MB

        $response = $this->post('/doors', [
            'name' => 'John Doe',
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['image']);
        $this->assertDatabaseCount('doors', 0);
    }

    public function test_can_delete_door(): void
    {
        $door = Door::create([
            'name' => 'John Doe',
            'image_path' => 'doors/test.jpg',
        ]);

        $response = $this->delete("/doors/{$door->id}");

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Door deleted successfully!');
        $this->assertDatabaseCount('doors', 0);
    }

    public function test_door_has_votes_relationship(): void
    {
        $door = Door::create([
            'name' => 'John Doe',
            'image_path' => 'doors/test.jpg',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $door->votes);
    }

    public function test_get_vote_count_returns_correct_count(): void
    {
        $door = Door::create([
            'name' => 'John Doe',
            'image_path' => 'doors/test.jpg',
        ]);

        $door->votes()->createMany([
            ['voter_name' => 'Alice', 'rank' => 1],
            ['voter_name' => 'Bob', 'rank' => 2],
            ['voter_name' => 'Charlie', 'rank' => 3],
        ]);

        $this->assertEquals(3, $door->getVoteCount());
    }

    public function test_get_total_points_calculates_correctly(): void
    {
        $door = Door::create([
            'name' => 'John Doe',
            'image_path' => 'doors/test.jpg',
        ]);

        // 1st place = 3 points, 2nd place = 2 points, 3rd place = 1 point
        $door->votes()->createMany([
            ['voter_name' => 'Alice', 'rank' => 1],   // 3 points
            ['voter_name' => 'Bob', 'rank' => 2],     // 2 points
            ['voter_name' => 'Charlie', 'rank' => 3], // 1 point
        ]);

        $this->assertEquals(6, $door->getTotalPoints());
    }

    public function test_multiple_first_place_votes(): void
    {
        $door = Door::create([
            'name' => 'John Doe',
            'image_path' => 'doors/test.jpg',
        ]);

        $door->votes()->createMany([
            ['voter_name' => 'Alice', 'rank' => 1],   // 3 points
            ['voter_name' => 'Bob', 'rank' => 1],     // 3 points
            ['voter_name' => 'Charlie', 'rank' => 1], // 3 points
        ]);

        $this->assertEquals(9, $door->getTotalPoints());
    }
}
