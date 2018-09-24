<?php

namespace Taka512\Form\Admin\User;

use Zend\Filter\StringTrim;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Taka512\Validator\Model\User\LoginIdValidator;
use Taka512\Validator\Model\User\RegisterPasswordValidator;

class CreateInput implements InputFilterAwareInterface
{
    public $loginId;
    public $password;
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
            'name' => 'login_id',
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
                            NotEmpty::IS_EMPTY => LoginIdValidator::MSG_EMPTY,
                        ],
                    ],
                ],
                [
                    'name' => LoginIdValidator::class,
                    'break_chain_on_failure' => true,
                ],
            ],
        ])->add([
            'name' => 'password',
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
                            NotEmpty::IS_EMPTY => RegisterPasswordValidator::MSG_EMPTY,
                        ],
                    ],
                ],
                [
                    'name' => RegisterPasswordValidator::class,
                    'break_chain_on_failure' => true,
                ],
            ],
        ]);
        $this->inputFilter = $inputFilter;

        return $inputFilter;
    }

    public function exchangeArray(array $data)
    {
        $this->loginId = (isset($data['login_id']) && $data['login_id'] !== '') ? $data['login_id'] : null;
        $this->password = (isset($data['password']) && $data['password'] !== '') ? $data['password'] : null;
        $this->confirm = !empty($data['confirm']) ? $data['confirm'] : false;
        $this->back = (isset($data['back']) && $data['back'] === '1') ? $data['back'] : false;
    }

    public function getArrayCopy()
    {
        return [
            'login_id' => $this->loginId,
            'password' => $this->password,
        ];
    }

    public function getLoginId()
    {
        return $this->loginId;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getHashedPassword()
    {
        return password_hash($this->password, \PASSWORD_DEFAULT);
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
