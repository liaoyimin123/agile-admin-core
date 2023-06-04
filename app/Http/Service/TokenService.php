<?php

namespace App\Http\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Class TokenService
{
    public $key = 'mango';
    public $expTime = 60 * 60 * 7;
    /**
     * 创建 token
     * @param token过期时间 单位:秒 例子：7200=2小时
     * @return string
     */
    public function createToken($uid)
    {
        $nowTime = time();
        try {
            $token['iss'] = ''; //签发者 可选
            $token['aud'] = ''; //接收该JWT的一方，可选
            $token['iat'] = $nowTime; //签发时间
            $token['nbf'] = $nowTime + 30; //某个时间点后才能访问
            $token['exp'] = $nowTime + $this->expTime; //token过期时间
            $token['uid'] = $uid; //自定义参数
            $json = JWT::encode($token, $this->key, "HS256");
            return $json;
        } catch (\Firebase\JWT\ExpiredException $e) {//签名不正确
            $returndata['code'] = "104";
            $returndata['msg'] = $e->getMessage();
            $returndata['data'] = "";
            return $returndata;
        } catch (\Exception $e) {//其他错误
            $returndata['code'] = "199";
            $returndata['msg'] = $e->getMessage();
            $returndata['data'] = "";
            return $returndata;
        }
    }
 
    /**
     * 验证token是否有效
     * @param string $jwt 需要验证的token
     */
    public function checkToken($jwt = '')
    {
        try {
            JWT::$leeway = 60; //当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256')); //HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            $returndata['code'] = "200";
            $returndata['msg'] = "成功";
            $returndata['data'] = $arr;
            return $returndata;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            $returndata['code'] = "101";
            $returndata['msg'] = $e->getMessage();
            $returndata['data'] = "";
            return $returndata;
        } catch (\Firebase\JWT\BeforeValidException $e) {
            $returndata['code'] = "102";
            $returndata['msg'] = $e->getMessage();
            $returndata['data'] = "";
            return $returndata;
        } catch (\Firebase\JWT\ExpiredException $e) {
            $returndata['code'] = "103";
            $returndata['msg'] = $e->getMessage();
            $returndata['data'] = "";
            return $returndata;
        } catch (\Exception $e) {
            $returndata['code'] = "199";
            $returndata['msg'] = $e->getMessage();
            $returndata['data'] = "";
            return $returndata;
        }
    }
}