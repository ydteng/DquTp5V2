<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/5/9
 * Time: 9:44
 */

namespace app\lib\exception;


class ShortMessageException extends BaseException
{
    public $code = 403;
    public $errorCode = 16010;
    public $msg = "短信发送失败";
}