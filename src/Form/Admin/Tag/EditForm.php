<?php

namespace Taka512\Form\Admin\Tag;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct(int $csrfTimeout = 7200)
    {
        parent::__construct('site_edit');
        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ])->add([
            'name' => 'name',
            'type' => Text::class,
        ])->add([
            'name' => 'confirm',
            'type' => Text::class,
        ])->add([
            'name' => 'created_at',
            'type' => Text::class,
        ])->add([
            'name' => 'updated_at',
            'type' => Text::class,
        ])->add([
            'name' => 'csrf',
            'type' => Csrf::class,
            'options' => [
                'csrf_options' => [
                    'timeout' => $csrfTimeout,
                ],
            ],
        ])->add([
            'name' => 'back',
            'type' => Text::class,
        ])->add([
            'name' => 'submit',
            'type' => Submit::class,
        ]);
    }
}
