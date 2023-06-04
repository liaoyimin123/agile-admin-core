<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    /**
     * 字段值重复校验
     */
    public static function fieldRepeatCheck($field, $val, $id = 0)
    {
        $query = self::where($field, $val);
        if ($id) {
            $query->where('id', '<>', $id);
        }
        $user = $query->first();
        if ($user) {
            return true;
        } else {
            return false;
        }
    }
}
