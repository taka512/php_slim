<?php

namespace Taka512\Test\Functional\Repository;

use Taka512\Test\DatabaseTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Model\User;

class UserRepositoryTest extends DatabaseTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/UserRepository.yml');
    }

    /**
     * @dataProvider providerInsert
     */
    public function testInsert($msg, $data, $expected)
    {
        $actual = $this->get('repository.user')->insert($data);
        $this->assertSame($expected, $actual);
    }

    public function providerInsert()
    {
        return [
            [
                'insert success and return id:2',
                [
                    'login_id' => 'login_id2',
                    'password' => 'password',
                ],
                2
            ],
        ];
    }

    /**
     * @dataProvider providerFindOneById
     */
    public function testFindOneById($msg, $id, $expected)
    {
        $actual = $this->get('repository.user')->findOneById($id);
        $this->assertSame($expected, ($actual instanceof User));
    }

    public function providerFindOneById()
    {
        return [
            ['case id:1 is found', 1, true],
            ['case id:2 is not found(not User)', 2, false],
        ];
    }

    /**
     * @dataProvider providerFindOneByLoginId
     */
    public function testFindOneByLoginId($msg, $loginId, $expected)
    {
        $actual = $this->get('repository.user')->findOneByLoginId($loginId);
        $this->assertSame($expected, ($actual instanceof User));
    }

    public function providerFindOneByLoginId()
    {
        return [
            ['case login_id:test_user is found', 'test_user', true],
            ['case login_id:hoge is not found(not User)', 'hoge', false],
        ];
    }

    /**
     * @dataProvider providerFindLatestUsers
     */
    public function testFindLatestUsers($msg, $limit, $expected)
    {
        $actual = $this->get('repository.user')->findLatestUsers($limit);
        $this->assertCount($expected, $actual);
    }

    public function providerFindLatestUsers()
    {
        return [
            ['user count is 1', 10, 1],
        ];
    }
}
