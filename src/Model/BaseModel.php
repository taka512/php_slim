<?php

namespace Taka512\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function getAttribute($key)
    {
        return parent::getAttribute(\snake_case($key));
    }

    public function setAttribute($key, $value)
    {
        return parent::setAttribute(\snake_case($key), $value);
    }
}
