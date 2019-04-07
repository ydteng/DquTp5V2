<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/4/7
 * Time: 16:21
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $errorCode = 13010;
    public $msg = '权限不够';
}