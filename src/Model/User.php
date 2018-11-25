<?php

namespace Taka512\Model;

class User extends BaseModel
{
    const FLG_OFF = 0;
    const FLG_ON = 1;

    protected $table = 'user';

    public function isDelete(): bool
    {
        return self::FLG_ON === $this->delFlg;
    }

    public function setCreateFormArray(array $data): void
    {
        $this->loginId = $data['login_id'];
        $this->password = password_hash($data['password'], \PASSWORD_DEFAULT);
    }

    public function setEditFormArray(array $data): void
    {
        $this->loginId = $data['login_id'];
        $this->delFlg = $data['del_flg'];
    }

    public function getFormArray(): array
    {
        return [
            'id' => $this->id,
            'login_id' => $this->loginId,
            'password' => $this->password,
            'del_flg' => $this->delFlg,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
