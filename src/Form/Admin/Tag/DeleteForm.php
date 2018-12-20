<?php

namespace Taka512\Form\Admin\Tag;

use Zend\Form\Form;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Csrf;

class DeleteForm extends Form
{
    public function __construct(int $csrfTimeout)
    {
        parent::__construct('tag_delete');
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
            'name' => 'submit',
            'type' => Submit::class,
        ]);
    }
}
