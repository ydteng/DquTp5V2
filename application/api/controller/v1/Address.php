<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/19
 * Time: 13:56
 */

namespace app\api\controller\v1;
use app\api\model\Provinces as ProvincesModel;
use app\api\model\School as SchoolModel;
use app\api\validate\AddressNew;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\MissException;
use app\api\service\Token as TokenService;
use app\api\model\UserAddress as UserAddressModel;
use app\api\model\User as UserModel;
use app\lib\exception\UserException;
use app\lib\SuccessMessage;

class Address
{
    /**
     * @获取地址，先直接返回所有城市，再根据城市id返回学校
     * 需要城市id和学校名
     */
    public function getProvince()
    {
        $provinces = ProvincesModel::getProvinces();
        if(!$provinces){
            throw new MissException();
        }
        return $provinces;
    }


    public function getSchoolByProID()
    {
        (new IDMustBePositiveInt())->goCheck();
        //为了让require验证规则起作用，所以没有在函数里面传至，要不tp5会I先检测有没有传值，报id参数错误的错
        $id = request()->param('id');

        $school = SchoolModel::getSchoolByProID($id);
        if(!$school){
            throw new MissException();
        }
        return $school;
    }

    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        //根据Token获取uid
        //根据uid查找用户数据，用户不存在则抛出异常
        //获取用户传过来的信息
        //判断用户信息是否存在，存在则更新，不存在则添加

        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user){
            throw new UserException();
        }
        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if (!$userAddress){
            $user->address()->save($dataArray);
        }
        else{
            $user->address->save($dataArray);
        }
        return json(new SuccessMessage(),201);
    }

    public function getUserAddress(){
        //没有验证token是否传入，因为在下面的获取uid已经在header中验证了
        $uid = TokenService::getCurrentUid();
        $address = UserAddressModel::getUserAddress($uid);
        if (!$address){
            throw new MissException();
        }
        return $address;
    }
}