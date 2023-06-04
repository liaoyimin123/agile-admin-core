<?php

namespace App\Models;

class SystemLog extends BaseModel
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'system_logs';

    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;
}
