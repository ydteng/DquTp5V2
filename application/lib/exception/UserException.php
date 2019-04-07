<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/3
 * Time: 13:46
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $errorCode = 90010;
    public $msg = '用户不存在';
}