<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            // 用户管理
            $userData = [
                'name' => '用户管理',
                'pid' => 0,
                'status' => 1,
                'path' => '/user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $id = DB::table('auths')->insertGetId($userData);
            $userAuthData = [
                [
                    'name' => '用户列表',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/list',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '删除用户',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/del',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '获取用户详情',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/getInfo',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '编辑用户',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/edit',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '添加用户',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/add',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '变更用户状态',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/changeStatus',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '批量删除用户',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/batchDel',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '用户导出',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/export',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '下载导入用户模板',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/downloadTemplate',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '用户导入',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/Import',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '授予角色',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/grantRole',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '获取用户角色',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/getGrantRole',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '获取用户名列表',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/user/getUserNameList',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
            ];
            DB::table('auths')->insert($userAuthData);

            // 角色管理
            $roleData = [
                'name' => '角色管理',
                'pid' => 0,
                'status' => 1,
                'path' => '/role',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $id = DB::table('auths')->insertGetId($roleData);
            $roleAuthData = [
                [
                    'name' => '添加角色',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/add',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '删除角色',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/del',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '批量删除角色',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/batchDel',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '获取角色详情',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/getInfo',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '编辑角色',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/edit',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '变更角色状态',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/changeStatus',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '角色列表',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/list',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '获取角色名列表',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/getRoleNameList',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '授予权限',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/grantAuth',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '获取角色权限',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/role/getGrantAuth',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
            ];
            DB::table('auths')->insert($roleAuthData);

            // 权限管理
            $authData = [
                'name' => '权限管理',
                'pid' => 0,
                'status' => 1,
                'path' => '/auth',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $id = DB::table('auths')->insertGetId($authData);
            $authAuthData = [
                [
                    'name' => '添加权限',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/auth/add',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '删除权限',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/auth/del',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '批量删除权限',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/auth/batchDel',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '获取权限详情',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/auth/getInfo',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '获取权限名列表',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/auth/getAuthNameList',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '编辑权限',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/auth/edit',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '变更权限状态',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/auth/changeStatus',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '权限列表',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/auth/list',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
            ];
            DB::table('auths')->insert($authAuthData);

            // 导入记录
            $importRecordData = [
                'name' => '导入记录',
                'pid' => 0,
                'status' => 1,
                'path' => '/importRecord',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $id = DB::table('auths')->insertGetId($importRecordData);
            $importRecordAuthData = [
                [
                    'name' => '导入列表',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/importRecord/list',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'name' => '重新执行',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/importRecord/reExecute',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
            ];
            DB::table('auths')->insert($importRecordAuthData);

            // 系统日志
            $systemLogData = [
                'name' => '系统日志',
                'pid' => 0,
                'status' => 1,
                'path' => '/systemLog',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $id = DB::table('auths')->insertGetId($systemLogData);
            $systemLogAuthData = [
                [
                    'name' => '日志列表',
                    'pid' => $id,
                    'status' => 1,
                    'path' => '/systemLog/list',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
            ];
            DB::table('auths')->insert($systemLogAuthData);
            Db::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
        }
    }
}
