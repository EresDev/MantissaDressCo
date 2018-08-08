<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/11/14
 * Time: 4:57 AM
 */

namespace Admin\Order\Model;

use Admin\Order\Model\Entity\Order;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class OrderTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            //$sql = $this->tableGateway->getSql();
            // create a new Select object for the table album
            $select = new Select('order');
            $select->join('user', "user.user_id = order.user_id", array('email'), \Zend\Db\Sql\Select::JOIN_INNER);
            $select->join('order_product', "order_product.order_id = order.order_id", array('total_price' => new \Zend\Db\Sql\Predicate\Expression('SUM(order_product.quantity * order_product.unit_price)')), \Zend\Db\Sql\Select::JOIN_INNER);
            $select->group('order_product.order_id');
            $select->order('order_id DESC');
            //echo $sql->getSqlstringForSqlObject($select); exit;
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Order());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
            // our configured select object
                $select,
                // the adapter to run it against
                $this->tableGateway->getAdapter(),
                // the result set to hydrate
                $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        $resultSet = $this->tableGatway->select(function (Select $select) {
            $select->join('user', "user.user_id = order.user_id", array('firstname', 'lastname'), 'inner');
            $select->order('user_id DESC');
        });
        return $resultSet;
    }

    public function saveOrder(Order $entity)
    {
        $data = array(
            'order_datetime' => $entity->order_datetime,
            'user_id' => $entity->user_id,
            'ostatus' => $entity->ostatus,
        );
        if (!$entity->order_datetime) {
            $data['order_datetime'] = date("Y-m-d H:i:s");
        }
        $id = (int)$entity->order_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getOrder($id)) {
                $this->tableGateway->update($data, array('order_id' => $id));
            } else {
                throw new \Exception('Cannot find order with ID: ' . $id);
            }
        }
        return $id;

    }

    public function getOrder($id)
    {
        $id = (int)$id;
        $resultSet = $this->tableGateway->select(function (Select $select) use ($id) {
            $select->join('user', "user.user_id = order.user_id", array('email' => new Expression("CONCAT(user.email, ' (ID: ', user.user_id, ')')")), \Zend\Db\Sql\Select::JOIN_INNER);
            $where = new Where();
            $where->equalTo('order_id', $id);
            $select->where($where);
        });
        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("Cannot find a order with ID: " . $id);
        }
        return $row;
    }

    public function deleteOrder($id)
    {
        $this->tableGateway->delete(array('order_id' => $id));
    }

    public function getAllUserOrder($userid)
    {
        $userid = (int)$userid;
        $resultSet = $this->tableGateway->select(function (Select $select) use ($userid) {
            $where = new Where();
            $where->equalTo('user_id', $userid);
            $select->where($where);
        });

        return $resultSet;
    }

} 