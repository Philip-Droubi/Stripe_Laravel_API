<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('products')->delete();
        
        \DB::table('products')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'name' => 'meat',
                'amount' => 10,
                'price' => 40.0,
                'created_at' => '2024-09-30 16:41:01',
                'updated_at' => '2024-09-30 16:41:01',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'name' => 'cheese',
                'amount' => 50,
                'price' => 12.9,
                'created_at' => '2024-09-30 16:41:16',
                'updated_at' => '2024-09-30 16:41:16',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'name' => 'milk',
                'amount' => 40,
                'price' => 10.0,
                'created_at' => '2024-09-30 16:41:30',
                'updated_at' => '2024-09-30 16:41:30',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 1,
                'name' => 'battery 42Wh 12v',
                'amount' => 30,
                'price' => 50.0,
                'created_at' => '2024-09-30 16:41:53',
                'updated_at' => '2024-09-30 16:41:53',
            ),
        ));
        
        
    }
}