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
            $subscribers = User::whereNot('id', $user->id)
                ->get()
                ->random(3);

            Blog::factory()
                ->for($user)
                ->hasAttached(
                    factory: $subscribers,
                    relationship: 'subscribers'
                )
                ->create();

            //Blog::factory()->for($user)->create()
            //    ->subscribers()
            //    ->sync($subscribers);
        });
    }
}
