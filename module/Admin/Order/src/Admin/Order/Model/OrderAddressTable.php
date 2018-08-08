<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 5:34 AM
 */

namespace Admin\Order\Model;

use Admin\Order\Model\Entity\OrderAddress;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;


class OrderAddressTable {
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
    public function getOrderAddress($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('order_address_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Cannot find a order address with ID: " . $id);
        }
        return $row;
    }
    public function getOrderAddressOfOrder($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('order_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Cannot find a order address with ID: " . $id);
        }
        return $row;
    }
    public function saveOrderAddress(OrderAddress $entity)
    {
        $data = array(
            'address1' => $entity->address1,
            'address2' => $entity->address2,
            'city' => $entity->city,
            'postcode' => $entity->postcode,
            'state' => $entity->state,
            'country' => $entity->country,
            'order_id' => $entity->order_id,
        );

        $id = (int)$entity->order_address_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getOrderAddress($id)) {
                $this->tableGateway->update($data, array('order_address_id' => $id));
            } else {
                throw new \Exception('Cannot find order address with ID: '.$id);
            }
        }
        return $id;

    }
    public function deleteOrderAddress($id)
    {
        $this->tableGateway->delete(array('order_address_id' => $id));
    }
    public function deleteOrderAddressByOrderId($id)
    {
        $this->tableGateway->delete(array('order_id' => $id));
    }

} 