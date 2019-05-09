<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/5/9
 * Time: 9:21
 */

namespace app\api\controller\v1;
use app\api\service\ShortMessage as ShortMessageService;
use app\api\service\Token as TokenService;
use app\lib\exception\ShortMessageException;
use think\Cache;

class ShortMessage
{
    //获取短信验证码
    public function getShortMsgCode(){
        $uid = TokenService::getCurrentUid();
        ShortMessageService::sendShortMessage($uid);
    }
//    //验证短信验证码
//    public function VerifyShortMsgCode(){
//        $uid = TokenService::getCurrentUid();
//        $key = $uid . 'ShortMessage';
//        $exist = Cache::get($key);
//        if (!$exist){
//            throw new ShortMessageException(['errorCode' => 10640,'msg' => '验证码过期']);
//        }
//    }
}