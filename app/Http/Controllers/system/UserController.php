<?php

namespace App\Http\Controllers\system;

use App\Http\Service\TokenService;
use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Jobs\ImportJob;
use App\Models\Auth;
use App\Models\ImportRecord;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    /**
     * 根据token获取用户详情
     */
    public function infoByToken(Request $request)
    {
        $token = $request->input('token');
        // 校验token
        $tokenService = new TokenService();
        $array = $tokenService->checkToken($token);
        if (!isset($array['data']['uid'])) {
            return $this->error($array['msg'], [], $array['code']);
        }
        // 查询数据库
        $user = User::find($array['data']['uid']);
        // 判断状态
        if (!$user['status']) {
            return $this->error('用户处于禁用状态，请联系管理员解除');
        }
        return $this->success('获取详情成功', $user);
    }

    /**
     * 获取用户列表
     */
    public function list(Request $request)
    {
        $params = $request->all();
        $query = User::orderBy('id', 'desc');
        // 搜索条件
        if (isset($params['username']) && $params['username']) {
            $query->where('username', 'like', "%{$params['username']}%");
        }
        if (isset($params['status']) && $params['status'] != 'all') {
            $query->where('status', $params['status']);
        }
        if ((isset($params['createDateTime'][0]) && $params['createDateTime'][0]) && (isset($params['createDateTime'][1]) && $params['createDateTime'][1])) {
            $query->whereBetween('created_at', [date('Y-m-d H:i:s', $params['createDateTime'][0]/1000), date('Y-m-d H:i:s', $params['createDateTime'][1]/1000)]);
        }
        $total = $query->count();
        $users = $query->offset($this->offset)
            ->limit($this->pageSize)->get();
        $data = [
            'items' => $users,
            'total' => $total
        ];
        return $this->success('获取列表成功', $data);
    }

    /**
     * 删除用户
     */
    public function del(Request $request)
    {
        $id = $request->input('id');
        $res = User::where('id', $id)->delete();
        if ($res) {
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }

    /**
     * 获取用户
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $user = User::where('id', $id)->first();
        return $this->success('获取用户信息成功', $user);
    }

    /**
     * 编辑用户
     */
    public function edit(Request $request)
    {
        $params = $request->all();
        $usernameIsRepeat = User::fieldRepeatCheck('username', $params['username'], $params['id']);
        if ($usernameIsRepeat) {
            return $this->error('该用户名已存在');
        }
        $user = User::find($params['id']);
        $user->username = $params['username'];
        $user->avatar = $params['avatar'];
        if (isset($params['password']) && $params['password']) {
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            $user->password = $params['password'];
        }
        $res = $user->save();
        if ($res) {
            return $this->success('编辑成功');
        }
        return $this->error('编辑失败');
    }

    /**
     * 添加用户
     */
    public function add(Request $request)
    {
        $params = $request->all();
        $usernameIsRepeat = User::fieldRepeatCheck('username', $params['username']);
        if ($usernameIsRepeat) {
            return $this->error('该用户名已存在');
        }
        $user = new User();
        $user->username = $params['username'];
        $user->avatar = $params['avatar'];
        if (isset($params['password']) && $params['password']) {
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        } else {
            $params['password'] = password_hash('123456', PASSWORD_DEFAULT);
        }
        $user->password = $params['password'];
        $res = $user->save();
        if ($res) {
            return $this->success('添加成功');
        }
        return $this->error('添加失败');
    }

    /**
     * 变更状态
     */
    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $user = User::find($id);
        $user->status = $status;
        $res = $user->save();
        if ($res) {
            return $this->success('变更状态成功');
        }
        return $this->error('变更状态失败');
    }

    /**
     * 批量删除
     */
    public function batchDel(Request $request)
    {
        $ids = $request->input('ids');
        $res = User::destroy($ids);
        if ($res) {
            return $this->success('批量删除成功');
        }
        return $this->error('批量删除失败');
    }

    /**
     * Excel导出
     */
    public function export(Request $request)
    {
        // 设置超时时间为永久
        set_time_limit(0);
        $exportDateTime = $request->input('exportDateTime');
        if (!$exportDateTime[0] || !$exportDateTime[1]) {
            return $this->error('请选择时间范围');
        }
        $list = User::whereBetween('created_at', [date('Y-m-d H:i:s', $exportDateTime[0]/1000), date('Y-m-d H:i:s', $exportDateTime[1]/1000)])
            ->select('username', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
        $list = $list->makeHidden('avatar_url');
        $header = ['用户名', '状态', '创建时间'];
        $sheet = new UsersExport($list, $header);
        return Excel::download($sheet, '用户数据.xlsx');
    }

    /**
     * 下载模板
     */
    public function downloadTemplate()
    {
        $list = User::where('id', '<', 1)
            ->get();
        $header = ['用户名', '状态', '密码'];
        $sheet = new UsersExport($list, $header);
        return Excel::download($sheet, '用户模板.xlsx');
    }

    /**
     * Excel导入
     */
    public function Import(Request $request)
    {
        $excelPath = $request->input('excelPath');
        $unique_code = Uuid::uuid1();
        ImportJob::dispatch($excelPath, $unique_code);
        $user = User::find($request->uid);
        $ir = new ImportRecord();
        $ir->model = 'user';
        $ir->uid = $user['id'];
        $ir->excel_path = $excelPath;
        $ir->unique_code = $unique_code;
        $ir->created_at = date('Y-m-d H:i:s');
        $res = $ir->save();
        if ($res) {
            return $this->success('导入成功');
        }
        return $this->error('导入失败'); 
    }

    /**
     * 授予角色
     */
    public function grantRole(Request $request)
    {
        $id = $request->input('id');
        $roleIds = $request->input('roleIds');
        // 去重
        $roleIds = array_unique($roleIds);
        $roleIds = json_encode($roleIds);
        $user = User::find($id);
        $user->roles = $roleIds;
        $res = $user->save();
        if ($res) {
            return $this->success('授予角色成功');
        }
        return $this->error('授予角色失败');
    }

    /**
     * 查询授予的角色
     */
    public function getGrantRole(Request $request)
    {
        $id = $request->input('id');
        $user = User::select('roles')
            ->find($id);
        $roles = json_decode($user['roles'], true);
        if (!$roles) {
            $roles = [];
        }
        return $this->success('获取用户角色成功', $roles);
    }

    /**
     * 获取用户名称列表
     */
    public function getUserNameList()
    {
        $list = User::get(['id', 'username']);
        $list = $list->makeHidden('avatar_url');
        return $this->success('获取用户名称列表成功', $list);
    }
}
