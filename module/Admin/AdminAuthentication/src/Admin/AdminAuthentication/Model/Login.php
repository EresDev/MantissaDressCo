<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 8/15/14
 * Time: 9:34 AM
 */

namespace Admin\AdminAuthentication\Model;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\StringLength;
use Zend\Db\Sql\Sql;
use Zend\Session\Container;
class Login implements InputFilterAwareInterface
{
    public $username;
    public $password;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->username = isset($data['username']) ? $data['username'] : null;
        $this->password = isset($data['password']) ? $data['password'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not Used!");
    }

    public function getInputFilter()
    {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'username',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(

                    array(
                        'name' =>'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Username is required!'
                            ),
                        ),
                    ),

                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'password',

                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' =>'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Password is required!'
                            ),
                        ),
                    ),

                ),
            )));

            $this->inputFilter = $inputFilter;

        }
        return $this->inputFilter;
    }

    public function login(ServiceLocatorInterface $sm){
        if(!$this->username || ! $this->password)
            throw new \Exception("Username and/or password must be set before login.");
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        $query = new Sql($adapter);
        $select = $query->select()->from('admin')->where(array('username'=>$this->username, 'password'=>md5($this->password)));
        $statement = $query->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $is_logged_in = $result->count() == 1;
        if($is_logged_in){
            $container = new Container("admin");
            $row = $result->current();
            $container->admin_id = $row["admin_id"];
            $container->admin_username = $row["username"];
            $container->admin_username = $row["email"];
        }
        return $is_logged_in;
    }

} 