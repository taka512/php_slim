<?php

namespace Taka512\Form\Admin\Tag;

use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;

class CreateForm extends Form
{
    public function __construct(int $csrfTimeout = 7200)
    {
        parent::__construct('tag_create');
        $this->add([
            'name' => 'name',
            'type' => Text::class,
        ])->add([
            'name' => 'confirm',
            'type' => Text::class,
        ])->add([
            'name' => 'back',
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
            'name' => 'submit',
            'type' => Submit::class,
        ]);
    }
}
