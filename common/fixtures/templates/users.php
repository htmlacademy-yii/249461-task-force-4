<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'name' => $faker->name,
    'email' => $faker->email,
    'password' => $faker->password(8),
    'is_worker'=>$faker->boolean ? 1 : 0,
    'avatar' => 'img/man-glasses.png',
    'birthday'=>$faker->date,
    'city_id'=>$faker->numberBetween(1,1087),
    'phone' => substr($faker->e164PhoneNumber, 1, 10),
    'telegram' => $faker->userName,
    'about_me' => $faker->text(400),
    'show_contacts'=>$faker->boolean ? 1 : 0,
    'tasks_completed'=>$faker->numberBetween(1,5000),
    'tasks_failed'=>$faker->numberBetween(1,5000),
    'rating'=>$faker->numberBetween(0,5),
];