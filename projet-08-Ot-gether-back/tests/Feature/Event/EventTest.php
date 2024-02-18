<?php

namespace Tests\Feature\Event;

use App\Models\EventCategory;
use App\Models\EventTag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_can_be_created(): void
    {
        $user = User::factory()->create();
        $category = EventCategory::create(['name' => 'test']);
        $tag = EventTag::create(['name' => 'testTag']);

        $response = $this->actingAs($user)->post('/api/v1/events', [
            'event_category_id' => $category->id,
            'event_tag_id' => $tag->id,
            'creator_id' => $user->id,
            'title' => 'titre',
            'slug' => 'titre',
            'description' => 'description',
            'address' => 'address',
            'city' => 'city',
            'region' => 'region',
            'is_IRL' => true,
            'picture' => 'picture',
            'participant_min' => 5,
            'participant_max' => 10,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),
        ]);

        $response->assertStatus(201);
    }
}
