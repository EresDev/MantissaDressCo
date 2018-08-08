<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/21/14
 * Time: 7:29 PM
 */

namespace Catalog\Controller;


use Application\Controller\ActionController;
use Catalog\Entity\ProductReview;
use Catalog\Filter\ReviewFilter;
use Zend\Captcha\Dumb;
use Zend\Form\Element\Captcha;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\View\Model\ViewModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;

class ProductController extends ActionController{
    public function indexAction(){
        $category_id = (int) $this->params()->fromRoute('category_id', 0);
        $em = $this->getEntityManager();
        //first verify the category id is correct
        $category = $em->getRepository('Catalog\Entity\Category')->find($category_id);
        if(!$category){ // category do not exist
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $repository = $em->getRepository('Catalog\Entity\Product');
        $queryBuilder = $repository->createQueryBuilder('product')
            ->where('product.enabled = 1')
            ->andWhere('product.category ='.$category_id)
            ->orderBy('product.sortOrder', 'ASC');
        $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(9);

        $page = (int)$this->params()->fromQuery('page');
        if($page) $paginator->setCurrentPageNumber($page);


        $view = new ViewModel(
            array(
                'paginator' => $paginator,
                'category' => $category,
            )
        );

        return $view;
    }
    public function productAction(){
        $entity_id = (int) $this->params()->fromRoute('product_id', 0);
        $em = $this->getEntityManager();
        //first verify the category id is correct
        $entity = $em->getRepository('Catalog\Entity\Product')->find($entity_id);
        if(!$entity){ // category do not exist
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $product_options = $em->getRepository('Catalog\Entity\ProductOption')->findBy(array('product' => $entity_id), array('availableQty' => 'DESC'));
        $product_images = $em->getRepository('Catalog\Entity\ProductImage')->findBy(array('product' => $entity_id));
        $view = new ViewModel(
            array(
                'entity' => $entity,
                'product_options' => $product_options,
                'product_images' => $product_images,
            )
        );

        //add review form and review
        /** @var ProductReview $entity */
        $entity = new ProductReview();
        $builder = new AnnotationBuilder( $this->getEntityManager());
        /** @var Form $form */
        $form = $builder->createForm( $entity );
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(),'Catalog\Entity\ProductReview'));
        $csrf = new Csrf("csrf");
        $captcha = new Captcha('captcha');
        $captcha->setCaptcha(new Dumb());
        $form->add($csrf);
        $form->add($captcha);

        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $post['product'] = (int) $this->params()->fromRoute('product_id', 0);
            $post['approved'] = 0;
            $ob_j = new ReviewFilter();
            $form->setInputFilter($ob_j->getInputFilter());
            $form->setData($post);
            if($form->isValid()){

                $obj = $form->getHydrator()->hydrate($form->getData(), $entity);
                $this->getEntityManager()->persist($obj);

                $this->flashMessenger()->addSuccessMessage("Your review is submitted. It will be published after approval. Thank you.");

                $this->getEntityManager()->flush();
                $form->removeAttribute('value');

            }
            else{
                $this->flashMessenger()->addErrorMessage("Please correct errors below in review.");
            }

        }

        $em = $this->getEntityManager();
        $repository = $em->getRepository('Catalog\Entity\ProductReview');
        $queryBuilder = $repository->createQueryBuilder('review')
            ->where('review.product = '.$entity_id)
            ->andWhere('review.approved= 1 ')
            ->orderBy('review.productReviewId', 'DESC');
        $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder));

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');
        if($page) $paginator->setCurrentPageNumber($page);
        $view->setVariable('paginator', $paginator);
        $view->setVariable('form', $form);
        $view->setVariable('product_id', $entity_id);
        //review stuff ends here
        $view->setTemplate('catalog/product/product');
        return $view;
    }
} 