<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/15/14
 * Time: 5:58 PM
 */

namespace Admin\User\Controller;


use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class UserAjaxController extends AbstractRestfulController{
    public $userTable;
    public function usersLikeAction(){
        $like = $this->params()->fromRoute('like', "");
        $results = $this->getUserTable()->getUsersByEmailLike($like);
        $entities = array();
        foreach($results as $row){

            $entities['email'][] = $row->email." (ID: {$row->user_id})";
        }
        $json = new JsonModel($entities);

        return $json;

    }
    public function getUserTable(){
        if(!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Admin\User\Model\UserTable');
        }
        return $this->userTable;
    }
} 