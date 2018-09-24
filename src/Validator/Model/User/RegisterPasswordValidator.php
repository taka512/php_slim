<?php

namespace Taka512\Validator\Model\User;

use Zend\Validator\AbstractValidator;

class RegisterPasswordValidator extends AbstractValidator
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
        // TODO パスワードをセッションで引回すようになったら削除
        // 確認画面へ遷移の場合のみ、入力チェックする
        if (empty($context['confirm']) || $context['confirm'] !== '1') {
            return true;
        }

        if (mb_strlen($value) > self::MAX_STR) {
            $this->error(self::GREATER_THAN_MAX_STR);

            return false;
        }

        return true;
    }
}
