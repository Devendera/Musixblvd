<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(StateTableSeeder::class);
         $this->call(CityTableSeeder::class);
         /*$this->call(CraftTableSeeder::class);
         $this->call(GenreTableSeeder::class);
         $this->call(PlatformTableSeeder::class);
         $this->call(TypeTableSeeder::class);
         $this->call(GenderTableSeeder::class);*/
    }
}
