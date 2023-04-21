<?php

use Illuminate\Database\Seeder;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = ['Male', 'Female'];

        foreach($genders as $key => $value){
            $craft = new \App\Gender([
                'title' => $value
            ]);

            $craft->save();
        }
    }
}
