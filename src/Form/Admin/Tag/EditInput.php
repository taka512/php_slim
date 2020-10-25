<?php

namespace Taka512\Form\Admin\Tag;

use Laminas\Filter\StringTrim;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\NotEmpty;
use Taka512\Validator\Model\Tag\NameValidator;

class EditInput implements InputFilterAwareInterface
{
    private $id;
    private $name;
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
        ]);
        $this->inputFilter = $inputFilter;

        return $inputFilter;
    }

    public function exchangeArray(array $data): void
    {
        $this->id = (isset($data['id']) && '' !== $data['id']) ? $data['id'] : null;
        $this->name = (isset($data['name']) && '' !== $data['name']) ? $data['name'] : null;
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
}
