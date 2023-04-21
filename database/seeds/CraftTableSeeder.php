<?php

use Illuminate\Database\Seeder;

class CraftTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $crafts = ['Vocalist', 'Rapper', 'Engineer', 'Producer', 'Writer',
            'Percussion', 'Strings', 'Brass', 'Woodwind'];

        foreach($crafts as $key => $value){
            $craft = new \App\Craft([
                'title' => $value
            ]);

            $craft->save();
        }
    }
}
