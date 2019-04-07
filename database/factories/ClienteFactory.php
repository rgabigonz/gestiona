<?php

use Faker\Generator as Faker;

$factory->define(App\Cliente::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'direccion' => $faker->streetAddress,
        'correo_electronico' => $faker->unique()->safeEmail,
        'telefono' => $faker->phoneNumber,
        'tipo_documento' => 1,
        'numero_documento' => $faker->unique()->numberBetween(24000000, 30000000),
    ];
});
