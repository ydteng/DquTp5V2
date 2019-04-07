<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/30
 * Time: 18:11
 */

namespace app\lib\exception;


class DeleteException extends BaseException
{
    public $code = 403;
    public $errorCode = 12010;
    public $msg = '删除失败';
}