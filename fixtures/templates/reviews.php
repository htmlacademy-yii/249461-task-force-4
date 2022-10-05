<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'task_id'=>$faker->numberBetween(1,50),
    'worker_id' => $faker->numberBetween(11,20),
    'author_id' => $faker->numberBetween(1,10),
    'review' => $faker->text(400),
    'mark'=>$faker->numberBetween(0,5),
];