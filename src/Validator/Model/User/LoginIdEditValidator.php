<?php

namespace Taka512\Validator\Model\User;

use Zend\Validator\AbstractValidator;
use Taka512\Repository\UserRepository;

class LoginIdEditValidator extends AbstractValidator
{
    const MAX_STR = 32;

    const INVALID_FORMAT = 'LoginIdInvalidFormat';
    const GREATER_THAN_MAX_STR = 'LoginIdGreaterThanMaxStr';
    const DUPLICATE_STR = 'LoginIdDuplicateStr';

    const MSG_EMPTY = 'ログインIDは必須です';
    const MSG_NOT_FOUND = 'ログインIDが存在しません';
    const MSG_INVALID_FMT = 'ログインIDは半角英数字を登録してください';
    const MSG_GREATER_THAN_MAX_STR = 'ログインIDが長すぎます。'.self::MAX_STR.'文字以下で指定してください';
    const MSG_DUPLICATE_STR = 'ログインIDは既に登録されています';

    protected $userRepository;
    protected $messageTemplates = [
        self::INVALID_FORMAT => self::MSG_INVALID_FMT,
        self::GREATER_THAN_MAX_STR => self::MSG_GREATER_THAN_MAX_STR,
        self::DUPLICATE_STR => self::MSG_DUPLICATE_STR,
    ];

    public function isValid($value, ?array $context = null)
    {
        if (1 !== preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            $this->error(self::INVALID_FORMAT);

            return false;
        }

        if (strlen($value) > self::MAX_STR) {
            $this->error(self::GREATER_THAN_MAX_STR);

            return false;
        }
        $user = $this->userRepository->findOneByLoginId($value);
        if (isset($user) && $user->id != $context['id']) {
            $this->error(self::DUPLICATE_STR);

            return false;
        }

        return true;
    }

    public function setUserRepository(UserRepository $userRepository): void
    {
        $this->userRepository = $userRepository;
    }
}
