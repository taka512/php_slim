<?php

namespace Taka512\Validator\Model\Site;

use Laminas\Validator\AbstractValidator;

class NameValidator extends AbstractValidator
{
    public const MAX_STR = 32;

    public const INVALID_FORMAT = 'NameInvalidFormat';
    public const GREATER_THAN_MAX_STR = 'NameGreaterThanMaxStr';

    public const MSG_EMPTY = 'サイト名は必須です';
    public const MSG_INVALID_FMT = 'サイト名の書式が正しくありません';
    public const MSG_GREATER_THAN_MAX_STR = 'サイト名が長すぎます';

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
