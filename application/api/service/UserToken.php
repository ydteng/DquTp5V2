<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/25
 * Time: 19:36
 */
/*处理复杂的业务逻辑*/
namespace app\api\service;


use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;
class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl  = sprintf(config('wx.login_url'),
            $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }
        else{
            $LoginFail = array_key_exists('errcode',$wxResult);
            if($LoginFail){
                $this->processLoginError($wxResult);
            }
            else{
                return $this->grantToken($wxResult);
            }
        }
    }
    private function grantToken($wxResult){
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }
        else{
            $uid = $this->newUser($openid);
        }
        $cacheValue = $this->prepareCacheValue($wxResult,$uid);
        $token = $this->saveToCache($cacheValue);
        return $token;
    }
    //写入缓存
    private function saveToCache($cacheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in');
        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'mes' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }

        return $key;

    }
    //准备value数据
    private function prepareCacheValue($wxResult,$uid)
    {
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = 16;
        return $cacheValue;
    }

    //若用户不存在，则创建用户
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']

        ]);
    }
}