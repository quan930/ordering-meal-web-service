<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-03-30
 * Time: 15:11
 */

namespace app\common\logic;


use think\Model;

class Hotel extends Model
{
    private $showUrl = '/index.php/show/';
    /**
     * 注册
     * @param \app\common\pojo\Hotel $hotel
     * @return int 返回-1内容为null，返回0添加失败，返回1添加成功
     */
    public function registered(\app\common\pojo\Hotel $hotel){
        //检验店家名称，电话，头像，密码，位置，菜系是否为null
        if ($hotel->name==null||$hotel->tel==null||$hotel->portrait==null||$hotel->password==null||$hotel->location==null||$hotel->cuisine==null)
            return -1;
        $hotel->online=false;//上线
        $hotel->examine=false;//审核
        return \model('HotelUser','model')->insertHotel($hotel);
    }

    /**
     * 登陆
     * @param \app\common\pojo\Hotel $hotel 店家
     * @return int tel 密码为null 返回-1 密码错误返回0 正确返回1
     */
    public function login(\app\common\pojo\Hotel $hotel){
        if ($hotel->tel==null||$hotel->password==null)
            return -1;
        $newHotel = \model('HotelUser','model')->selectHotelByTel($hotel->tel);
        if ($newHotel->password==$hotel->password)
            return 1;
        return 0;
    }

    /**
     * 全部店家
     */
    public function findAllHotel(){
        $hotels = \model('HotelUser','model')->selectAllHotels();
        foreach ($hotels as $hotel){
            $hotel->portrait=$this->showUrl.$hotel->tel;
        }
        return $hotels;
    }

    /**
     * 根据店家tel查找店家
     * @param $tel int 电话 同id
     * @return \app\common\pojo\Hotel 店家
     */
    public function findHotelByTel($tel){
        $hotel = \model('HotelUser','model')->selectHotelByTel($tel);
        $hotel->portrait=$this->showUrl.$hotel->tel;
        return $hotel;
    }

    /**
     * 根据店家tel返回店家图片
     * @param $tel int 店家tel
     * @return mixed 图片二进制
     */
    public function findImageByTel($tel){
         return \model('HotelUser','model')->selectHotelByTel($tel)->portrait;
    }

    /**
     * 返回未审核店家
     * @return array 店家数组
     */
    public function findNotAuditHotels(){
        $newHotels = [];
        $hotels = \model('HotelUser','model')->selectAllHotels();
        foreach ($hotels as $hotel){
            if ($hotel->examine==false){
                $hotel->portrait=$this->showUrl.$hotel->tel;
                array_push($newHotels,$hotel);
            }
        }
        return $newHotels;
    }

    /**
     * 审核店家
     * @param $tel string 电话tel
     */
    public function auditHotel($tel){
        return \model('HotelUser','model')->updateHotel(new \app\common\pojo\Hotel(null,$tel,null,null,null,true,null,null));
    }
}