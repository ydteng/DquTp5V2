<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/25
 * Time: 19:22
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;

class Token
{
    //获取令牌
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get();
        return [
            'token' => $token
        ];
    }

    //验证令牌合法性
    public function verifyToken($token='')
    {
        if(!$token){
            throw new ParameterException([
                'msg' =>'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }
}