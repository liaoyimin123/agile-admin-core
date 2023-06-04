<?php

namespace App\Http\Middleware;

use App\Models\Auth;
use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class VerifyAuth
{
    /**
     * 校验权限
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 1.查询用户所拥有的角色信息
        $user = User::select('roles')
            ->find($request['uid']);
        if (!$user) {
            return Response()->json(['msg' => '用户不存在，你无权访问', 'data' => [], 'code' => 403]);
        }
        $roleIds = json_decode($user['roles'], true);
        if (!$roleIds) {
            return Response()->json(['msg' => '没有授予角色，你无权访问', 'data' => [], 'code' => 403]);
        }
        // 2.查询角色所拥有的权限信息
        $role = Role::whereIn('id', $roleIds)
            ->pluck('auths')
            ->toArray();
        if (!$role) {
            return Response()->json(['msg' => '角色不存在，你无权访问', 'data' => [], 'code' => 403]);
        }
        // 定义权限id数组
        $authIds= [];
        foreach ($role as $v) {
            $temp = json_decode($v, true);
            $authIds = array_merge($authIds, $temp);
        }
        if (!$authIds) {
            return Response()->json(['msg' => '没有授予权限，你无权访问', 'data' => [], 'code' => 403]);
        }
        // 去重
        $authIds = array_unique($authIds);
        // 3.查询当前权限信息
        $auth = Auth::whereIn('id', $authIds)
            ->pluck('path')
            ->toArray();
        $path = str_replace('api', '', $request->path());
        if (!in_array($path, $auth)) {
            return Response()->json(['msg' => '权限不足，你无权访问', 'data' => [], 'code' => 403]);
        }
        return $next($request);
    }
}
