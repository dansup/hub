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


$factory->define(App\Node::class, function ($faker) {
    $ip = substr_replace($faker->ipv6, 'fc', 0, 2);
    return [
        'public_key' => implode('',$faker->RandomElements([
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        ], $count = 52)) . ".k",
        'addr' => $ip,
        'hostname' => $faker->userName.$faker->domainName,
        'ownername' => $faker->firstName,
        'city' => $faker->city,
        'province' => $faker->state,
        'country' => $faker->country,
        'version' => rand(14,17),
        'latency' => rand(10,665),
        'bio' => $faker->catchPhrase.' '.$faker->realText(rand(30,46)),
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
        'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
    ];
});

$factory->define(App\Service::class, function ($faker) {
    return [
        'name'          => $faker->company,
        'url'           => $faker->url,
        'addr'          => 'fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5',
        'port'          => 80,
        'protocol'      => 'http',
        'bio'           => $faker->paragraph,
        'city'          => $faker->city,
        'country'       => $faker->country,
    ];
});

$factory->define(App\User::class, function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => str_random(10),
        'remember_token' => str_random(10),
    ];
});
