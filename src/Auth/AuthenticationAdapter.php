<?php

namespace Taka512\Auth;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

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
        // Retrieve the user's information (e.g. from a database)
        // and store the result in $row (e.g. associative array).
        // If you do something like this, always store the passwords using the
        // PHP password_hash() function!

#        if (password_verify($this->password, 'test')) {
        if ($this->password == 'test') {
            return new Result(Result::SUCCESS, $this->loginId);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->loginId);
    }
}
