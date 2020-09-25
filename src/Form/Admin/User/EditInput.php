<?php

namespace Taka512\Form\Admin\User;

use Taka512\Model\User;
use Taka512\Repository\UserRepository;
use Taka512\Validator\Model\User\LoginIdEditValidator;
use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\NotEmpty;

class EditInput implements InputFilterAwareInterface
{
    protected $id;
    protected $loginId;
    protected $delFlg;
    protected $createdAt;
    protected $updatedAt;
    protected $confirm = false;
    protected $back = false;
    protected $userRepository;
    protected $inputFilter;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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
                            NotEmpty::IS_EMPTY => LoginIdEditValidator::MSG_EMPTY,
                        ],
                    ],
                ],
                [
                    'name' => LoginIdEditValidator::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'userRepository' => $this->userRepository,
                    ],
                ],
            ],
        ])->add([
            'name' => 'del_flg',
            'required' => false,
        ]);
        $this->inputFilter = $inputFilter;

        return $inputFilter;
    }

    public function exchangeArray(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->loginId = $data['login_id'] ?? null;
        $this->delFlg = (isset($data['del_flg']) && User::FLG_ON == $data['del_flg']) ? User::FLG_ON : User::FLG_OFF;
        $this->confirm = (isset($data['confirm']) && '1' === $data['confirm']) ? true : false;
        $this->back = (isset($data['back']) && '1' === $data['back']) ? true : false;
        $this->createdAt = $data['created_at'] ?? null;
        $this->updatedAt = $data['updated_at'] ?? null;
    }

    public function getArrayCopy(): array
    {
        return [
            'id' => $this->id,
            'login_id' => $this->loginId,
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
}
