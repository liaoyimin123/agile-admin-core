<?php

namespace App\Imports;

use App\Models\ImportRecord;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UsersImport implements ToCollection
{
    /**
     * excel路径
     */
    protected $excelPath;

    /**
     * 唯一编码
     */
    protected $unique_code;

    public function __construct($excelPath = '', $unique_code = '')
    {
        $this->excelPath = $excelPath;
        $this->unique_code = $unique_code;
    }

    /**
     * 使用 ToCollection
     * @param array $row
     *
     * @return User|null
     */
    public function Collection(Collection $rows)
    {
        //如果需要去除表头
        unset($rows[0]);
        //$rows 是数组格式
        $this->createData($rows);
    }

    public function createData($rows)
    {
        DB::beginTransaction();
        try {
            $arr = [];
            foreach ($rows as $v) {
                $usernameIsRepeat = User::fieldRepeatCheck('username', $v[0]);
                if ($usernameIsRepeat) {
                    throw new \Exception('该用户名已存在，用户名：'.$v[0]);
                }
                $arr[] = [
                    'username' => $v[0],
                    'status' => $v[1],
                    'password' => password_hash($v[2], PASSWORD_DEFAULT),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
            User::insert($arr);
            // 变更记录状态为成功
            ImportRecord::where('unique_code', $this->unique_code)
                ->update(['status' => 1, 'fails' => '']);
            Db::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // 变更记录状态为失败，并记录失败原因
            ImportRecord::where('unique_code', $this->unique_code)
                ->update(['status' => 2, 'fails' => $e->getMessage()]);
        }
    }
}
