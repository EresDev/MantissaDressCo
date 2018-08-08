<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/10/14
 * Time: 4:02 AM
 */

namespace Admin\User\Model;

use Admin\User\Model\Entity\User;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UserTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if($paginated) {
            // create a new Select object for the table album
            $select = new Select('user');
            $select->order('user_id DESC');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new User());
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
        $resultSet = $this->tableGatway->select(function(Select $select){
            $select->order('user_id DESC');
        });
        return $resultSet;
    }
    public function getUser($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('user_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Cannot find a user with ID: " . $id);
        }
        return $row;
    }
    public function saveUser(User $user)
    {
        $data = array(
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'address1' => $user->address1,
            'address2' => $user->address2,
            'city' => $user->city,
            'postcode' => $user->postcode,
            'state' => $user->state,
            'country' => $user->country,
            'newsletter' => $user->newsletter,
            'banned' => $user->banned,
            'email_verified' => $user->email_verified,
        );
        if($user->password){
            $data['password'] = md5($user->password);
        }
        $id = (int)$user->user_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('user_id' => $id));
            } else {
                throw new \Exception('Cannot find user with ID: '.$id);
            }
        }
        return $id;

    }
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('user_id' => $id));
    }
    public function getUsersByEmailLike($like){
        $resultSet = $this->tableGateway->select(function(Select $select) use ($like){
            $where = new Where();
            $where->like('email', $like."%");
            $select->where($where);
            $select->order('email');
        });

        return $resultSet;
    }

} 