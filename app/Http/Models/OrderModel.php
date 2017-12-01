<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/19
 * Time: 10:05
 */
namespace App\Http\Models;

class OrderModel extends BaseModel{
    protected $table = 'Order';

    public function __construct()
    {
    }

    public function update($order_id = 0, $data = array())
    {
        $data['update_at'] = time();
        return app('db')
            ->table($this->getTable())
            ->where('id', $order_id)
            ->update($data);
    }
}