<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        $users = User::all();

        Message::factory()
            ->count(50)
            ->sequence(fn () => [
                'event_id' => $events->random(),
            ])
            ->sequence(fn () => [
                'sender_id' => $users->random(),
            ])
            ->create();
    }
}
