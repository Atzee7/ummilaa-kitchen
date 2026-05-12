<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Dimsum',        'icon' => '🥟'],
            ['name' => 'Risol',         'icon' => '🥐'],
            ['name' => 'Perpentolan',   'icon' => '🍢'],
            ['name' => 'Crunchy Series','icon' => '🍗'],
            ['name' => 'Minuman',       'icon' => '🥤'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                ['name' => $cat['name'], 'icon' => $cat['icon'], 'slug' => Str::slug($cat['name'])]
            );
        }
    }
}