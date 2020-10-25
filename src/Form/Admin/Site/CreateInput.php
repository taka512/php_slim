<?php

namespace Taka512\Form\Admin\Site;

use Laminas\Filter\StringTrim;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\NotEmpty;
use Taka512\Validator\Model\Site\NameValidator;
use Taka512\Validator\Model\Site\UrlValidator;

class CreateInput implements InputFilterAwareInterface
{
    private $name;
    private $url;
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
        ]);
        $this->inputFilter = $inputFilter;

        return $inputFilter;
    }

    public function exchangeArray(array $data): void
    {
        $this->name = (isset($data['name']) && '' !== $data['name']) ? $data['name'] : null;
        $this->url = (isset($data['url']) && '' !== $data['url']) ? $data['url'] : null;
        $this->confirm = !empty($data['confirm']) ? $data['confirm'] : false;
        $this->back = (isset($data['back']) && '1' === $data['back']) ? $data['back'] : false;
    }

    public function getArrayCopy(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
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
}
