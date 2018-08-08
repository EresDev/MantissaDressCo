<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/8/14
 * Time: 7:52 AM
 */

namespace Admin\Product\Model\Entity;


use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

class ProductOptionTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getProductOptions($product_id)
    {
        $product_id = (int)$product_id;
        $resultSet = $this->tableGateway->select(function (Select $select) use ($product_id) {
            $where = new Where();
            $where->equalTo("product_id", $product_id);
            $select->where($where);
        });
        return $resultSet;
    }

    public function saveProductOption(ProductOption $option)
    {
        $data = array(
            'product_id' => $option->product_id,
            'option_title' => $option->option_title,
            'available_qty' => $option->available_qty,
        );

         $this->tableGateway->insert($data);


    }

    public function deleteProductOptions($product_id)
    {
        //echo $product_id; exit;
        $where = new Where();
        $where->equalTo("product_id", $product_id);
        $this->tableGateway->delete($where);
    }

} 