<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/9/14
 * Time: 5:15 AM
 */

namespace Admin\Product\Model\Entity;

class ProductImage {
    public $product_image_id;
    public $product_id;
    public $image;

    public function exchangeArray($data){
        $this->product_image_id = isset($data['product_image_id'])?$data['product_image_id']:null;
        $this->product_id = isset($data['product_id'])?$data['product_id']:null;
        $this->image = isset($data['image'])?$data['image']:null;
    }
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 