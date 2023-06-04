<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Jobs\ImportJob;
use App\Models\ImportRecord;
use App\Models\User;
use Illuminate\Http\Request;

class ImportRecordController extends Controller
{
    /**
     * 获取列表
     */
    public function list(Request $request)
    {
        $params = $request->all();
        $query = ImportRecord::orderBy('id', 'desc');
        // 筛选条件
        if (isset($params['model']) && $params['model'] != 'all') {
            $query->where('model', $params['model']);
        }
        if (isset($params['userId']) && $params['userId'] != 'all') {
            $query->where('uid', $params['userId']);
        }
        if (isset($params['status']) && $params['status'] != 'all') {
            $query->where('status', $params['status']);
        }
        if ((isset($params['createDateTime'][0]) && $params['createDateTime'][0]) && (isset($params['createDateTime'][1]) && $params['createDateTime'][1])) {
            $query->whereBetween('created_at', [date('Y-m-d H:i:s', $params['createDateTime'][0]/1000), date('Y-m-d H:i:s', $params['createDateTime'][1]/1000)]);
        }
        $total = $query->count();
        $records = $query->offset($this->offset)
            ->limit($this->pageSize)
            ->get()
            ->ToArray();
        // 获取相关uid数组
        $uids = array_column($records, 'uid');
        // 查询用户数据
        $users = User::whereIn('id', $uids)->get(['id', 'username']);
        // 处理数据
        foreach ($records as &$record) {
            $record['username'] = 'undefined';
            if ($record['model'] == 'user') {
                $record['modelStr'] = '用户模块';
            }
            foreach ($users as $user) {
                if ($record['uid'] == $user['id']) {
                    $record['username'] = $user['username'];
                }
            }
        }
        $data = [
            'items' => $records,
            'total' => $total
        ];
        return $this->success('获取列表成功', $data);
    }

    /**
     * 重新执行失败的excel文件导入
     */
    public function reExecute(Request $request)
    {
        $id = $request->input('id');
        $importRecord = ImportRecord::select('excel_path', 'unique_code', 'status')
            ->find($id);
        // 判断状态
        if ($importRecord['status'] != 2) {
            return $this->error('只能重新执行已失败的导入任务');
        }
        ImportJob::dispatch($importRecord['excel_path'], $importRecord['unique_code']);
        // 修改状态
        $ir = ImportRecord::find($id);
        $ir->status = 0;
        $ir->fails = '';
        $res = $ir->save();
        if ($res) {
            return $this->success('重新执行成功');
        }
        return $this->success('重新执行失败');
    }
}
