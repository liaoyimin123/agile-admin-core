<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 添加权限
     */
    public function add(Request $request)
    {
        $params = $request->all();
        $pathIsRepeat = Auth::fieldRepeatCheck('path', $params['path']);
        if ($pathIsRepeat) {
            return $this->error('权限路由已存在');
        }
        $auth = new Auth();
        $auth->name = $params['name'];
        $auth->path = $params['path'];
        $auth->pid = $params['pid'];
        $res = $auth->save();
        if ($res) {
            return $this->success('添加成功');
        }
        return $this->error('添加失败');
    }

    /**
     * 删除权限
     */
    public function del(Request $request)
    {
        $id = $request->input('id');
        // 查询子级
        $ids = Auth::where('pid', $id)
            ->pluck('id')
            ->toArray();
        // 追加到数组
        $ids[] = $id;
        $res = Auth::destroy($ids);
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
        // 查询子级
        $childrenIds = Auth::whereIn('pid', $ids)
            ->pluck('id')
            ->toArray();
        // 合并数组
        $ids = array_merge($ids, $childrenIds);
        $res = Auth::destroy($ids);
        if ($res) {
            return $this->success('批量删除成功');
        }
        return $this->error('批量删除失败');
    }

    /**
     * 获取权限
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $auth = Auth::where('id', $id)->first();
        return $this->success('获取权限信息成功', $auth);
    }

    /**
     * 获取权限名称列表
     */
    public function getAuthNameList()
    {
        $list = Auth::where('pid', 0)
            ->get(['id', 'name']);
        return $this->success('获取权限名称列表成功', $list);
    }

    /**
     * 编辑权限
     */
    public function edit(Request $request)
    {
        $params = $request->all();
        $pathIsRepeat = Auth::fieldRepeatCheck('path', $params['path'], $params['id']);
        if ($pathIsRepeat) {
            return $this->error('权限路由已存在');
        }
        $auth = Auth::find($params['id']);
        $auth->name = $params['name'];
        $auth->path = $params['path'];
        $auth->pid = $params['pid'];
        $res = $auth->save();
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
        // 查询子集
        $ids = Auth::where('pid', $id)
            ->pluck('id')
            ->toArray();
        // 追加到数组
        $ids[] = $id;
        $res = Auth::whereIn('id', $ids)
            ->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
        if ($res) {
            return $this->success('变更状态成功');
        }
        return $this->error('变更状态失败');
    }

    /**
     * 获取权限列表
     */
    public function list()
    {
        $list = Auth::where('pid', 0)
            ->get()
            ->toArray();
        $children = Auth::where('pid', '<>', 0)
            ->get()
            ->toArray();
        foreach ($list as &$lv) {
            $lv['disabled'] = $lv['status'] ? false : true;
            $lv['children'] = [];
            foreach ($children as &$cv) {
                if (!isset($cv['disabled'])) {
                    $cv['disabled'] = $cv['status'] ? false : true;
                }
                if ($lv['id'] == $cv['pid']) {
                    $lv['children'][] = $cv;
                }
            }
        }
        return $this->success('获取列表成功', $list);
    }
}
