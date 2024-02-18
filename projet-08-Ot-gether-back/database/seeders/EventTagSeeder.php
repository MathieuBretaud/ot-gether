<?php

namespace Database\Seeders;

use App\Models\EventTag;
use Illuminate\Database\Seeder;

class EventTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🖖 🦶🏻 🤖 🫶🏻 👁️ 🎉 🎠🎂 oui emoji > all

        $tag = collect(['En cours', 'Terminé', 'Supprimé', 'Fermé']);
        $tag->map(fn ($tag) => EventTag::create([
            'name' => $tag,
        ]));
    }
}
