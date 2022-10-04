<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'task_id'=>$faker->numberBetween(1,50),
    'user_id' => $faker->numberBetween(1,10),
    'comment' => $faker->text(150),
    'price'=>$faker->numberBetween(100,2000),
    'rejected'=>$faker->boolean ? 1 : 0,
];