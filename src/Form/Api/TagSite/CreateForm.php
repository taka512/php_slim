<?php

namespace Taka512\Form\Api\TagSite;

use Zend\Form\Form;
use Zend\Form\Element\Text;

class CreateForm extends Form
{
    public function __construct()
    {
        parent::__construct('api_tag_site_create');
        $this->add([
            'name' => 'tag_id',
            'type' => Text::class,
        ])->add([
            'name' => 'site_id',
            'type' => Text::class,
        ]);
    }
}
