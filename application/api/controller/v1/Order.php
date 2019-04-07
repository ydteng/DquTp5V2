<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/5
 * Time: 16:18
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\model\User as UserModel;
use app\api\model\Order as OrderModel;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\validate\PagingParameter;
use app\lib\exception\PlaceOrderException;
use app\lib\exception\UserException;
use app\lib\SuccessMessage;


class Order extends BaseController
{
    protected $beforeActionList = [
        'checkPackerScope' => ['only' => 'packorder']
    ];



    //下单
    public function PlaceOrder()
    {
        $validate = new OrderPlace();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        //判断每日下单上限
        $result = OrderService::limitPlaceOrderNum($uid);
        if ($result == false){
            return json(['msg' => '超过每日下单数上限']);
        }

        $user = UserModel::get($uid);
        if (!$user){
            throw new UserException();
        }
        $dataArray = $validate->getDataByRule(input('post.'));

        $dataArray['end_point_id'] = $uid;
        $dataArray['order_num'] = OrderService::makeOrderNum($uid);
        //判断订单是否重复
        $result = OrderService::repeatCheck($uid,$dataArray);
        if ($result == false){
            throw new PlaceOrderException();
        }
        $user->order()->save($dataArray);

        OrderService::addPackOrderNum($uid);

        return json(['order_num' => $dataArray['order_num']],201);
    }
    //获取个人订单
    public function getUserOrder()
    {
        (new PagingParameter())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会先检测有没有传值，报id参数错误的错
        $page = request()->param('page');
        $uid = TokenService::getCurrentUid();
        $orders = OrderModel::getUserOrder($page,$uid);
        return $orders;
    }
    //获取全部订单
    public function getAllOrder()
    {
        (new PagingParameter())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会先检测有没有传值，报id参数错误的错
        $page = request()->param('page');
        $uid = TokenService::getCurrentUid();
        if (!$uid){
            throw new UserException();
        }
        $orders = OrderModel::getAllOrders($page);
        return $orders;


    }
    //获取订单详情
    public function getOrderDetail(){
        (new IDMustBePositiveInt())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会先检测有没有传值，报id参数错误的错
        $uid = TokenService::getCurrentUid();
        $id = request()->param('id');
        if (!$uid){
            throw new UserException();
        }
        $detail = OrderModel::getDetail($id);
        return $detail;

    }
    //删除接口
    public function deleteOrder(){
        (new IDMustBePositiveInt())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会先检测有没有传值，报id参数错误的错
        $uid = TokenService::getCurrentUid();
        $id = request()->param('id');
        if (!$uid){
            throw new UserException();
        }
        $result = OrderModel::deleteOrder($id);
        if ($result){
            return new SuccessMessage();
        }
    }
    //接单接口
    public function packOrder(){
        (new IDMustBePositiveInt())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会先检测有没有传值，报id参数错误的错
        $uid = TokenService::getCurrentUid();
        $id = request()->param('id');
        $receiverID = OrderModel::getReceiverByOrderID($id);
        if (!$uid){
            throw new UserException();
        }
        if ($uid == $receiverID){
            //throw new pickException();
        }
        $order = OrderModel::setPacker($id,$uid);
        return $order;
    }
    //确认送达接口
    public function confirmOrder(){
        (new IDMustBePositiveInt())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会先检测有没有传值，报id参数错误的错
        $uid = TokenService::getCurrentUid();
        $id = request()->param('id');
        if (!$uid){
            throw new UserException();
        }
        $result = OrderModel::confirm($id,$uid);
        if ($result){
            return new SuccessMessage();
        }
    }
    //获取我接取的订单列表
    public function getPackedOrder(){
        (new PagingParameter())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会先检测有没有传值，报id参数错误的错
        $page = request()->param('page');
        $uid = TokenService::getCurrentUid();
        if (!$uid){
            throw new UserException();
        }
        $orders = OrderModel::getPackedOrders($page,$uid);
        return $orders;

    }
    //取消订单
    public function cancelOrder(){
        (new IDMustBePositiveInt())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会先检测有没有传值，报id参数错误的错
        $uid = TokenService::getCurrentUid();
        $id = request()->param('id');
        if (!$uid){
            throw new UserException();
        }
        $result = OrderModel::cancel($id,$uid);
        UserModel::subScore($uid,3);
        if ($result){
            return new SuccessMessage();
        }
    }
}