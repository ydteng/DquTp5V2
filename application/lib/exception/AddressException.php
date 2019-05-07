<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/5/6
 * Time: 16:17
 */

namespace app\lib\exception;


class AddressException extends BaseException
{
    public $code = 403;
    public $errorCode = 15010;
    public $msg = '用户地址不存在';
}