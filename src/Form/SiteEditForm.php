<?php

namespace Taka512\Form;

use Zend\Session\Container;
use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Csrf;
use Taka512\Model\Site;


class SiteEditForm extends Form
{
    public function __construct(Container $session, int $csrfTimeout)
    {
        parent::__construct('site_edit');
        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ])->add([
            'name' => 'name',
            'type' => Text::class,
        ])->add([
            'name' => 'url',
            'type' => Text::class,
        ])->add([
            'name' => 'del_flg',
            'type' => Checkbox::class,
            'options' => [
                'checked_value' => Site::FLG_ON,
                'unchecked_value' => Site::FLG_OFF,
            ],
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
                    'session' => $session,
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
