<?php

namespace Taka512\Form\Admin\Site;

use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\MultiCheckbox;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Taka512\Model\Site;
use Taka512\Repository\TagRepository;

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
                'checked_value' => (string) Site::FLG_ON,
                'unchecked_value' => (string) Site::FLG_OFF,
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
