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
    const DEFAULT_SITE_ID = null;
    const DEFAULT_LIMIT = '30';
    const DEFAULT_OFFSET = '0';

    protected $name = self::DEFAULT_NAME;
    protected $siteId = self::DEFAULT_SITE_ID;
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
        $this->siteId = empty($data['site_id']) ? self::DEFAULT_SITE_ID : $data['site_id'];
        $this->limit = empty($data['limit']) ? self::DEFAULT_LIMIT : $data['limit'];
        $this->offset = empty($data['offset']) ? self::DEFAULT_OFFSET : $data['offset'];
    }

    public function getArrayCopy(): array
    {
        return [
            'name' => $this->name,
            'site_id' => $this->siteId,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }
}
