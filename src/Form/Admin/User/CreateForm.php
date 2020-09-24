<?php

namespace Taka512\Form\Admin\User;

use Zend\Form\Form;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Csrf;

class CreateForm extends Form
{
    public function __construct(int $csrfTimeout = 7200)
    {
        parent::__construct('create');
        $this->add([
            'name' => 'login_id',
            'type' => Text::class,
        ])->add([
            'name' => 'password',
            'type' => Password::class,
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
