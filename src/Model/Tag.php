<?php

namespace Taka512\Model;

/**
 * @OA\Schema(
 *     description="Tag model",
 *     type="object",
 *     title="Tag model"
 * )
 */
class Tag extends BaseModel
{
    protected $table = 'tag';

    /**
     * @OA\Property(
     *   property="id",
     *   type="integer",
     *   format="int64",
     *   example=1
     * )
     * @OA\Property(
     *   property="name",
     *   type="string",
     *   example="tag1"
     * )
     */
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
