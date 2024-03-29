<?php

namespace Taka512\Model;

/**
 * @OA\Schema(
 *     description="Site model",
 *     type="object",
 *     title="Site model"
 * )
 */
class Site extends BaseModel
{
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
     *   example="site1"
     * )
     * @OA\Property(
     *   property="url",
     *   type="string",
     *   example="http://google.com"
     * )
     */
    public const FLG_OFF = 0;
    public const FLG_ON = 1;

    protected $table = 'site';

    public function isDelete(): bool
    {
        return self::FLG_ON === $this->delFlg;
    }

    public function setFormArray(array $data): void
    {
        $this->name = $data['name'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->delFlg = $data['del_flg'] ?? self::FLG_OFF;
    }

    public function getFormArray(): array
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
