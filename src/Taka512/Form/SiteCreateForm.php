<?php

namespace Taka512\Form;

use Zend\Session\Container;
use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Csrf;

class SiteCreateForm extends Form
{
    public function __construct(Container $session, int $csrfTimeout)
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
            'name' => 'csrf',
            'type' => Csrf::class,
            'options' => [
                'csrf_options' => [
                    'session' => $session,
                    'timeout' => $csrfTimeout,
                ],
            ],
        ])->add([
            'name' => 'submit',
            'type' => Submit::class,
        ]);
    }
}
