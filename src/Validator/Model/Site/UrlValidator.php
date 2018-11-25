<?php

namespace Taka512\Validator\Model\Site;

use Zend\Validator\AbstractValidator;

class UrlValidator extends AbstractValidator
{
    const MAX_STR = 128;

    const INVALID_FORMAT = 'UrlInvalidFormat';
    const GREATER_THAN_MAX_STR = 'UrlGreaterThanMaxStr';

    const MSG_EMPTY = 'URLは必須です';
    const MSG_INVALID_FMT = 'URLの書式が正しくありません';
    const MSG_GREATER_THAN_MAX_STR = 'URLが長すぎます';

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
