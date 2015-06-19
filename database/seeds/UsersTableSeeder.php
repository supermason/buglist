<?php

/**
 * Description of UsersTableSeeder
 *
 * @author mason.ding
 */

use App\User;

class UsersTableSeeder extends Illuminate\Database\Seeder {
    
    public function run() {
        
        User::create([
            'name' => 'echo',
            'email' => 'chenwy@etsoo.com',
            'password' => Hash::make('111111'),
        ]);
        
        User::create([
            'name' => 'mason',
            'email' => 'mason.ding@etsoo.com',
            'password' => Hash::make('111111'),
        ]);
    }
}
