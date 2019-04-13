<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/4/13
 * Time: 11:05
 */

namespace app\api\controller\v1;


use app\api\model\PackerInfo;
use app\api\service\Email;
use app\api\validate\ApplyScope;
use app\lib\exception\MissException;
use app\lib\exception\UserException;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\api\model\PackerInfo as PackerInfoModel;
use app\lib\SuccessMessage;

class Scope
{
    //获取申请状态
    public function getStatus(){
        $uid = TokenService::getCurrentUid();
        $packerInfo = new PackerInfoModel();
        $packerInfo = $packerInfo->where(['user_id' => $uid])->find();
        if (!$packerInfo)
        {
            throw new MissException();
        }
        return $packerInfo->status;

    }
    //申请接单
    public function applyScope(){
        $validate = new ApplyScope();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user){
            throw new UserException();
        }
        $dataArray = $validate->getDataByRule(input('post.'));
        $reason = $dataArray['reason'];
        unset($dataArray['reason']);
        $packerInfo = $user->packer;
        if (!$packerInfo){
            $user->packer()->save($dataArray);
        }
        else{
            $user->packer->save($dataArray);
        }
        $dataArray['reason'] = $reason;
        $dataArray['send_num'] = $user->send_num;
        $dataArray['pack_num'] = $user->pack_num;
        Email::send($dataArray);
        return new SuccessMessage();
    }
}