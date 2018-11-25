<?php

namespace Taka512\Form\Admin\User;

use Zend\Filter\StringTrim;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Taka512\Validator\Model\User\LoginIdValidator;
use Taka512\Validator\Model\User\PasswordValidator;

class SigninInput implements InputFilterAwareInterface
{
    protected $loginId;
    protected $password;
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
                            NotEmpty::IS_EMPTY => PasswordValidator::MSG_EMPTY,
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
        $this->loginId = $data['login_id'] ?? null;
        $this->password = $data['password'] ?? null;
    }

    public function getArrayCopy(): array
    {
        return [
            'login_id' => $this->loginId,
            'password' => $this->password,
        ];
    }

    public function getLoginId(): ?string
    {
        return $this->loginId;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
