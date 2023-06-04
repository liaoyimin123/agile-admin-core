<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * 添加角色
     */
    public function add(Request $request)
    {
        $params = $request->all();
        $nameIsRepeat = Role::fieldRepeatCheck('name', $params['name']);
        if ($nameIsRepeat) {
            return $this->error('角色名已存在');
        }
        $role = new Role();
        $role->name = $params['name'];
        $res = $role->save();
        if ($res) {
            return $this->success('添加成功');
        }
        return $this->error('添加失败');
    }

    /**
     * 删除角色
     */
    public function del(Request $request)
    {
        $id = $request->input('id');
        $res = Role::where('id', $id)->delete();
        if ($res) {
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }

    /**
     * 批量删除
     */
    public function batchDel(Request $request)
    {
        $ids = $request->input('ids');
        $res = Role::destroy($ids);
        if ($res) {
            return $this->success('批量删除成功');
        }
        return $this->error('批量删除失败');
    }

    /**
     * 获取角色
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $role = Role::where('id', $id)->first();
        return $this->success('获取角色信息成功', $role);
    }

    /**
     * 编辑角色
     */
    public function edit(Request $request)
    {
        $params = $request->all();
        $nameIsRepeat = Role::fieldRepeatCheck('name', $params['name'], $params['id']);
        if ($nameIsRepeat) {
            return $this->error('角色名已存在');
        }
        $role = Role::find($params['id']);
        $role->name = $params['name'];
        $res = $role->save();
        if ($res) {
            return $this->success('编辑成功');
        }
        return $this->error('编辑失败');
    }

    /**
     * 变更状态
     */
    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $role = Role::find($id);
        $role->status = $status;
        $res = $role->save();
        if ($res) {
            return $this->success('变更状态成功');
        }
        return $this->error('变更状态失败');
    }

    /**
     * 获取角色列表
     */
    public function list(Request $request)
    {
        $params = $request->all();
        $query = Role::orderBy('id', 'desc');
        // 搜索条件
        if (isset($params['name']) && $params['name']) {
            $query->where('name', 'like', "%{$params['name']}%");
        }
        if (isset($params['status']) && $params['status'] != 'all') {
            $query->where('status', $params['status']);
        }
        if ((isset($params['createDateTime'][0]) && $params['createDateTime'][0]) && (isset($params['createDateTime'][1]) && $params['createDateTime'][1])) {
            $query->whereBetween('created_at', [date('Y-m-d H:i:s', $params['createDateTime'][0]/1000), date('Y-m-d H:i:s', $params['createDateTime'][1]/1000)]);
        }
        $total = $query->count();
        $roles = $query->offset($this->offset)
            ->limit($this->pageSize)->get();
        $data = [
            'items' => $roles,
            'total' => $total
        ];
        return $this->success('获取列表成功', $data);
    }

    /**
     * 获取角色名称列表
     */
    public function getRoleNameList()
    {
        $list = Role::orderBy('id', 'desc')
            ->get(['id', 'name', 'status']);
        return $this->success('获取角色名称列表成功', $list);
    }

    /**
     * 授予权限
     */
    public function grantAuth(Request $request)
    {
        $id = $request->input('id');
        $authIds = $request->input('authIds');
        $role = Role::find($id);
        // 去重
        $authIds = array_unique($authIds);
        // 转json
        $authIds = json_encode($authIds);
        $role->auths = $authIds;
        $res = $role->save();
        if ($res) {
            return $this->success('授予权限成功');
        }
        return $this->error('授予权限失败');
    }

    /**
     * 查询授予权限
     */
    public function getGrantAuth(Request $request)
    {
        $id = $request->input('id');
        $role = Role::select('auths')
            ->find($id);
        // 转数组
        $auths = json_decode($role['auths'], true);
        // 去重
        if ($auths) {
            $auths = array_unique($auths);
        } else {
            $auths = [];
        }
        return $this->success('获取选中权限成功', $auths);
    }
}
