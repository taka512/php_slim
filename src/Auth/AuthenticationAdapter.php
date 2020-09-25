<?php

namespace Taka512\Auth;

use Taka512\Repository\UserRepository;
use Taka512\Validator\Model\User\LoginIdValidator;
use Taka512\Validator\Model\User\PasswordValidator;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class AuthenticationAdapter implements AdapterInterface
{
    private $userRepository;
    private $loginId;
    private $password;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function setPassword(string $password): AdapterInterface
    {
        $this->password = $password;

        return $this;
    }

    public function setLoginId(string $loginId): AdapterInterface
    {
        $this->loginId = $loginId;

        return $this;
    }

    public function authenticate(): Result
    {
        $user = $this->userRepository->findOneByLoginId($this->loginId);
        if (is_null($user) || $user->isDelete()) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->loginId, [LoginIdValidator::MSG_NOT_FOUND]);
        }

        if (password_verify($this->password, $user->password)) {
            return new Result(Result::SUCCESS, $this->loginId);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->loginId, [PasswordValidator::MSG_WRONG]);
    }
}
