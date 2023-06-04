<?php

namespace App\Http\Middleware;

use App\Models\SystemLog as ModelsSystemLog;
use Closure;
use Illuminate\Http\Request;

class SystemLog
{
    /**
     * 记录系统操作日志
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $uid = $request['uid'];
        $method = $request->method();
        // 如果是get请求则不记录，业务逻辑中，get请求为查询操作
        if ($method == 'GET') {
            return $next($request);
        }
        $path = str_replace('api', '', $request->path());
        $params = $request->all();
        $ip = $request->ip();
        $sysLog = new ModelsSystemLog();
        $sysLog->uid = $uid;
        $sysLog->method = $method;
        $sysLog->path = $path;
        $sysLog->params = json_encode($params, JSON_UNESCAPED_UNICODE);
        $sysLog->ip = $ip;
        $sysLog->created_at = date('Y-m-d H:i:s');
        $sysLog->save();
        return $next($request);
    }
}
