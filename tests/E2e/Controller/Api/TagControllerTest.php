<?php

namespace Taka512\Test\E2e\Controller\Api;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Http\ClientFactory;
use Taka512\Test\E2eTestCase;

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
                    'tags' => [
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
            ],
            [
                'name parameter is [tag]',
                '?name=tag',
                [
                    'tags' => [
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
            ],
            [
                'site_id parameter is [site_id]',
                '?site_id=1',
                [
                    'tags' => [
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
            ],
            [
                'name,limit parameters',
                '?name=tag&limit=1',
                [
                    'tags' => [
                        [
                            'id' => 2,
                            'name' => 'tag2',
                        ],
                    ],
                ],
            ],
            [
                'name,limit,offset parameters',
                '?name=tag&limit=1&offset=1',
                [
                    'tags' => [
                        [
                            'id' => 1,
                            'name' => 'tag1',
                        ],
                    ],
                ],
            ],
        ];
    }
}
