<?php

namespace Taka512\Form\Admin\Site;

use Taka512\Model\Site;
use Taka512\Repository\TagRepository;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct(TagRepository $tagRepository, int $csrfTimeout = 7200)
    {
        $tagValues = [];
        $tags = $tagRepository->findLatestTags();
        foreach ($tags as $tag) {
            $tagValues[$tag->id] = $tag->name;
        }
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
            'name' => 'tags',
            'type' => MultiCheckbox::class,
            'options' => [
                'value_options' => $tagValues,
            ],
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
