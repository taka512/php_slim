<?php

namespace Taka512\Test\Functional\Repository;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Manager\EntityManager;
use Taka512\Model\User;
use Taka512\Test\DatabaseTestCase;

class UserRepositoryTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/UserRepository.yml');
        $this->get(EntityManager::class)->truncateTables(['user']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testInsert()
    {
        $data = [
            'login_id' => 'login_id2',
            'password' => 'password',
        ];
        $actual = $this->get('repository.user')->insert($data);
        $this->assertSame(2, $actual, 'case:insert success and return id');
    }

    public function testFindOneById()
    {
        $actual = $this->get('repository.user')->findOneById(1);
        $this->assertInstanceOf(User::class, $actual, 'case:id is found');

        $actual = $this->get('repository.user')->findOneById(99);
        $this->assertNull($actual, 'case:id is not found');
    }

    public function testFindOneByLoginId()
    {
        $actual = $this->get('repository.user')->findOneByLoginId('test_user');
        $this->assertInstanceOf(User::class, $actual, 'case:login_id is found');

        $actual = $this->get('repository.user')->findOneByLoginId('hogehoge');
        $this->assertNull($actual, 'case:login_id is not found');
    }

    public function testFindLatestUsers()
    {
        $actual = $this->get('repository.user')->findLatestUsers(10);
        $this->assertCount(1, $actual, 'case: search user');
    }
}
