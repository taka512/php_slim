<?php

namespace Taka512\Form\Admin\User;

use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Taka512\Model\User;

class EditForm extends Form
{
    public function __construct(int $csrfTimeout = 7200)
    {
        parent::__construct('user_edit');
        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ])->add([
            'name' => 'login_id',
            'type' => Text::class,
        ])->add([
            'name' => 'del_flg',
            'type' => Checkbox::class,
            'options' => [
                'checked_value' => (string)User::FLG_ON,
                'unchecked_value' => (string)User::FLG_OFF,
            ],
        ])->add([
            'name' => 'created_at',
            'type' => Text::class,
        ])->add([
            'name' => 'updated_at',
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
