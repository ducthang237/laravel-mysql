<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Post;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author1 = User::where('email', 'reporter1@example.com')->first();
        $author2 = User::where('email', 'reporter2@example.com')->first();
        $faker = \Faker\Factory::create();
        for ($i=0; $i < 10; $i++) { 
          $title = $faker->sentence($nbWords = 6, $variableNbWords = true);
          $post = Post::create([
              'title' => $title, 
              'body' => $faker->text($maxNbChars = 1000),
              'slug' => Str::slug($title),
              'published' => rand(0,1),
              'user_id' => $author1->id
          ]);
          $title = $faker->sentence($nbWords = 6, $variableNbWords = true);
          $post = Post::create([
              'title' => $title, 
              'body' => $faker->text($maxNbChars = 1000),
              'slug' => Str::slug($title),
              'published' => rand(0,1),
              'user_id' => $author2->id
          ]);
        }
    }
}
