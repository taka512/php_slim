<?php

namespace Taka512\Form\Admin\User;

use Zend\Filter\StringTrim;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Taka512\Validator\Model\User\LoginIdEditValidator;
use Taka512\Repository\UserRepository;
use Taka512\Model\User;

class EditInput implements InputFilterAwareInterface
{
    public $id;
    public $loginId;
    public $delFlg;
    public $createdAt;
    public $updatedAt;
    public $confirm = false;
    public $back = false;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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
                            NotEmpty::IS_EMPTY => LoginIdEditValidator::MSG_EMPTY,
                        ],
                    ],
                ],
                [
                    'name' => LoginIdEditValidator::class,
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
        $this->loginId = (isset($data['login_id']) && $data['login_id'] !== '') ? $data['login_id'] : null;
        $this->delFlg = (isset($data['del_flg']) && $data['del_flg'] == User::FLG_ON) ? User::FLG_ON: User::FLG_OFF;
        $this->confirm = (isset($data['confirm']) && $data['confirm'] === '1') ? true : false;
        $this->back = (isset($data['back']) && $data['back'] === '1') ? true : false;
        $this->createdAt = (isset($data['created_at']) && $data['created_at'] !== '') ? $data['created_at'] : null;
        $this->updatedAt = (isset($data['updated_at']) && $data['updated_at'] !== '') ? $data['updated_at'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'login_id' => $this->loginId,
            'del_flg' => $this->delFlg,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
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
