<?php

namespace Taka512\Test\E2e\Controller\Api;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Test\E2eTestCase;
use Taka512\Http\ClientFactory;

class TagControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/TagController.yml');
    }

    /**
     * @dataProvider providerIndex
     */
    public function testIndex($msg, $query, $expected)
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $crawler = $client->request('GET', '/api/tag'.$query);
        $this->assertSame(200, $client->getResponse()->getStatus(), 'ステータスコードが200');
        $this->assertSame($expected, json_decode($client->getResponse()->getContent(), true), 'コンテンツ');
    }

    public function providerIndex()
    {
        return [
            [
                'query no parameter',
                '',
                [
                    [
                        'id' => 3,
                        'name' => 'hoge1',
                    ],
                    [
                        'id' => 2,
                        'name' => 'tag2',
                    ],
                    [
                        'id' => 1,
                        'name' => 'tag1',
                    ],
                ],
            ],
            [
                'name parameter is [tag]',
                '?name=tag',
                [
                    [
                        'id' => 2,
                        'name' => 'tag2',
                    ],
                    [
                        'id' => 1,
                        'name' => 'tag1',
                    ],
                ],
            ],
            [
                'name,limit parameters',
                '?name=tag&limit=1',
                [
                    [
                        'id' => 2,
                        'name' => 'tag2',
                    ],
                ],
            ],
            [
                'name,limit,offset parameters',
                '?name=tag&limit=1&offset=1',
                [
                    [
                        'id' => 1,
                        'name' => 'tag1',
                    ],
                ],
            ],
        ];
    }
}
