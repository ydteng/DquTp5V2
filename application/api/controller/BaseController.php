<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/31
 * Time: 16:45
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token;
class BaseController extends Controller
{
    protected function checkPrimaryScope()
    {
        Token::needPrimaryScope();
    }

    protected function checkPackerScope()
    {
        Token::needPackerScope();
    }
}