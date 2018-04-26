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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'slug' => $faker->slug,
        'name' => $faker->sentence(),
        'active' => $faker->boolean(),
        'created_by' => $faker->randomNumber(),
        'updated_by' => null,
        'deleted_by' => null
    ];
});

$factory->define(App\Client::class, function (Faker\Generator $faker) {
    return [
        'slug' => $faker->slug,
        'name' => $faker->sentence(),
        'active' => $faker->boolean(),
        'created_by' => $faker->randomNumber(),
        'updated_by' => null,
        'deleted_by' => null
    ];
});

$factory->define(App\Contact::class, function (Faker\Generator $faker) {
    return [
        'slug' => $faker->slug,
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'active' => $faker->boolean(),
        'client_id' => function () {
            return factory(App\Client::class)->create()->id;
        },
        'created_by' => $faker->randomNumber(),
        'updated_by' => null,
        'deleted_by' => null
    ];
});

$factory->define(App\Provider::class, function (Faker\Generator $faker) {
    return [
        'slug' => $faker->slug,
        'name' => $faker->name,
        'active' => $faker->boolean(),
    ];
});

$factory->define(App\Scope::class, function (Faker\Generator $faker) {
    return [
        'slug' => $faker->slug,
        'name' => $faker->name,
        'active' => $faker->boolean(),
        'provider_id' => function () {
            return factory(App\Provider::class)->create()->id;
        },
        'project_id' => function () {
            return factory(App\Project::class)->create()->id;
        }
    ];
});

$factory->define(App\Additional::class, function (Faker\Generator $faker) {
    return [
        'additionable_id' => $faker->randomNumber(),
        'additionable_type' => $faker->randomElement(['project', 'client']),
        'key' => $faker->word(),
        'value_text' => null,
        'value_int' => $faker->randomNumber(),
    ];
});

$factory->state(App\Additional::class, 'project', [
    'additionable_type' => 'project',
]);

$factory->state(App\Additional::class, 'client', [
    'additionable_type' => 'client',
]);