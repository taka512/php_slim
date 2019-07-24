<?php

namespace Taka512\Model;

/**
 * @OA\Schema(
 *     description="Api Response Error model",
 *     type="object",
 *     title="Error model"
 * )
 */
class Error
{
    /**
     * @OA\Property(
     *   property="code",
     *   type="string",
     *   example="not_found"
     * )
     */
    public $code;
    /**
     * @OA\Property(
     *   property="message",
     *   type="string",
     *   example="data not found"
     * )
     */
    public $message;
}
