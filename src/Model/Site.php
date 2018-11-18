<?php

namespace Taka512\Model;

class Site extends BaseModel
{
    const FLG_OFF = 0;
    const FLG_ON = 1;

    protected $table = 'site';

    public function isDelete()
    {
        return self::FLG_ON === $this->delFlg;
    }

    public function setFormArray(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->delFlg = $data['del_flg'] ?? self::FLG_OFF;
    }

    public function getFormArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'del_flg' => $this->delFlg,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
