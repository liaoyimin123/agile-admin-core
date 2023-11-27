<?php

namespace App\Http\Controllers;

use App\Http\Service\TokenService;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * 登录
     */
    public function login(Request $request)
    {
        $params = $request->all();
        // 校验验证码
        if(!captcha_api_check($params['captcha'], $params['key'], 'math')) {
            return $this->error('验证码失效或者输入错误');
        }
        // 查询数据库
        $user = User::where('username', $params['username'])->first();
        if (!$user) {
            return $this->error('账号或者密码错误');
        }
        // 判断状态
        if (!$user['status']) {
            return $this->error('用户处于禁用状态，请联系管理员解除');
        }
        if (!password_verify($params['password'], $user['password'])) {
            return $this->error('账号或者密码错误');
        }
        // 生成token
        $token = new TokenService();
        $k = $token->createToken($user['id']);
        return $this->success('登录成功', ['token' => $k]);
    }

    /**
     * 获取验证码
     */
    public function getCaptcha()
    {
        $captcha = app('captcha')->create('math', true);
        return $this->success('获取验证码成功', $captcha);
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        return $this->success('退出登录成功');
    }
}
