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

class SiteEditInput implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $url;
    public $delFlg;
    public $createdAt;
    public $updatedAt;
    public $confirm = false;

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
        ])->add([
            'name' => 'del_flg',
            'required' => false,
        ]);
        $this->inputFilter = $inputFilter;

        return $inputFilter;
    }
    public function exchangeArray(array $data)
    {
        $this->id = (isset($data['id']) && $data['id'] !== '') ? $data['id'] : null;
        $this->name = (isset($data['name']) && $data['name'] !== '') ? $data['name'] : null;
        $this->url = (isset($data['url']) && $data['url'] !== '') ? $data['url'] : null;
        $this->delFlg = (isset($data['del_flg']) && $data['del_flg'] == Site::DEL_FLG_ON) ? Site::DEL_FLG_ON: Site::DEL_FLG_OFF;
        $this->confirm = !empty($data['confirm']) ? $data['confirm'] : false;
        $this->createdAt = (isset($data['created_at']) && $data['created_at'] !== '') ? $data['created_at'] : null;
        $this->updatedAt = (isset($data['updated_at']) && $data['updated_at'] !== '') ? $data['updated_at'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'del_flg' => $this->delFlg,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    public function isConfirm()
    {
        return $this->confirm !== false;
    }
}
