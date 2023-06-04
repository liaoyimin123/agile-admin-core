<?php

namespace App\Http\Middleware;

use App\Http\Service\TokenService;
use Closure;
use Illuminate\Http\Request;

class VerifyToken
{
    /**
     * 校验凭证
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Token');
        if (!$token) {
            return Response()->json(['msg' => '请上传凭证', 'data' => [], 'code' => 404]);
        }
        // 校验
        $tokenService = new TokenService();
        $array = $tokenService->checkToken($token);
        // 判断
        if ($array['code'] != 200) {
            return Response()->json(['msg' => $array['msg'], 'data' => [], 'code' => $array['code']]);
        }
        // 追加登录用户id
        $request['uid'] = $array['data']['uid'];
        return $next($request);
    }
}
