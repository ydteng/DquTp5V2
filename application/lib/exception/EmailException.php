<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/4/26
 * Time: 22:14
 */

namespace app\lib\exception;


class EmailException extends BaseException
{
    public $code = 403;
    public $errorCode = 14010;
    public $msg = '邮件错误';
}