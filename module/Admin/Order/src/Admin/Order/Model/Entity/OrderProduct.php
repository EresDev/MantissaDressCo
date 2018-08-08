<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 4:44 AM
 */

namespace Admin\Order\Model\Entity;


class OrderProduct {
    public $order_product_id;
    public $order_id;
    public $product_id;
    public $product_option_title;
    public $quantity;
    public $unit_price;

    public function exchangeArray($data){
        $this->order_product_id = isset($data['order_product_id']) ? $data['order_product_id'] : null;
        $this->order_id = isset($data['order_id']) ? $data['order_id'] : null;
        $this->product_id = isset($data['product_id']) ? $data['product_id'] : null;
        $this->product_option_title = isset($data['product_option_title']) ? $data['product_option_title'] : null;
        $this->quantity = isset($data['quantity']) ? $data['quantity'] : null;
        $this->unit_price = isset($data['unit_price']) ? $data['unit_price'] : null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 