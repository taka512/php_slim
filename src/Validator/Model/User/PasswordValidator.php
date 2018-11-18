<?php

namespace Taka512\Validator\Model\User;

use Zend\Validator\AbstractValidator;

class PasswordValidator extends AbstractValidator
{
    const MIN_STR = 8;
    const MAX_STR = 32;

    const INVALID_FORMAT = 'PasswordInvalidFormat';
    const LESS_THAN_MIN_STR = 'PasswordLessThanMinStr';
    const GREATER_THAN_MAX_STR = 'PasswordGreaterThanMaxStr';

    const MSG_EMPTY = 'パスワードは必須です';
    const MSG_WRONG = 'パスワードが間違っています';
    const MSG_INVALID_FMT = 'パスワードは半角英数字を指定してください';
    const MSG_LESS_THAN_MIN_STR = 'パスワードが短すぎます。'.self::MIN_STR.'文字以上を指定してください';
    const MSG_GREATER_THAN_MAX_STR = 'パスワードが長すぎます。'.self::MAX_STR.'文字以下で指定してください';

    protected $messageTemplates = [
        self::INVALID_FORMAT => self::MSG_INVALID_FMT,
        self::LESS_THAN_MIN_STR => self::MSG_LESS_THAN_MIN_STR,
        self::GREATER_THAN_MAX_STR => self::MSG_GREATER_THAN_MAX_STR,
    ];

    public function isValid($value, array $context = null)
    {
        if (1 !== preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            $this->error(self::INVALID_FORMAT);

            return false;
        }

        if (strlen($value) > self::MAX_STR) {
            $this->error(self::GREATER_THAN_MAX_STR);

            return false;
        }

        if (strlen($value) < self::MIN_STR) {
            $this->error(self::LESS_THAN_MIN_STR);

            return false;
        }

        return true;
    }
}
