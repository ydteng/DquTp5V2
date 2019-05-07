<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/26
 * Time: 20:12
 */

namespace app\api\service;


use app\api\model\PackerInfo;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;
use app\lib\enum\ScopeEnum;
use app\api\model\Order as OrderModel;
use app\api\model\PackerInfo as PackerInfoModel;

class Token
{
    public static function generateToken()
    {
        //选取32个字符组成一组随机字符串
        $randChars = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME'];
        $salt = config('secure.salt');

        return md5($randChars.$timestamp.$salt);
    }

//检测接单权限
    public static function needPackerScope()
    {
        (new IDMustBePositiveInt())->goCheck();
        $id = input('param.id');
        $order = OrderModel::getOrderByOrderID($id);
        $receiverID = $order->user_id;
        $packerID= $order->packer_id;
        $scope = self::getCurrentTokenVar('scope');
        $uid = self::getCurrentUid();

        if ($uid == $receiverID || $uid == $packerID){
            return true;
        }
        //以下为临时代码，下次更新将删除

        $packerInfo = new PackerInfoModel();
        $packerInfo = $packerInfo->where(['user_id' => $uid])->find();
        if (!$packerInfo){
            throw new ForbiddenException();
        }
        else if ($packerInfo->status == 300){
            return true;
        }
        else if ($packerInfo->status == 100 || $packerInfo->status == 200){
            throw new ForbiddenException();
        }

        //到上面的代码

        if ($scope){
            if ($scope == ScopeEnum::Super) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }
//检测普通用户权限
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            }
            else{
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }


    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        if($exist){
            return true;
        }
        else{
            return false;
        }
    }

    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars  = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }
        else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }
            else{
                throw new Exception("尝试获取的Token变量不存在");
            }
        }
    }

    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }
}