<?php

namespace App;

class Generator
{
    public static function generate($count)
    {
        $numbers = range(1, 100);
        shuffle($numbers);

        $faker = \Faker\Factory::create();
        $faker->seed(1);
        $companies = [];
        for ($i = 0; $i < $count; $i++) {
            $companies[] = [
                'id' => $numbers[$i],
                'name' => $faker->company,
                'phone' => $faker->phoneNumber
            ];
        }

        return $companies;
    }
}
