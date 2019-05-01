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
use app\lib\exception\EmailException;
use app\lib\exception\UserException;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\api\model\PackerInfo as PackerInfoModel;
use app\lib\SuccessMessage;
use think\Cache;


class Scope
{
    //获取申请状态
    public function getStatus(){
        $uid = TokenService::getCurrentUid();
        $packerInfo = new PackerInfoModel();
        $packerInfo = $packerInfo->where(['user_id' => $uid])->find();
        if (!$packerInfo)
        {
            return ['status' =>0];
        }
        return ['status' =>$packerInfo->status];

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
        $dataArray['status'] = 100;
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
        Email::send('有人申请接单权限，请尽快处理',$dataArray);
        return new SuccessMessage();
    }
    //反馈
    public function feedback(){
        $uid = TokenService::getCurrentUid();
        $dataArray = input('post.');
        $key = 'feedback' . $uid;
        $exist = Cache::get($key);
        if (!$exist){
            cache($key,$dataArray,86400);
        }
        else{
            throw new EmailException(['errorCode' => '14020','msg' => '反馈错误，24小时只能反馈一次']);
        }

        Email::send('意见反馈',$dataArray);
        return new SuccessMessage();
    }
    //协议信息
    public function agreement(){
        $title = '一校派服务协议';
        $text = file_get_contents('agreement.txt');
        return ['title' => $title,'text' => $text];

    }
}