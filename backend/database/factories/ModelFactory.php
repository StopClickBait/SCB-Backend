<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

// Generate fake User data
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

// Generate fake Posts data
$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'text'       => $faker->realText(140),
        'article_id' => rand(1,50),
        'user_id'    => rand(1,50),
        'upvotes'    => rand(1,1000),
        'downvotes'  => rand(1,1000)
    ];
});

// Generate fake Article data
$factory->define(App\Article::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'userID'   => rand(1,50),
        'nameURI'  => str_random(20),
        'isDeleted'=> rand(0,1)
    ];
});
