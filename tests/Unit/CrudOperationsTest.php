<?php

namespace ArgentCrusade\CrudKit\Tests\Unit;

use ArgentCrusade\CrudKit\Tests\Fakes\FakeRequest;
use ArgentCrusade\CrudKit\Tests\Fakes\User;
use ArgentCrusade\CrudKit\Tests\ResetsCrudManager;
use ArgentCrusade\CrudKit\Tests\TestCase;

class CrudOperationsTest extends TestCase
{
    use ResetsCrudManager;

    public function testItShouldCreateResources()
    {
        $request = new FakeRequest([
            'name' => 'John Doe',
            'email' => 'example@example.org',
            'password' => 'secret',
            'timezone' => 'Europe/Moscow',
        ]);

        /** @var User $user */
        $user = $this->manager->create($request);
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('John Doe', $user->name);
        $this->assertSame('example@example.org', $user->email);
    }

    public function testItShouldCallResourceAndUserCallbacksAfterCreateOperation()
    {
        $request = new FakeRequest([
            'name' => 'John Doe',
            'email' => 'example@example.org',
            'password' => 'secret',
            'timezone' => 'Europe/Moscow',
        ]);

        $this->assertSame(0, User::where('email', 'example@example.org')->count());
        $this->assertNull($this->manager->resource()->getLastEvent());

        /** @var User $user */
        $user = $this->manager->create($request, function (User $user) {
            $this->assertSame('John Doe', $user->name);

            return tap($user)->update(['name' => 'Jane Doe']);
        });

        $this->assertSame('created', $this->manager->resource()->getLastEvent());
        $this->assertSame(1, User::where('email', 'example@example.org')->count());

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Jane Doe', $user->name);
        $this->assertSame('example@example.org', $user->email);
    }

    public function testItShouldRevertAllChangesIfSomethingGoesWrongWhileCreatingResource()
    {
        $request = new FakeRequest([
            'name' => 'John Doe',
            'email' => 'example@example.org',
            'password' => 'secret',
            'timezone' => 'Europe/Moscow',
        ]);

        $this->assertSame(0, User::where('email', 'example@example.org')->count());

        $gotException = false;

        try {
            $this->manager->create($request, function () {
                throw new \BadMethodCallException('Oops');
            });
        } catch (\BadMethodCallException $e) {
            $gotException = true;
        }

        $this->assertTrue($gotException);
        $this->assertSame(0, User::where('email', 'example@example.org')->count());
    }

    public function testItShouldUpdateResources()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['name' => 'John Doe']);
        $this->assertSame('John Doe', $user->name);

        $request = new FakeRequest(['name' => 'Jane Doe']);

        /** @var User $user */
        $user = $this->manager->update($request, $user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Jane Doe', $user->name);
    }

    public function testItShouldCallResourceAndUserCallbacksAfterUpdateOperation()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['name' => 'John Doe', 'email' => 'john@example.org']);
        $this->assertSame('John Doe', $user->name);
        $this->assertSame('john@example.org', $user->email);

        $request = new FakeRequest(['name' => 'Jane Doe']);

        $this->assertNull($this->manager->resource()->getLastEvent());

        /** @var User $user */
        $user = $this->manager->update($request, $user, function (User $user) {
            return tap($user)->update(['email' => 'jane@example.org']);
        });

        $this->assertSame('updated', $this->manager->resource()->getLastEvent());
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Jane Doe', $user->name);
        $this->assertSame('jane@example.org', $user->email);
    }

    public function testItShouldRevertAllChangesIfSomethingGoesWrongWhileUpdatingResource()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['name' => 'John Doe', 'email' => 'john@example.org']);
        $this->assertSame('John Doe', $user->name);

        $request = new FakeRequest(['name' => 'Jane Doe']);
        $gotException = false;

        try {
            $this->manager->update($request, $user, function () {
                throw new \BadMethodCallException('Oops');
            });
        } catch (\BadMethodCallException $e) {
            $gotException = true;
        }

        $this->assertTrue($gotException);
        $this->assertSame('John Doe', $user->name);
    }

    public function testItShouldDeleteResources()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['email' => 'john@example.org']);
        $this->assertSame(1, User::where('email', 'john@example.org')->count());

        $result = $this->manager->destroy(new FakeRequest(), $user);
        $this->assertTrue($result);
        $this->assertSame(0, User::where('email', 'john@example.org')->count());
    }

    public function testItShouldCallResourceAndUserCallbacksAfterDeleteOperation()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['email' => 'john@example.org']);
        $this->assertSame(1, User::where('email', 'john@example.org')->count());

        $flag = false;
        $this->assertNull($this->manager->resource()->getLastEvent());

        $this->manager->destroy(new FakeRequest(), $user, function () use (&$flag) {
            $flag = true;
        });

        $this->assertSame('destroyed', $this->manager->resource()->getLastEvent());
        $this->assertTrue($flag);
        $this->assertSame(0, User::where('email', 'john@example.org')->count());
    }

    public function testItShouldRevertAllChangesIfSomethingGoesWrongWhileDeletingResource()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['email' => 'john@example.org']);
        $this->assertSame(1, User::where('email', 'john@example.org')->count());

        $gotException = false;

        try {
            $this->manager->destroy(new FakeRequest(), $user, function () {
                throw new \BadMethodCallException('Oops');
            });
        } catch (\BadMethodCallException $e) {
            $gotException = true;
        }

        $this->assertTrue($gotException);
        $this->assertSame(1, User::where('email', 'john@example.org')->count());
    }
}
