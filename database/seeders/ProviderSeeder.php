<?php

namespace Database\Seeders;

use App\Enums\SocialiteProvider;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = SocialiteProvider::cases();

        collect($providers)->each(function ($case) {
            Provider::create(['name' => $case->name]);
        });
    }
}
