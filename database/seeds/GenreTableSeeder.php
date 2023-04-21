<?php

use Illuminate\Database\Seeder;

class GenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = ['Alternative', 'Blues', 'Classical', 'Country', 'Dance',
            'Easy Listening', 'Electronic', 'Hip Hop/Rap', 'Indie',
            'Gospel', 'Jazz', 'Latin', 'New Age', 'Opera', 'Pop', 'R&B/Soul', 'Reggae',
            'Rock', 'Beats'];

        foreach($genres as $key => $value){
            $genre = new \App\Genre([
                'title' => $value
            ]);

            $genre->save();
        }
    }
}
