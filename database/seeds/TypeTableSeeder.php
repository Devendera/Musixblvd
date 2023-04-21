<?php

use Illuminate\Database\Seeder;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Solo', 'Group', 'Band'];

        foreach($types as $key => $value){
            $type = new \App\Type([
                'title' => $value
            ]);

            $type->save();
        }
    }
}
