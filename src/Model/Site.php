<?php

namespace Taka512\Model;

class Site extends BaseModel
{
    const FLG_OFF = 0;
    const FLG_ON = 1;

    protected $table = 'site';
    protected $delFlg = self::FLG_OFF;

    public function isDelete()
    {
        return ($this->delFlg === self::FLG_ON);
    }

    public function setFormArray(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->delFlg = $data['del_flg'] ?? null;
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
