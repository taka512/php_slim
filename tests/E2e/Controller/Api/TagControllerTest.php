<?php

namespace Taka512\Test\E2e\Controller\Api;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Http\ClientFactory;
use Taka512\Manager\EntityManager;
use Taka512\Test\E2eTestCase;

class TagControllerTest extends E2eTestCase
{
    protected function setUp(): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/TagController.yml');
        $this->get(EntityManager::class)->truncateTables(['tag', 'site', 'tag_site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    /**
     * @dataProvider providerIndex
     */
    public function testIndex($msg, $query, $expected)
    {
        $client = ClientFactory::createGoutte();
        $crawler = $client->request('GET', $this->getUrl('/api/tag'.$query));
        $this->assertSame(200, $client->getResponse()->getStatusCode(), 'ステータスコードが200');
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
