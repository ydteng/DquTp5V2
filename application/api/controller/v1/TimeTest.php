<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/26
 * Time: 21:02
 */

namespace app\api\controller\v1;


use app\api\model\User;
use app\api\model\Img;
use app\api\model\UserAddress;
use think\Cache;
use think\Db;

class TimeTest
{
    public function test(){
        // 获取表单上传文件
        $files = request()->file();
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
    }
}