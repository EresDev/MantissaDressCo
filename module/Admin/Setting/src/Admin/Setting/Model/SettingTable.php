<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/19/14
 * Time: 10:53 PM
 */

namespace Admin\Setting\Model;


use Admin\Setting\Model\Entity\Setting;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

class SettingTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function saveSetting(Setting $entity)
    {
        $data = array(
            'setting_key' => $entity->setting_key,
            'setting_value' => $entity->setting_value,
        );


        if ($this->getSetting($entity->setting_key)) {
            $this->tableGateway->update($data, array('setting_key' => $entity->setting_key));
        } else {
            throw new \Exception('Cannot find setting with setting key: ' . $entity->setting_key);
        }

    }
    public function getSetting($setting_key)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($setting_key) {
            $where = new Where();
            $where->equalTo('setting_key', $setting_key);
            $select->where($where);
        });
        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("Cannot find a setting with setting_key: " . $setting_key);
        }
        return $row;
    }
} 