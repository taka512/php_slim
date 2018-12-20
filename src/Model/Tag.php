<?php

namespace Taka512\Model;

class Tag extends BaseModel
{
    protected $table = 'tag';

    public function setFormArray(array $data): void
    {
        $this->name = $data['name'];
    }

    public function getFormArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
