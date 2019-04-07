<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/25
 * Time: 20:04
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $errorCode = 11010;
    public $msg = '微信服务接口调用失败';
}