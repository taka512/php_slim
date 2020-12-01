<?php

namespace Taka512\Model;

use Illuminate\Database\Eloquent\Model;
use Taka512\Util\StdUtil;

class BaseModel extends Model
{
    public function getAttribute($key)
    {
        return parent::getAttribute(StdUtil::snakeCase($key));
    }

    public function setAttribute($key, $value)
    {
        return parent::setAttribute(StdUtil::snakeCase($key), $value);
    }
}
