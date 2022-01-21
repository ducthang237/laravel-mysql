<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reporter = Role::where('slug', 'reporter')->first();
        $editor = Role::where('slug', 'editor')->first();

        $user1 = User::create([
            'name' => 'Reporter 1', 
            'email' => 'reporter1@example.com',
            'password' => bcrypt('123456')
        ]);
        $user1->roles()->attach($reporter);

        $user2 = User::create([
            'name' => 'Reporter 2', 
            'email' => 'reporter2@example.com',
            'password' => bcrypt('123456')
        ]);
        $user2->roles()->attach($reporter);

        $user3 = User::create([
            'name' => 'Editor 1', 
            'email' => 'editor1@example.com',
            'password' => bcrypt('123456')
        ]);
        $user3->roles()->attach($editor);
    }
}
