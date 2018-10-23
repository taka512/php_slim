<?php

namespace Taka512\Auth;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Taka512\Model\User;

class AuthenticationAdapter implements AdapterInterface
{
    private $loginId;
    private $password;

    public function __construct(string $loginId, string $password)
    {
        $this->loginId = $loginId;
        $this->password = $password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setLoginId(string $loginId)
    {
        $this->loginId = $loginId;
    }

    /**
     * Performs an authentication attempt
     *
     * @return Result
     */
    public function authenticate()
    {
        $user = User::where('del_flg', User::FLG_OFF)->where('login_id', $this->loginId)->first();
        if (is_null($user)) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->loginId, ['ユーザが見つかりませんでした']);
        }

        if (password_verify($this->password, $user->password)) {
            return new Result(Result::SUCCESS, $this->loginId);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->loginId, ['パスワードが正しくありません']);
    }
}
