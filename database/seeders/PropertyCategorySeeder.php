<?php

namespace Database\Seeders;

use App\Models\PropertyCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Residential', 'Commercial'];

        foreach ($categories as $cat) {
            PropertyCategory::updateOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat, 'is_active' => true]
            );
        }
    }
}
