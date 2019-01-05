<?php

namespace Taka512\Form\Api\Tag;

use Zend\Form\Form;
use Zend\Form\Element\Text;

class SearchForm extends Form
{
    public function __construct()
    {
        parent::__construct('api_tag_search');
        $this->add([
            'name' => 'name',
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
