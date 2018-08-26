<?php

namespace Taka512\Validator\Model\User;

use Zend\Validator\AbstractValidator;

class PasswordValidator extends AbstractValidator
{
    const MAX_STR = 32;

    const INVALID_FORMAT = 'PasswordInvalidFormat';
    const GREATER_THAN_MAX_STR = 'PasswordGreaterThanMaxStr';

    const MSG_EMPTY = 'パスワードは必須です';
    const MSG_INVALID_FMT = 'パスワードの書式が正しくありません';
    const MSG_GREATER_THAN_MAX_STR = 'パスワードが長すぎます';

    protected $messageTemplates = [
        self::INVALID_FORMAT => self::MSG_INVALID_FMT,
        self::GREATER_THAN_MAX_STR => self::MSG_GREATER_THAN_MAX_STR,
    ];

    public function isValid($value, array $context = null)
    {
        if (mb_strlen($value) > self::MAX_STR) {
            $this->error(self::GREATER_THAN_MAX_STR);

            return false;
        }

        return true;
    }
}
