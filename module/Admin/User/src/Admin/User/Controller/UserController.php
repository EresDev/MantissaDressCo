<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/10/14
 * Time: 6:18 AM
 */

namespace Admin\User\Controller;


use Admin\User\Form\UserForm;
use Admin\User\Model\Entity\User;
use Application\Controller\ActionController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends ActionController{
    public $userTable;
    public $orderTable;
    public $orderAddressTable;
    public $orderProcutTable;
    public function indexAction()
    {
        $status = $this->params()->fromRoute('status', "");
        $success_message = "";
        $entity = "User";
        switch($status){
            case "success-edit":
                $success_message = "$entity updated successfully.";
                break;
            case "success-delete":
                $success_message = "{$entity}/{$entity}s deleted successfully.";
                break;
            case "success-add":
                $success_message = "$entity added successfully.";
                break;

        }
        $paginator = $this->getUserTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(10);

        $view = new ViewModel(array(
            'entities' => $paginator,
            'success_message' => $success_message,
        ));

        return $view;
    }

    public function addAction()
    {


        $form = new UserForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $entity = new User();
            $form->setInputFilter($entity->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $entity->exchangeArray($form->getData());
                $entity_id = $this->getUserTable()->saveUser($entity);
                return $this->redirect()->toRoute('admin-user', array('status' => 'success-add'));
            }

        }

        $view = new ViewModel(array(
            'entityform' => $form,
        ));
        $view->setTemplate("admin/user/add");
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-user', array(
                'action' => 'add'
            ));
        }
        $entity = $this->getUserTable()->getUser($id);
        $entity->password = "";
        $form = new UserForm();
        $form->bind($entity);


        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($entity->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $product_id = $this->getUserTable()->saveUser($form->getData());
                return $this->redirect()->toRoute('admin-user', array('status' => 'success-edit'));
            }
        }

        $view = new ViewModel(array(
            'entity_id' => $id,
            'entityform' => $form,

        ));
        $view->setTemplate("admin/user/add");
        return $view;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $entities = $request->getPost()->toArray();
            if(isset($entities["entities"])){
                foreach($entities["entities"] as $entity_id){
                    $orders = $this->getOrderTable()->getAllUserOrder($entity_id);
                    foreach($orders as $order){
                        $this->getOrderAddressTable()->deleteOrderAddressByOrderId($order->order_id);
                        $this->getOrderProductTable()->deleteOrderProductsByOrderId($order->order_id);
                        $this->getOrderTable()->deleteOrder($order->order_id);

                    }
                    $history = $this->getEntityManager()->getRepository('Catalog\Entity\LoginHistory')->findBy(array('user' => $entity_id));
                    foreach($history as $his){
                        $this->getEntityManager()->remove($his);
                    }

                    $this->getEntityManager()->flush();
                    $this->getUserTable()->deleteUser($entity_id);
                }
                return $this->redirect()->toRoute('admin-user', array('status' => 'success-delete'));
            }
        }
        return $this->redirect()->toRoute('admin-user');
    }
    public function getUserTable(){
        if(!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Admin\User\Model\UserTable');
        }
        return $this->userTable;
    }

    public function getOrderTable(){
        if(!$this->orderTable){
            $sm = $this->getServiceLocator();
            $this->orderTable = $sm->get('Admin\Order\Model\OrderTable');
        }
        return $this->orderTable;
    }
    public function getOrderAddressTable(){
        if(!$this->orderAddressTable){
            $sm = $this->getServiceLocator();
            $this->orderAddressTable = $sm->get('Admin\Order\Model\OrderAddressTable');
        }
        return $this->orderAddressTable;
    }
    public function getOrderProductTable(){
        if(!$this->orderProductTable){
            $sm = $this->getServiceLocator();
            $this->orderProductTable = $sm->get('Admin\Order\Model\OrderProductTable');
        }
        return $this->orderProductTable;
    }

} 