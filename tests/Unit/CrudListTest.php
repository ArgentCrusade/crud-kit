<?php

namespace ArgentCrusade\CrudKit\Tests\Unit;

use ArgentCrusade\CrudKit\Tests\Fakes\User;
use ArgentCrusade\CrudKit\Tests\Fakes\UsersRepository;
use ArgentCrusade\CrudKit\Tests\ResetsCrudManager;
use ArgentCrusade\CrudKit\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CrudListTest extends TestCase
{
    use ResetsCrudManager;

    public function testItShouldGenerateBasicListRequest()
    {
        $users = factory(User::class, 5)->create();
        $this->assertSame(5, count($users));

        $repository = $this->manager->list(new Request());
        $this->assertInstanceOf(UsersRepository::class, $repository);

        $this->assertSame(5, $repository->all()->count());
    }

    /**
     * @param string $orderBy
     * @param array  $orderMap
     *
     * @dataProvider orderingDataProvider
     */
    public function testItShouldApplyOrdering(string $orderBy, array $orderMap)
    {
        $users = factory(User::class, 5)->create();
        $this->assertSame(5, count($users));

        $repository = $this->manager->list(new Request(['order_by' => $orderBy]));
        $this->assertInstanceOf(UsersRepository::class, $repository);

        /** @var Collection $items */
        $items = $repository->get();
        $this->assertSame(5, $items->count());

        foreach ($orderMap as $collectionIndex => $expectedId) {
            $this->assertSame($expectedId, $items->get($collectionIndex)->id);
        }
    }

    /**
     * @param array $emails
     * @param array $query
     * @param int   $expected
     *
     * @dataProvider filtersDataProvider
     */
    public function testItShouldApplyFilters(array $emails, array $query, int $expected)
    {
        collect($emails)->each(function (string $email) {
            factory(User::class)->create(['email' => $email]);
        });

        $items = $this->manager->list(new Request($query))->get();
        $this->assertSame($expected, count($items));
    }

    public function testItShouldAllowToModifyRepositoryOnTheFly()
    {
        /** @var User $firstUser */
        /** @var User $secondUser */
        list($firstUser, $secondUser) = factory(User::class, 2)->create();

        $firstUser->projects()->createMany([
            ['name' => 'First'],
            ['name' => 'Second'],
        ]);

        $secondUser->projects()->createMany([
            ['name' => 'Third'],
        ]);

        /** @var User[] $items */
        $items = $this->manager
            ->list(new Request(['order_by' => 'id']), function (UsersRepository $repository) {
                return $repository->with(['projects']);
            })
            ->get();

        $this->assertSame(2, count($items));
        $this->assertTrue($items[0]->relationLoaded('projects'));
        $this->assertSame(2, $items[0]->projects()->count());
        $this->assertTrue($items[1]->relationLoaded('projects'));
        $this->assertSame(1, $items[1]->projects()->count());
    }

    public static function orderingDataProvider()
    {
        return [
            [
                'orderBy' => '-id',
                'orderMap' => [5, 4, 3, 2, 1],
            ],
            [
                'orderBy' => 'id',
                'orderMap' => [1, 2, 3, 4, 5],
            ],
        ];
    }

    public static function filtersDataProvider()
    {
        return [
            [
                'emails' => [
                    'first@example.org', 'second@example.org', 'third@example.org',
                    'first@example.com', 'second@example.com', 'third@example.com', 'fourth@example.com',
                ],
                'query' => ['email_domain' => 'example.org'],
                'expected' => 3,
            ],
            [
                'emails' => [
                    'first@example.org', 'second@example.org', 'third@example.org',
                    'first@example.com', 'second@example.com', 'third@example.com', 'fourth@example.com',
                ],
                'query' => ['email_domain' => 'example.com'],
                'expected' => 4,
            ],
        ];
    }
}
