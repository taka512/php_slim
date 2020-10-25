<?php

namespace Taka512\Form\Admin\Site;

use Laminas\Filter\StringTrim;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\NotEmpty;
use Taka512\Model\Site;
use Taka512\Validator\Model\Site\NameValidator;
use Taka512\Validator\Model\Site\UrlValidator;

class EditInput implements InputFilterAwareInterface
{
    private $id;
    private $name;
    private $url;
    private $tags;
    private $delFlg;
    private $createdAt;
    private $updatedAt;
    private $confirm = false;
    private $back = false;
    private $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter): void
    {
        throw new \DomainException(sprintf('%s does not allow injection of an alternate input filter', __CLASS__));
    }

    public function getInputFilter(): InputFilterInterface
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'break_on_failure' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => NameValidator::MSG_EMPTY,
                        ],
                    ],
                ],
                [
                    'name' => NameValidator::class,
                    'break_chain_on_failure' => true,
                ],
            ],
        ])->add([
            'name' => 'url',
            'required' => true,
            'break_on_failure' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => UrlValidator::MSG_EMPTY,
                        ],
                    ],
                ],
                [
                    'name' => UrlValidator::class,
                    'break_chain_on_failure' => true,
                ],
            ],
        ])->add([
            'name' => 'tags',
            'required' => false,
        ])->add([
            'name' => 'del_flg',
            'required' => false,
        ]);
        $this->inputFilter = $inputFilter;

        return $inputFilter;
    }

    public function exchangeArray(array $data): void
    {
        $this->id = (isset($data['id']) && '' !== $data['id']) ? $data['id'] : null;
        $this->name = (isset($data['name']) && '' !== $data['name']) ? $data['name'] : null;
        $this->url = (isset($data['url']) && '' !== $data['url']) ? $data['url'] : null;
        $this->tags = (isset($data['tags']) && '' !== $data['tags']) ? $data['tags'] : [];
        $this->delFlg = (isset($data['del_flg']) && Site::FLG_ON == $data['del_flg']) ? Site::FLG_ON : Site::FLG_OFF;
        $this->confirm = !empty($data['confirm']) ? $data['confirm'] : false;
        $this->back = (isset($data['back']) && '1' === $data['back']) ? $data['back'] : false;
        $this->createdAt = (isset($data['created_at']) && '' !== $data['created_at']) ? $data['created_at'] : null;
        $this->updatedAt = (isset($data['updated_at']) && '' !== $data['updated_at']) ? $data['updated_at'] : null;
    }

    public function getArrayCopy(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'tags' => $this->tags,
            'del_flg' => $this->delFlg,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    public function isConfirm(): bool
    {
        return false !== $this->confirm;
    }

    public function isBack(): bool
    {
        return false !== $this->back;
    }

    public function isCheckedByTagId($tagId): bool
    {
        return in_array($tagId, $this->tags);
    }

    public function getTagSiteData(): array
    {
        $tags = [];
        foreach ($this->tags as $tagId) {
            $tags[] = [
               'site_id' => $this->id,
               'tag_id' => $tagId,
           ];
        }

        return $tags;
    }
}
