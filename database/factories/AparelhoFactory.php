<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Aparelho;
use Faker\Generator as Faker;

$factory->define(\App\Models\Aparelho::class, function (Faker $faker) {
    return [
        'codigo' => $faker->randomNumber(6),
        'descricao' => $faker->realText(100)
    ];
});
