<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Philip',
                'email' => 'philip@email.com',
                'password' => '$2y$12$yhFLH/tKg3y.2pyt6ntEk.RS8z2LYzUUOclirwpDxf2gMfUZpMDbe',
                'created_at' => '2024-09-30 17:42:53',
                'updated_at' => '2024-09-30 17:42:53',
            ),
        ));
        
        
    }
}