<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/5/10
 * Time: 19:47
 */

namespace app\lib\exception;


class FileException extends BaseException
{
    public $code = 403;
    public $errorCode = 17010;
    public $msg = '文件上传失败';
}