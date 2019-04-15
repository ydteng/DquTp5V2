<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/11
 * Time: 10:30
 */

namespace app\api\service;
use app\api\model\Order as OrderModel;
use app\api\model\User as UserModel;
use app\lib\exception\CancelException;
use app\lib\exception\confirmException;
use app\lib\exception\TimeOutException;
use think\Cache;
use think\Exception;

class Order
{
    //生成订单号  格式为当前日期+10000+uid+随机数
    public static function makeOrderNum($uid){
        $num = 10000 + $uid;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $timestamp = $strPol[rand(0,25)].date('Ymd',time()).$num.rand(11111111,99999999);
        return $timestamp;
    }

    //改变确定订单状态
    public static function changConfirmStatus($id,$uid,$order){
        /*
         * 改变流程
         * 首先获取订单的接单人id和发单人id
         * 1.如果用户uid和发单人id相同，则在判断接单人是否已经确认订单，如果接单人未确认（即状态码为3000），则改变为4001（发单方确认），
         * 否则改变为完成状态6000
         * 2.如果用户uid和接单人id相同，则在判断发单人是否已经确认订单，如果发单人未确认（即状态码为3000），则改变为4000（接单方确认），
         * 否则改变为完成状态6000
         *
         * */
        $status = $order->status;

        //计算订单时间

        $hour = subTime($order,'H');


        $receiverID = OrderModel::getReceiverByOrderID($id);
        $packerID = OrderModel::getPackerByOrderID($id);

        //为了保险加上的时间检测
        if ($hour >= 24){
            throw new TimeOutException();
        }
        if ($status == 2000){
            throw new confirmException([
                'errorCode' => '20011',
                'msg' => '非法操作，未接取的订单无法确认'
            ]);
        }
        if ($status == 6000 && $status == 1000){
            throw new confirmException();
        }

        if ($uid == $receiverID){
            if ($status == 4000){
                $order->save(['status' => 6000]);
                //接单方确认加分
                UserModel::addScore($receiverID,5);
                UserModel::addScore($packerID,5);
                return true;
            }
            else if($status == 4001){
                throw new confirmException([
                    'errorCode' => '20012',
                    'msg' => '请不要重复确认一个订单'
                ]);
            }
            else{
                $order->save(['status' => 4001]);
                //接单方确认加分
                UserModel::addScore($receiverID,5);
                UserModel::addScore($packerID,5);
                return true;
            }
        }
        else if ($uid == $packerID){
            if ($status == 4001){
                $order->save(['status' => 6000]);
                //接单方在发单方确认后确认
                UserModel::addScore($packerID,1);
                return true;
            }
            else if($status == 4000){
                throw new confirmException([
                    'errorCode' => '20012',
                    'msg' => '请不要重复确认一个订单'
                ]);
            }
            else{
                //仅接单方确认不加分
                $order->save(['status' => 4000]);
                return true;
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
            throw new CancelException([
                'errorCode' =>'10012',
                'msg' => '订单已被接取，发单人无法取消'
            ]);
        }
        else if ($uid == $userID && $status == 2000){
            return true;
        }
        else if ($uid == $packerID && $status == 3000){
            return true;
        }
        else if ($status == 1000){
            throw new CancelException();
        }
        else{
            throw new CancelException([
                'errorCode' =>'10011',
                'msg' => '非法取消订单'
            ]);
        }
    }

    //检测订单是否重复
    public static function repeatCheck($uid,$orderArray){
        /*
         * 缓存时间设置为48小时
         * 缓存key用为‘orderMd5’+uid
         * 1.首先检测缓存是否存在
         * 2.存在则取出，然后进行比较
         * 3.相同则返回订单重复的提示信息，不重复则存入缓存，继续执行
         * 4.不相同直接存入缓存，继续执行
         * 5.不存在缓存，则直接存入缓存。
         * */
        $key = 'orderMd5' . $uid;
        $dataStr = $orderArray['cost'] . $orderArray['start_point'] . $orderArray['item_type'] . $orderArray['detail'];
        $value = md5($dataStr);

        $exist = Cache::get($key);
        if ($exist){
            $orderMd5Array = $exist;
            $isRepeat = in_array($value,$orderMd5Array);
            if ($isRepeat){
                return false;
            }
            else{
                $orderMd5Array = $exist;
                array_push($orderMd5Array,$value);
                cache($key,$orderMd5Array,127800);
                return true;
            }
        }
        else{
            $orderMd5Array =[];
            array_push($orderMd5Array,$value);
            cache($key,$orderMd5Array,127800);
            return true;
        }

    }
    //订单详情需要带有谁的地址，
    public static function detailFrom($uid,$order){
        /*
         * 1.如果uid=user_id 则显示接单人的信息
         * 2.如果uid=packer_id 则显示发单人的信息
         * 3.超过24小时信息不显示
         * */

        $receiverID = $order->user_id;
        $packerID = $order->packer_id;

        $hour = subTime($order,'H');
        if ($hour >=24){
            $order->hidden(['end_point.nickname','end_point.mobile','packer_address']);
            return $order;
        }
        if ($uid == $receiverID){
            $order->hidden(['end_point.nickname','end_point.mobile']);
            return $order;
        }
        if ($uid == $packerID){
            $order->hidden(['packer_address']);
            return $order;
        }



    }

}