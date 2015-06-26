<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        
        $this->call('UsersTableSeeder');
        $this->command->info('Users Table 成功填充！');
//        $this->call('BugsTableSeeder');
//        $this->command->info('Bugs Table 成功填充！');
        
        Model::reguard();
    }
}
