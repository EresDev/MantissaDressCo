<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/27/14
 * Time: 8:47 AM
 */

namespace Admin\Information\Controller;
use Admin\Information\Filter\InformationFilter;
use Catalog\Entity\InformationPage;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\Validator\EmailAddress;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

use Application\Controller\ActionController;

class InformationController extends ActionController{
    public function indexAction(){
        $em = $this->getEntityManager();
        $repository = $em->getRepository('Catalog\Entity\InformationPage');
        $queryBuilder = $repository->createQueryBuilder('information')

            ->orderBy('information.informationPageId', 'DESC');
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
        /** @var ProductReview $entity */
        $entity = new InformationPage();
        $builder = new AnnotationBuilder( $this->getEntityManager());
        /** @var Form $form */
        $form = $builder->createForm( $entity );
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(),'Catalog\Entity\InformationPage'));

        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            $form->setInputFilter((new InformationFilter())->getInputFilter());
            if($form->isValid()){
                $obj = $form->getHydrator()->hydrate($form->getData(), $entity);

                $this->getEntityManager()->persist($obj);

                $this->flashMessenger()->addSuccessMessage("Information added successfully.");
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin-information');

            }
        }

        $view =  new ViewModel();
        $view->setTemplate('admin/information/add');
        $view->setVariable('form',$form);
        return $view;
    }
    public function editAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-information', array(
                'action' => 'add'
            ));
        }
        $em = $this->getEntityManager();
        $entity = $em->getRepository('Catalog\Entity\InformationPage')->find($id);
        if(!$entity){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $builder = new AnnotationBuilder( $this->getEntityManager());
        /** @var Form $form */
        $form = $builder->createForm( $entity );
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(),'Catalog\Entity\InformationPage'));

        if($this->getRequest()->isPost()){
            $form->setInputFilter((new InformationFilter())->getInputFilter());
            $form->setData($this->getRequest()->getPost()->toArray());
            if($form->isValid()){
                $obj = $form->getHydrator()->hydrate($form->getData(), $entity);
                $this->getEntityManager()->persist($obj);

                $this->flashMessenger()->addSuccessMessage("Information updated successfully.");
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin-information');
            }
        }
        else{
            $form->bind($entity);
        }

        $view =  new ViewModel();
        $view->setTemplate('admin/information/add');
        $view->setVariable('form',$form);
        return $view;
    }
    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $entities = $request->getPost()->toArray();
            if(isset($entities["entities"])){
                foreach($entities["entities"] as $entity_id){

                    $entity = $this->getEntityManager()->getRepository('Catalog\Entity\InformationPage')->find($entity_id);
                    $this->getEntityManager()->remove($entity);
                }
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage("Information deleted successfully.");
            }
        }
        return $this->redirect()->toRoute('admin-information');
    }
} 