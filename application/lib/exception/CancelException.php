<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/26
 * Time: 18:19
 */

namespace app\lib\exception;


class CancelException extends BaseException
{
    public $code = 401;
    public $errorCode = 10078;
    public $msg = "订单已被取消，不能重复取消";
}