<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'title' => $faker->text(255),
    'description' => $faker->text(500),
    'category_id' => $faker->numberBetween(1,8),
    'price'=>$faker->numberBetween(1,5000),
    'author_id'=>$faker->numberBetween(11,20),
    'worker_id'=>$faker->numberBetween(0,10),
    'status' => $faker->randomElement(['new', 'canceled', 'progress', 'completed', 'failed']),
    'address'=>$faker->address,
    'city_id'=>$faker->numberBetween(1,1087),
    'lat' => $faker->randomFloat(7,10,140),
    'lng' => $faker->randomFloat(7,10,140),
];