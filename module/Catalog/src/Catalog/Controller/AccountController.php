<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/23/14
 * Time: 4:48 AM
 */

namespace Catalog\Controller;


use Application\Controller\UserActionController;
use Catalog\Entity\Order;
use Catalog\Entity\User;
use Catalog\Form\AccountUpdateForm;
use Catalog\Form\ChangePasswordForm;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\ViewModel;

class AccountController extends UserActionController{
    public function indexAction(){
        if (! $this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        $em = $this->getEntityManager();
        $history = $em->getRepository('Catalog\Entity\LoginHistory')->findBy(array('user' =>$this->getAuthService()->getIdentity()), array('historyId' =>'DESC'), 8);
        $details = $em->getRepository('Catalog\Entity\User')->find($this->getAuthService()->getIdentity());
        return array(
            'history' => $history,
            'details' => $details
        );
    }

    public function ordersAction(){
        if (! $this->getAuthService()->hasIdentity()){
            $url = rawurlencode($this->url()->fromRoute('account', array('action'=>'orders'), array('force_canonical' => true)) );
            $this->flashmessenger()->addErrorMessage("You need to be logged in to view this page.");
            return $this->redirect()->toRoute('login', array(), array('query' => array('redirect_to' => $url)));
        }
        $em = $this->getEntityManager();
        $repository = $em->getRepository('Catalog\Entity\Order');
        $queryBuilder = $repository->createQueryBuilder('orders')
            ->where('orders.user =' .$this->getAuthService()->getIdentity())
            ->orderBy('orders.orderId', 'DESC');
        $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder));

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');
        if($page) $paginator->setCurrentPageNumber($page);
        $products = array();
        foreach($paginator as $order){
            $products[] = $em->getRepository('Catalog\Entity\OrderProduct')->findBy(array('order' => $order->getOrderId()));
        }
        $address = null;
        if(count($paginator) >= 1){
            $address =$em->getRepository('Catalog\Entity\OrderAddress')->findOneBy(array('order' => $order->getOrderId()));
        }
        $view = new ViewModel(
            array(
                'paginator' => $paginator,
                'products' =>$products,
                'address' => $address,
            )
        );
        return $view;
    }

    public function historyAction(){
        if (! $this->getAuthService()->hasIdentity()){
            $url = rawurlencode($this->url()->fromRoute('account', array('action'=>'history'), array('force_canonical' => true)) );
            $this->flashmessenger()->addErrorMessage("You need to be logged in to view this page.");
            return $this->redirect()->toRoute('login', array(), array('query' => array('redirect_to' => $url)));
        }
        $em = $this->getEntityManager();
        $history = $em->getRepository('Catalog\Entity\LoginHistory')->findBy(array('user'=>$this->getAuthService()->getIdentity()), array('historyId' => 'DESC'), 100);

        return array(
            'history' => $history
        );
    }

    public function updateAction(){
        if (! $this->getAuthService()->hasIdentity()){
            $url = rawurlencode($this->url()->fromRoute('account', array('action'=>'update'), array('force_canonical' => true)) );
            $this->flashmessenger()->addErrorMessage("You need to be logged in to view this page.");
            return $this->redirect()->toRoute('login', array(), array('query' => array('redirect_to' => $url)));
        }
        $form = new AccountUpdateForm();
        $hydrator = new ClassMethods();
        $em = $this->getEntityManager();
        $user = $em->getRepository('Catalog\Entity\User')->find($this->getAuthService()->getIdentity());
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            $valid1 = $form->isValid();
            $valid2 = $form->isValid($form->setInputFilter($form->getCustomInputFilter()));
            if($valid2 && $valid1){

                $user = $hydrator->hydrate($post, $user);

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage("Your personal information has been updated successfully.");
                return array(
                    'form' => $form,
                );
            }
        }

        $user = $hydrator->extract($user);
        $form->setData($user);
        return array(
            'form' => $form,
        );
    }

    public function changePasswordAction(){
        if (! $this->getAuthService()->hasIdentity()){
            $url = rawurlencode($this->url()->fromRoute('account', array('action'=>'changePassword'), array('force_canonical' => true)) );
            $this->flashmessenger()->addErrorMessage("You need to be logged in to view this page.");
            return $this->redirect()->toRoute('login', array(), array('query' => array('redirect_to' => $url)));
        }
        $form = new ChangePasswordForm();
        if($this->getRequest()->isPost()){


            $post = $this->getRequest()->getPost()->toArray();

            $form->setData($post);
                $valid1 = $form->isValid();
                $valid2 = $form->isValid($form->setInputFilter($form->getCustomInputFilter()));
                if($valid2 && $valid1){
                    $em = $this->getEntityManager();
                    $user = $em->getRepository('Catalog\Entity\User')->find($this->getAuthService()->getIdentity());
                    if($user->getPassword() == md5($post['currentpassword'])){

                        $user->setPassword(md5($post['password']));

                        $em->persist($user);
                        $em->flush();
                        $this->flashMessenger()->addSuccessMessage("Your password has been updated successfully.");
                        $form = new ChangePasswordForm();
                        return array(
                            'form' => $form,
                        );
                    }
                    else{
                        $this->flashMessenger()->addErrorMessage("Your current password is incorrect. Please try again.");

                    }
                }

        }

        return array(
            'form' => $form,
        );
    }
} 