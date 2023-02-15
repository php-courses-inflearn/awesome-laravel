<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::all()->each(function (Blog $blog) {
            Post::factory(3)->for($blog)->hasAttachments()->create();
        });
    }
}
