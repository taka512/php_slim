<?php

namespace Taka512\Form\Api\TagSite;

use Zend\Filter\StringTrim;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CreateInput implements InputFilterAwareInterface
{
    protected $tagId;
    protected $siteId;
    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter): void
    {
        throw new \DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter(): InputFilterInterface
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name' => 'tag_id',
            'required' => true,
            'break_on_failure' => false,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'タグidの入力は必須です',
                        ],
                    ],
                ],
            ],
        ])->add([
            'name' => 'site_id',
            'required' => true,
            'break_on_failure' => false,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'サイトidの入力は必須です',
                        ],
                    ],
                ],
            ],
        ]);
        $this->inputFilter = $inputFilter;

        return $inputFilter;
    }

    public function exchangeArray(array $data): void
    {
        $this->tagId = empty($data['tag_id']) ? null : $data['tag_id'];
        $this->siteId = empty($data['site_id']) ? null : $data['site_id'];
    }

    public function getArrayCopy(): array
    {
        return [
            'tag_id' => $this->tagId,
            'site_id' => $this->siteId,
        ];
    }
}
