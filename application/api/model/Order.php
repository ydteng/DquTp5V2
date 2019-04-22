<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/11
 * Time: 10:13
 */

namespace app\api\model;


use app\lib\exception\DeleteException;
use app\lib\exception\MissException;
use app\api\service\TimeOut as TimeOutService;
use app\api\service\Order as OrderService;
use app\lib\exception\packException;
use app\lib\exception\UserException;
use think\Build;

class Order extends BaseModel
{
    protected $hidden = ['user_id','packer_id','province_id','school_id','create_time','update_time','delete_time'];

    public function endPoint()
    {
        return $this->hasOne('UserAddress','user_id','user_id');
    }

    public function packerAddress()
    {
        return $this->hasOne('PackerInfo','user_id','packer_id');
    }
    //根据id获取订单
    public static function getOrderByOrderID($id){
        $order = self::where(['id' => $id])->find();
        if (!$order){
            throw new MissException();
        }
        return $order;
    }
    //获取发单人id
    public static function getReceiverByOrderID($id){
        $receiver = self::where(['id' => $id])->find();
        if (!$receiver){
            throw new MissException();
        }
        $receiverID = $receiver->user_id;
        return $receiverID;
    }
    //获取接单人id
    public static function getPackerByOrderID($id){
        $packer = self::where(['id' => $id])->find();
        if (!$packer){
            throw new MissException();
        }
        $packerID = $packer->packer_id;
        return $packerID;
    }
    //获取所有订单
    public static function getAllOrders($page,$uid)
    {
        //只返回同一学校的订单
        $address = new UserAddress();
        $address = $address->where(['user_id' => $uid])->find();
        $schoolID = $address->school_id;
        $orders = self::with('endPoint')->where(['status'=>'2000','school_id' => $schoolID])
            ->whereTime('create_time','>','-1 days')
            ->page($page,10)->order('create_time asc')->select();
        myHidden($orders,['detail','end_point.id','end_point.nickname','end_point.mobile']);
        if (!$orders){
            $orders =[];
        }
        return $orders;
    }

    //获取个人订单
    public static function getUserOrder($page,$uid)
    {
        $orders = self::with('endPoint')->where(['user_id' => $uid])
            ->page($page,10)->order('create_time desc')->select();
        myHidden($orders,['detail','end_point.id','end_point.nickname','end_point.mobile']);
        TimeOutService::orderTimeOut($orders);
        if (!$orders){
            return [];
        }
        return $orders;
    }
    //获取订单详情
    public static function getDetail($id,$uid){
        $detail = self::with('endPoint,packerAddress')->where(['id' => $id])->find();
        if (!$detail){
            throw new MissException();
        }
        $detail = OrderService::detailFrom($uid,$detail);
        return $detail;
    }
    //删除订单
    public static function deleteOrder($id){
        $order = self::where(['id' => $id])->find();
        if (!$order){
            throw new MissException();
        }
        //$result = $order->delete();
        $result = false;
        if (!$result){
            throw new DeleteException();
        }
        else{
            return true;
        }
    }
    //修改订单接单人
    public static function setPacker($id,$uid){
        $order = self::with('endPoint')->where(['id' => $id])->find();
        if (!$order){
            throw new MissException();
        }
        else if($order->packer_id){
            throw new packException(['msg' => '订单已被接取，不能重复接取','errorCode' => '50011']);
        }
        $order->save(['packer_id' => $uid,'status' => 3000]);
        myHidden($order,['user_id','packer_id',]);
        return $order;
    }
    //获取已接取的订单
    public  static function getPackedOrders($page,$uid){
        $orders = self::with('endPoint')->where(['packer_id' => $uid])
            ->page($page,10)->order('create_time desc')->select();
        myHidden($orders,['detail','end_point.id','end_point.nickname','end_point.mobile']);
        TimeOutService::orderTimeOut($orders);
        if (!$orders){
            return [];
        }
        return $orders;
    }
    //确认订单
    public static function confirm($id,$uid){
        $order = self::where(['id' => $id])->find();
        if (!$order){
            throw new MissException();
        }
        $result = OrderService::changConfirmStatus($id,$uid,$order);

        return $result;
    }
    //取消订单
    public static function cancel($id,$uid){
        $order = self::where(['id' => $id])->find();
        if (!$order){
            throw new MissException();
        }
        $result = OrderService::changeCancelStatus($order,$uid);
        if ($result == true){
            return true;
        }
    }
}