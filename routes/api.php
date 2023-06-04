<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\system\UserController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\system\AuthController;
use App\Http\Controllers\system\ImportRecordController;
use App\Http\Controllers\system\RoleController;
use App\Http\Controllers\system\SystemLogController;
use App\Http\Middleware\SystemLog;
use App\Http\Middleware\VerifyAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/infoByToken', [UserController::class, 'infoByToken']);
Route::middleware([VerifyToken::class, VerifyAuth::class, SystemLog::class])->group(function () {
    // 系统路由组
    Route::name('system')->group(function () {
        // 用户模块
        Route::get('/user/list', [UserController::class, 'list']);
        Route::post('/user/del', [UserController::class, 'del']);
        Route::get('/user/getInfo', [UserController::class, 'getInfo']);
        Route::post('/user/edit', [UserController::class, 'edit']);
        Route::post('/user/add', [UserController::class, 'add']);
        Route::post('/user/changeStatus', [UserController::class, 'changeStatus']);
        Route::post('/user/batchDel', [UserController::class, 'batchDel']);
        Route::post('/user/export', [UserController::class, 'export']);
        Route::post('/user/downloadTemplate', [UserController::class, 'downloadTemplate']);
        Route::post('/user/Import', [UserController::class, 'Import']);
        Route::post('/user/grantRole', [UserController::class, 'grantRole']);
        Route::get('/user/getGrantRole', [UserController::class, 'getGrantRole']);
        Route::get('/user/getUserNameList', [UserController::class, 'getUserNameList']);

        // 角色模块
        Route::post('/role/add', [RoleController::class, 'add']);
        Route::post('/role/del', [RoleController::class, 'del']);
        Route::post('/role/batchDel', [RoleController::class, 'batchDel']);
        Route::get('/role/getInfo', [RoleController::class, 'getInfo']);
        Route::post('/role/edit', [RoleController::class, 'edit']);
        Route::post('/role/changeStatus', [RoleController::class, 'changeStatus']);
        Route::get('/role/list', [RoleController::class, 'list']);
        Route::get('/role/getRoleNameList', [RoleController::class, 'getRoleNameList']);
        Route::post('/role/grantAuth', [RoleController::class, 'grantAuth']);
        Route::get('/role/getGrantAuth', [RoleController::class, 'getGrantAuth']);

        // 权限模块
        Route::post('/auth/add', [AuthController::class, 'add']);
        Route::post('/auth/del', [AuthController::class, 'del']);
        Route::post('/auth/batchDel', [AuthController::class, 'batchDel']);
        Route::get('/auth/getInfo', [AuthController::class, 'getInfo']);
        Route::get('/auth/getAuthNameList', [AuthController::class, 'getAuthNameList']);
        Route::post('/auth/edit', [AuthController::class, 'edit']);
        Route::post('/auth/changeStatus', [AuthController::class, 'changeStatus']);
        Route::get('/auth/list', [AuthController::class, 'list']);

        // 导入记录模块
        Route::get('/importRecord/list', [ImportRecordController::class, 'list']);
        Route::post('/importRecord/reExecute', [ImportRecordController::class, 'reExecute']);

        // 系统日志模块
        Route::get('/systemLog/list', [SystemLogController::class, 'list']);
    });

    // 公共模块
    Route::post('/common/uploadImg', [CommonController::class, 'uploadImg']);
    Route::post('/common/uploadExcel', [CommonController::class, 'uploadExcel']);
});

