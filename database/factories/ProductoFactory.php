<?php

use Faker\Generator as Faker;

$factory->define(App\Producto::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'descripcion' => $faker->text,
        'precio' => $faker->randomFloat(2, 0, 499),
        'stk_min' => $faker->numberBetween(1, 99),
        'stk_max' => $faker->numberBetween(100, 499),        
    ];
});
