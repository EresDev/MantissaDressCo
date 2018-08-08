<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/27/14
 * Time: 4:10 AM
 */

namespace Admin\Staff\Controller;


use Application\Controller\ActionController;
use Catalog\Entity\Admin;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\Validator\EmailAddress;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class StaffController extends ActionController{
    public function indexAction(){
        $em = $this->getEntityManager();
        $repository = $em->getRepository('Catalog\Entity\Admin');
        $queryBuilder = $repository->createQueryBuilder('admin')

            ->orderBy('admin.adminId', 'DESC');
        $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder));

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');
        if($page) $paginator->setCurrentPageNumber($page);
        $view =  new ViewModel();
        $view->setVariable('paginator',$paginator);
        return $view;
    }

    public function addAction(){
        /** @var Admin $admin */
        $admin = new Admin();
        $builder = new AnnotationBuilder( $this->getEntityManager());
        /** @var Form $form */
        $form = $builder->createForm( $admin );
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(),'Catalog\Entity\Admin'));
        $form->bind($admin);
        //$form->getInputFilter()->get('password')->setRequired(false);

        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);

            $validator = new EmailAddress();
            if(!$validator->isValid($post['email'])){
                $this->flashMessenger()->addErrorMessage("Invalid email address.");

            }
            else if($form->isValid()){
                $existing = $this->getEntityManager()->getRepository('Catalog\Entity\Admin')->findOneBy(array('email' => $post['email']));
                $existing2 = $this->getEntityManager()->getRepository('Catalog\Entity\Admin')->findOneBy(array('username' => $post['username']));

                if($existing){
                    $this->flashMessenger()->addErrorMessage("Email address is already in use.");
                }
                else if($existing2){
                    $this->flashMessenger()->addErrorMessage("Username is already in use.");

                }
                else {
                    $post['password'] = md5($post['password']);
                    $admin = $form->getHydrator()->hydrate($post,$admin);
                    $this->getEntityManager()->persist(
                         $admin
                    );
                    $this->getEntityManager()->flush();
                    $this->flashMessenger()->addSuccessMessage("Staff member added successfully.");
                    return $this->redirect()->toRoute('admin-staff');
                }
            }

        }



        $view =  new ViewModel();
        $view->setTemplate('admin/staff/add');
        $view->setVariable('form',$form);
        return $view;
    }
    public function editAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-staff', array(
                'action' => 'add'
            ));
        }
        $em = $this->getEntityManager();
        $entity = $em->getRepository('Catalog\Entity\Admin')->find($id);
        if(!$entity){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $builder = new AnnotationBuilder( $this->getEntityManager());
        /** @var Form $form */
        $form = $builder->createForm( $entity );
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(),'Catalog\Entity\Admin'));


        $form->getInputFilter()->get('password')->setRequired(false);
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);

            $validator = new EmailAddress();
            if(!$validator->isValid($post['email'])){
                $this->flashMessenger()->addErrorMessage("Invalid email address.");

            }
            else if($form->isValid()){
                $existing = $this->getEntityManager()->getRepository('Catalog\Entity\Admin')->findOneBy(array('email' => $post['email']));
                $existing2 = $this->getEntityManager()->getRepository('Catalog\Entity\Admin')->findOneBy(array('username' => $post['username']));

                if($existing && $existing->getAdminId() != $id){
                    $this->flashMessenger()->addErrorMessage("Email address is already in use.");
                }
                else if($existing2 && $existing2->getAdminId() != $id){
                    $this->flashMessenger()->addErrorMessage("Username is already in use.");

                }
                else {
                    //echo $post['password']; exit;
                    if( $post['password'] != ""){
                        $existing->setPassword(md5($post['password']));
                    }
                    $existing->setUsername($post['username']);
                    $existing->setEmail($post['email']);
                    $this->getEntityManager()->persist(
                        $existing
                    );
                    $this->getEntityManager()->flush();
                    $this->flashMessenger()->addSuccessMessage("Staff member updated successfully.");
                    return $this->redirect()->toRoute('admin-staff');
                }
            }

        }
        $form->bind($entity);

        $form->get('password')->setValue("");
        $view =  new ViewModel();
        $view->setTemplate('admin/staff/add');
        $view->setVariable('form',$form);
        $view->setVariable('edit','edit');
        return $view;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $entities = $request->getPost()->toArray();
            if(isset($entities["entities"])){
                foreach($entities["entities"] as $entity_id){

                    $entity = $this->getEntityManager()->getRepository('Catalog\Entity\Admin')->find($entity_id);
                    $this->getEntityManager()->remove($entity);
                }
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage("Staff member/members deleted successfully.");
            }
        }
        return $this->redirect()->toRoute('admin-staff');
    }
} 