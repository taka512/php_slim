<?php

namespace Taka512\Test\Unit\Form\Api\TagSite;

use Taka512\Form\Api\TagSite\CreateInput;
use Taka512\Test\TestCase;

class CreateInputTest extends TestCase
{
    /**
     * @dataProvider providerGetArrayCopy
     */
    public function testGetArrayCopy($msg, $data, $expected)
    {
        $input = $this->get(CreateInput::class);
        $input->exchangeArray($data);
        $actual = $input->getArrayCopy();
        $this->assertSame($expected, $actual);
    }

    public function providerGetArrayCopy()
    {
        return [
            ['値が正しく設定できてその値が取得できる事の確認', ['tag_id' => 1, 'site_id' => 1], ['tag_id' => 1, 'site_id' => 1]],
            ['空文字列が設定された場合nullとなる事の確認', ['tag_id' => '', 'site_id' => ''], ['tag_id' => null, 'site_id' => null]],
        ];
    }
}
