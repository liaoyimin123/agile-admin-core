<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    /**
     * 获取列表
     */
    public function list(Request $request)
    {
        $params = $request->all();
        $query = SystemLog::orderBy('id', 'desc');
        // 筛选条件
        if (isset($params['userId']) && $params['userId'] != 'all') {
            $query->where('uid', $params['userId']);
        }
        if (isset($params['method']) && $params['method']) {
            $query->where('method', 'like', "%{$params['method']}%");
        }
        if (isset($params['path']) && $params['path']) {
            $query->where('path', 'like', "%{$params['path']}%");
        }
        if (isset($params['ip']) && $params['ip']) {
            $query->where('ip', 'like', "%{$params['ip']}%");
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
}
