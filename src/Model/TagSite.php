<?php

namespace Taka512\Model;

class TagSite extends BaseModel
{
    protected $table = 'tag_site';
    public $timestamps = false;

    public function setFormArray(array $data): void
    {
        $this->tagId = $data['tag_id'];
        $this->siteId = $data['site_id'];
    }

    public function getFormArray(): array
    {
        return [
            'tag_id' => $this->tagId,
            'site_id' => $this->siteId,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
