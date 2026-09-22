<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(10)->create();
        User::factory(5)->create();
        $posts = Post::factory(50)
                        ->recycle(Category::all())
                        ->recycle(User::all())
                        ->create();
        $tags = Tag::factory(20)->create();

        foreach ($posts as $post) {
            $post->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')
            );
        }

    }
}
