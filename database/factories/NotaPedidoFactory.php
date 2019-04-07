<?php

use Faker\Generator as Faker;

$factory->define(App\NotaPedido::class, function (Faker $faker) {
    return [
        'cliente_id' => 1,
        'user_id' => 1,
        'total' => $faker->randomFloat(2, 0, 499)
    ];
});
