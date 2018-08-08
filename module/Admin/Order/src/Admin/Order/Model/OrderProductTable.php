<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 6:06 AM
 */

namespace Admin\Order\Model;


use Admin\Order\Model\Entity\OrderProduct;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;


class OrderProductTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($order_id)
    {
        $order_id = (int) $order_id;
        $resultSet = $this->tableGatway->select(function(Select $select) use ($order_id){
            $where = new Where('order_id', $order_id);
            $select->where($where);
        });
        return $resultSet;
    }
    public function getOrderProduct($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('order_product_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Cannot find a order product with ID: " . $id);
        }
        return $row;
    }
    public function getOrderProducts($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('order_id' => $id));

        return $rowset;
    }
    public function saveOrderProduct(OrderProduct $entity)
    {
        $data = array(
            'product_id' => $entity->product_id,
            'product_option_title' => $entity->product_option_title,
            'quantity' => $entity->quantity,
            'order_id' => $entity->order_id,
            'unit_price' => $entity->unit_price
        );

        $id = (int)$entity->order_product_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getOrderProduct($id)) {
                $this->tableGateway->update($data, array('order_product_id' => $id));
            } else {
                throw new \Exception('Cannot find order product with ID: '.$id);
            }
        }
        return $id;

    }
    public function deleteOrderProducts($id)
    {
        $this->tableGateway->delete(array('order_id' => $id));
    }
    public function deleteOrderProductsByOrderId($id)
    {
        $this->tableGateway->delete(array('order_id' => $id));
    }

    public function setOrderProductNull($product_id, $title)
    {
        $data = array(
            'product_id' => null,
            'product_option_title' => $title,
        );

        $id = (int)$product_id;

        $this->tableGateway->update($data, array('product_id' => $id));

    }
}