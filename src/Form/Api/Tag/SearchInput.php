<?php

namespace Taka512\Form\Api\Tag;

use Zend\Filter\StringTrim;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Taka512\Validator\Model\Tag\NameValidator;

class SearchInput implements InputFilterAwareInterface
{
    const DEFAULT_NAME = '';
    const DEFAULT_LIMIT = '30';
    const DEFAULT_OFFSET = '0';

    protected $name = self::DEFAULT_NAME;
    protected $limit = self::DEFAULT_LIMIT;
    protected $offset = self::DEFAULT_OFFSET;
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
            'name' => 'name',
            'required' => false,
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
        $this->name = empty($data['name']) ? self::DEFAULT_NAME : $data['name'];
        $this->limit = empty($data['limit']) ? self::DEFAULT_LIMIT : $data['limit'];
        $this->offset = empty($data['offset']) ? self::DEFAULT_OFFSET : $data['offset'];
    }

    public function getArrayCopy(): array
    {
        return [
            'name' => $this->name,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }
}
