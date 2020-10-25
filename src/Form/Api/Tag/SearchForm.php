<?php

namespace Taka512\Form\Api\Tag;

use Laminas\Form\Element\Text;
use Laminas\Form\Form;

class SearchForm extends Form
{
    public function __construct()
    {
        parent::__construct('api_tag_search');
        $this->add([
            'name' => 'name',
            'type' => Text::class,
        ])->add([
            'name' => 'site_id',
            'type' => Text::class,
        ])->add([
            'name' => 'limit',
            'type' => Text::class,
        ])->add([
            'name' => 'offset',
            'type' => Text::class,
        ]);
    }
}
