<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/27/14
 * Time: 7:19 AM
 */

namespace Admin\Review\Controller;


use Admin\Review\Filter\ReviewFilter;
use Application\Controller\ActionController;
use Catalog\Entity\ProductReview;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\Validator\EmailAddress;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class ReviewController extends ActionController{
    public function indexAction(){
        $em = $this->getEntityManager();
        $repository = $em->getRepository('Catalog\Entity\ProductReview');
        $queryBuilder = $repository->createQueryBuilder('review')

            ->orderBy('review.productReviewId', 'DESC');
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
        $entity = new ProductReview();
        $builder = new AnnotationBuilder( $this->getEntityManager());
        /** @var Form $form */
        $form = $builder->createForm( $entity );
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(),'Catalog\Entity\ProductReview'));

        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            $form->setInputFilter((new ReviewFilter())->getInputFilter());
            if($form->isValid()){
                $obj = $form->getHydrator()->hydrate($form->getData(), $entity);

                $this->getEntityManager()->persist($obj);

                $this->flashMessenger()->addSuccessMessage("Product Review added successfully.");
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin-review');

            }
        }

        $view =  new ViewModel();
        $view->setTemplate('admin/review/add');
        $view->setVariable('form',$form);
        return $view;
    }

    public function editAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-review', array(
                'action' => 'add'
            ));
        }
        $em = $this->getEntityManager();
        $entity = $em->getRepository('Catalog\Entity\ProductReview')->find($id);
        if(!$entity){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $builder = new AnnotationBuilder( $this->getEntityManager());
        /** @var Form $form */
        $form = $builder->createForm( $entity );
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(),'Catalog\Entity\Admin'));

        if($this->getRequest()->isPost()){
            $form->setInputFilter((new ReviewFilter())->getInputFilter());
            $form->setData($this->getRequest()->getPost()->toArray());
            if($form->isValid()){
                $obj = $form->getHydrator()->hydrate($form->getData(), $entity);
                $this->getEntityManager()->persist($obj);

                $this->flashMessenger()->addSuccessMessage("Product Review updated successfully.");
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin-review');
            }
        }
        else{
            $form->bind($entity);
        }

        $view =  new ViewModel();
        $view->setTemplate('admin/review/add');
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

                    $entity = $this->getEntityManager()->getRepository('Catalog\Entity\ProductReview')->find($entity_id);
                    $this->getEntityManager()->remove($entity);
                }
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage("Product Review/Reviews deleted successfully.");
            }
        }
        return $this->redirect()->toRoute('admin-review');
    }


}