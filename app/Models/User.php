<?php

namespace App\Models;

class User extends BaseModel
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * 追加字段
     */
    protected $appends = ['avatar_url'];

    /**
     * 获取头像地址，携带协议和域名
     *
     * @param  string  $value
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return "";
        }
        if (strpos($this->avatar, 'http://') === false && strpos($this->avatar, 'https://') === false) {
            return asset('storage/'.$this->avatar);
        } else {
            return $this->avatar;
        }
    }

    // /**
    //  * 设置用户名(在编辑的时候赋值会被执行)
    //  *
    //  * @param  string  $value
    //  * @return void
    //  */
    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = $value.'管理员';
    // }
}
