<?php

namespace Taka512\Validator\Model\Tag;

use Laminas\Validator\AbstractValidator;

class NameValidator extends AbstractValidator
{
    const MAX_STR = 32;

    const INVALID_FORMAT = 'NameInvalidFormat';
    const GREATER_THAN_MAX_STR = 'NameGreaterThanMaxStr';

    const MSG_EMPTY = 'タグは必須です';
    const MSG_INVALID_FMT = 'タグ名の書式が正しくありません';
    const MSG_GREATER_THAN_MAX_STR = 'タグ名が長すぎます';

    protected $messageTemplates = [
        self::INVALID_FORMAT => self::MSG_INVALID_FMT,
        self::GREATER_THAN_MAX_STR => self::MSG_GREATER_THAN_MAX_STR,
    ];

    public function isValid($value, ?array $context = null)
    {
        if (mb_strlen($value) > self::MAX_STR) {
            $this->error(self::GREATER_THAN_MAX_STR);

            return false;
        }

        return true;
    }
}
