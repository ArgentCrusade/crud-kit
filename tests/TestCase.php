<?php

namespace ArgentCrusade\CrudKit\Tests;

use ArgentCrusade\CrudKit\Tests\Fakes\Project;
use ArgentCrusade\CrudKit\Tests\Fakes\User;
use ArgentCrusade\CrudKit\Tests\Migrations\CreateProjectsTable;
use ArgentCrusade\CrudKit\Tests\Migrations\CreateUsersTable;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        (new CreateUsersTable())->up();
        (new CreateProjectsTable())->up();

        $factory = app(Factory::class);
        $factory->define(User::class, function (Generator $faker) {
            return [
                'name' => $faker->name,
                'email' => $faker->safeEmail,
                'password' => '$2y$10$j.wyukOehQBns1QsohRbr.tC20lkpJL2bUuH8aNBhMMB4ffM7MTpe', // 'secret'
                'timezone' => $faker->timezone,
            ];
        });

        $factory->define(Project::class, function (Generator $faker) {
            return [
                'user_id' => factory(User::class)->create()->id,
                'name' => $faker->company,
            ];
        });
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
