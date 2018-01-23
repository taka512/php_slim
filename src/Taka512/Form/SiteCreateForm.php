<?php

namespace Taka512\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Submit;

class SiteCreateForm extends Form
{
    public function __construct()
    {
        parent::__construct('site_create');
        $this->add([
            'name' => 'name',
            'type' => Text::class,
        ])->add([
            'name' => 'url',
            'type' => Text::class,
        ])->add([
            'name' => 'confirm',
            'type' => Text::class,
        ])->add([
            'name' => 'submit',
            'type' => Submit::class,
        ]);
    }
}
