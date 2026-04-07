<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'بروتين',    'slug' => 'protein',  'emoji' => '💪', 'sort_order' => 1],
            ['name' => 'فيتامينات', 'slug' => 'vitamins', 'emoji' => '💊', 'sort_order' => 2],
            ['name' => 'رياضة',     'slug' => 'sport',    'emoji' => '🏋️', 'sort_order' => 3],
            ['name' => 'أوميغا',    'slug' => 'omega',    'emoji' => '🐟', 'sort_order' => 4],
            ['name' => 'أعشاب',     'slug' => 'herbs',    'emoji' => '🌿', 'sort_order' => 5],
            ['name' => 'بشرة',      'slug' => 'skin',     'emoji' => '✨', 'sort_order' => 6],
            ['name' => 'الوزن',     'slug' => 'weight',   'emoji' => '⚖️', 'sort_order' => 7],
            ['name' => 'طاقة',      'slug' => 'energy',   'emoji' => '⚡', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
