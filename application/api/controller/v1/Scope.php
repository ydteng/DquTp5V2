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
use app\lib\exception\AddressException;
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

        //权限申请失败超过24小时
        $hour = subTime($packerInfo,'H');
        if ($hour>=24 && $packerInfo->status == 200){
            $packerInfo->save(['status' =>0]);
            return ['status' =>0];
        }
        if ($hour>=48 && $packerInfo->status == 100){
            $packerInfo->save(['status' =>0]);
            return ['status' =>0];
        }
        return ['status' =>$packerInfo->status];

    }
    //申请接单
    public function applyScope(){
        $validate = new ApplyScope();
        $validate->goCheck();

        // 获取表单上传文件
        $files = request()->file('image');
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }








        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user){
            throw new UserException();
        }

        $userAddress = $user->address;
        if(!$userAddress){
            throw new AddressException();
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
        $dataArray['uid'] = $uid;
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
        $dataArray['uid'] = $uid;
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