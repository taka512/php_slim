<?php

namespace Taka512\Repository;

use Illuminate\Database\Eloquent\Collection;
use Taka512\Model\User;

class UserRepository
{
    public function insert(array $data): ?int
    {
        $user = new User();
        $user->setCreateFormArray($data);
        $user->save();

        return $user->id;
    }

    public function findOneById(int $id): ?User
    {
        return User::where('id', $id)->first();
    }

    public function findOneByLoginId(string $loginId): ?User
    {
        return User::where('login_id', $loginId)->first();
    }

    public function findLatestUsers(int $limit = 10): Collection
    {
        return User::orderBy('id', 'desc')->take($limit)->get();
    }
}
