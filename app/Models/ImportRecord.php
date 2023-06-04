<?php

namespace App\Models;

class ImportRecord extends BaseModel
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'import_records';

    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 追加字段
     */
    protected $appends = ['excel_path_url'];

    /**
     * 获取excel文件地址，携带协议和域名
     *
     * @param  string  $value
     * @return string
     */
    public function getExcelPathUrlAttribute()
    {
        if (!$this->excel_path) {
            return "";
        }
        if (strpos($this->excel_path, 'http://') === false && strpos($this->excel_path, 'https://') === false) {
            return asset('storage/'.$this->excel_path);
        } else {
            return $this->excel_path;
        }
    }
}
