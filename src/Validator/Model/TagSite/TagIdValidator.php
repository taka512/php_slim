<?php

namespace Taka512\Validator\Model\TagSite;

use Zend\Validator\AbstractValidator;

class TagIdValidator extends AbstractValidator
{
    const MAX_STR = 32;

    const INVALID_FMT = 'InvalidFmt';
    const DUPLICATE_ID = 'DuplicateId';

    const MSG_INVALID_FMT = 'タグIDの書式が正しくありません';
    const MSG_DUPLICATE_ID = 'タグは既に登録済みです';

    protected $messageTemplates = [
        self::INVALID_FMT => self::MSG_INVALID_FMT,
        self::DUPLICATE_ID => self::MSG_DUPLICATE_ID,
    ];

    public function isValid($value, ?array $context = null)
    {
        var_dump($context);

        return true;
    }
}
