<?php

namespace Taka512\Repository;

use Taka512\Model\User;

class UserRepository
{
    public function insert(array $data)
    {
        $user = new User();
        $user->setCreateFormArray($data);
        $user->save();
    }

    public function findOneById(int $id)
    {
        return User::where('id', $id)->first();
    }

    public function findOneByLoginId(string $loginId)
    {
        return User::where('del_flg', User::FLG_OFF)->where('login_id', $loginId)->first();
    }

    public function findLatestUsers(int $limit = 10)
    {
        return User::orderBy('id', 'desc')->take($limit)->get();
    }
}
