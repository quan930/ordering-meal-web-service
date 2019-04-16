<?php
/**
 * Created by PhpStorm.
 * User: daquan
 * Date: 2019-04-16
 * Time: 14:11
 */

namespace app\common\logic;


use app\common\pojo\Order;
use think\Model;

class LogicOrder extends Model
{
    /**
     * 添加订单
     * @param Order $order
     * @return int 失败返回0 否则返回1
     */
    public function addOrder(Order $order){
        $bool = \model("ModelOrder","model")->insertOrder($order);
        if ($bool==false)
            return 0;
        return 1;
    }

    /**
     * 全部订单
     * @param $hotel
     * @return mixed
     */
    public function findOrdersByHotel($hotel){
        $data = \model("ModelOrder",'model')->selectOrdersByHotel($hotel);
        return $data;
    }

    /**
     * 店家接单
     * @param $id int 订单id
     * @return mixed
     */
    public function shopkeeperOrders($id){
        $order = new Order($id,null,null,null,null,null,1);
        return \model("ModelOrder",'model')->updateOrder($order);
    }
}