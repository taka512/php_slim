<?php

namespace Taka512\Form\Admin\User;

use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;

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
