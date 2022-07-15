<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {
            $subscribers = User::inRandomOrder()
                ->where('id', '<>', $user->id)
                ->limit(3)
                ->get();

            Blog::factory()->for($user)->hasAttached(
                factory: $subscribers,
                relationship: 'subscribers'
            )->create();
        });
    }
}
