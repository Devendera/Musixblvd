<?php

use Illuminate\Database\Seeder;

class PlatformTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $platforms = ['Apple Music', 'Spotify', 'Google Play', 'Tidal', 'Amazon',
            'Pandora', 'Deezer', 'Youtube', 'SoundCloud'];

        foreach($platforms as $key => $value){
            $platform = new \App\Platform([
                'title' => $value,
                'logo' => 'logo.jpg'
            ]);

            $platform->save();
        }
    }
}
