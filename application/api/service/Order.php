<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/11
 * Time: 10:30
 */

namespace app\api\service;
use app\api\model\Order as OrderModel;
use app\lib\exception\CancelException;
use app\lib\exception\confirmException;
use app\lib\exception\TimeOutException;
use think\Cache;
use think\Exception;

class Order
{
    //生成订单号  格式为当前日期+10000+uid+随机数
    public static function makeOrderNum($uid){
        $num = 10000+$uid;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $timestamp = $strPol[rand(0,25)].date('Ymd',time()).$num.rand(11111111,99999999);
        return $timestamp;
    }

    //改变确定订单状态
    public static function changConfirmStatus($id,$uid){
        /*
         * 改变流程
         * 首先获取订单的接单人id和发单人id
         * 1.如果用户uid和发单人id相同，则在判断接单人是否已经确认订单，如果接单人未确认（即状态码为3000），则改变为4001（发单方确认），
         * 否则改变为完成状态6000
         * 2.如果用户uid和接单人id相同，则在判断发单人是否已经确认订单，如果发单人未确认（即状态码为3000），则改变为4000（接单方确认），
         * 否则改变为完成状态6000
         *
         * */
        $msg = '确认成功';
        $order = OrderModel::get($id);
        $status = $order->status;

        //计算订单时间

        $hour = subTime($order,'H');


        $receiverID = OrderModel::getReceiverByOrderID($id);
        $packerID = OrderModel::getPackerByOrderID($id);

        //为了保险加上的时间检测
        if ($hour >= 24){
            throw new confirmException(['msg' => '订单接单超过24小时无法确定']);
        }
        if ($status == 2000){
            throw new confirmException(['msg' => '非法操作，未接取的订单无法确认']);
        }
        if ($status == 6000 && $status == 1000){
            throw new confirmException();
        }

        if ($uid == $receiverID){
            if ($status == 4000){
                $order->save(['status' => 6000]);
                return $msg;
            }
            else if($status == 4001){
                throw new confirmException(['msg' => '请不要重复确认订单']);
            }
            else{
                $order->save(['status' => 4001]);
                return $msg;
            }
        }
        else if ($uid == $packerID){
            if ($status == 4001){
                $order->save(['status' => 6000]);
                return $msg;
            }
            else if($status == 4000){
                throw new confirmException(['msg' => '请不要重复确认订单']);
            }
            else{
                $order->save(['status' => 4000]);
                return $msg;
            }
        }
        else{
            throw new Exception('未知错误，发生于uid与receiverID、packerID比较时');
        }
    }


    //限制每日的接单数
    public static function limitPlaceOrderNum($uid){
        /*
         * 1.检测缓存是否存在，不存在则把今天的凌晨时间存到缓存中。继续后面的下单操作，下单成功则单数加一
         * 2.存在则取出缓存中的时间与现在的时间做对比，若超过24小时则把今天的凌晨时间存到缓存中，继续后面的操作，下单成功则单数加一
         * 3.如果未超过24小时且单数不超过10，则直接进行后面的操作，下单成功则单数加一。
         * 4.若单数超过10，则返回‘超过每日下单数限制’
         * */
        $BeforeDate = date('Y-m-d') .' '. '00:00:00';
        $currentDate = date('Y-m-d H:i:s');
        $packOrderNum = 0;
        $cacheValue = ['date' => $BeforeDate,'Num' => $packOrderNum];
        $exit = Cache::get($uid);
        if (!$exit){
            $cacheValue = json_encode($cacheValue );
            cache($uid,$cacheValue);
            return true;
        }
        else{
            $value = Cache::get($uid);
            if (!is_array($value)){
                $value = json_decode($value,true);
            }
            $subDate = floor((strtotime($currentDate)-strtotime($value['date']))/3600);

            if ($subDate >= 24){
                $cacheValue = json_encode($cacheValue );
                cache($uid,$cacheValue);
                return true;
            }
            else if($subDate < 24 && $value['Num'] < 20){
                return true;
            }
            else if($subDate < 24 && $value['Num'] >= 20){
                return false;
            }
        }
    }

    //增加发单数
    public static function addPackOrderNum($uid){
        $val = Cache::get($uid);
        if (!is_array($val)){
            $val = json_decode($val,true);
        }
        if($val['Num']<20){
            $val['Num'] = $val['Num']+1;
        }
        $val = json_encode($val);
        cache($uid,$val);
    }

    //取消订单的状态
    public static function changeCancelStatus($order,$uid){
        $userID = $order->user_id;
        $packerID = $order->packer_id;
        $status = $order->status;
        $hour = subTime($order,'H');
        //为了保险加上的时间检测
        if ($hour >=24){
            throw  new TimeOutException();
        }
        else if ($uid == $userID && $status == 3000){
            return false;
        }
        else if ($uid == $userID && $status == 2000){
            return true;
        }
        else if ($status == 1000){
            throw new CancelException();
        }
        else if($uid != $packerID){
            throw new CancelException(['msg' => '非法取消订单']);
        }
        else{
            return true;
        }
    }
}