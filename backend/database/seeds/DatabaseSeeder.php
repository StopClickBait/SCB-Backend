<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

//use database\seeds\UserTableSeeder;

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

        $this->call(UsersTableSeeder::class);       
        $this->call(ArticlesTableSeeder::class);
        $this->call(PostsTableSeeder::class);

        Model::reguard();
    }
}
