<?php

namespace Taka512\Form;

use Zend\Filter\StringTrim;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Taka512\Validator\Model\Site\NameValidator;
use Taka512\Validator\Model\Site\UrlValidator;
use Taka512\Model\Site;

class SiteCreateInput implements InputFilterAwareInterface
{
    public $name;
    public $url;
    public $confirm = false;
    public $back = false;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }


    public function getInputFilter()
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
    public function exchangeArray(array $data)
    {
        $this->name = (isset($data['name']) && $data['name'] !== '') ? $data['name'] : null;
        $this->url = (isset($data['url']) && $data['url'] !== '') ? $data['url'] : null;
        $this->confirm = !empty($data['confirm']) ? $data['confirm'] : false;
        $this->back = (isset($data['back']) && $data['back'] === '1') ? $data['back'] : false;
    }

    public function getArrayCopy()
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
        ];
    }

    public function isConfirm()
    {
        return $this->confirm !== false;
    }

    public function isBack()
    {
        return $this->back !== false;
    }
}
