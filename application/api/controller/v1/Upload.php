<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/5/11
 * Time: 11:57
 */

namespace app\api\controller\v1;
use app\api\model\UserAddress as UserAddressModel;
use app\lib\exception\AddressException;
use app\lib\exception\FileException;
use app\api\service\Token as TokenService;
use app\lib\SuccessMessage;

class Upload
{
    public function uploadImg(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddressModel::getUserAddress($uid);
        if (!$userAddress){
            throw new AddressException();
        }

        // 获取表单上传文件
        $filesList =[];
        $files = request()->file();
        if (!$files){
            throw new FileException(['msg' => '上传的文件为空']);
        }
        foreach($files as $key => $value){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $dateDir = date("Ymd");
            $info = $value->rule('uniqid')->validate(['size'=>10485760,'ext'=>'jpg,png'])
                ->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . $dateDir . DS .$uid);
            if($info){
                // 成功上传后 获取上传信息
                $fileName = $info->getSaveName();
                $path = ROOT_PATH . 'public' . DS . 'uploads' . DS .$fileName;
                $filesList[$key]=$path;
                return new SuccessMessage();

            }else{
                // 上传失败获取错误信息
                throw new FileException(['msg' => $value->getError()]);
            }
        }
    }
}