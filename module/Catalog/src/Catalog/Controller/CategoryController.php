<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/21/14
 * Time: 7:29 PM
 */

namespace Catalog\Controller;


use Application\Controller\ActionController;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\View\Model\ViewModel;

class CategoryController extends ActionController{
    public function indexAction(){
        $em = $this->getEntityManager();
        $repository = $em->getRepository('Catalog\Entity\Category');
        $queryBuilder = $repository->createQueryBuilder('category')
            ->where('category.enabled = 1')
            ->orderBy('category.sortOrder', 'ASC');
        $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder));

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(9);

        $page = (int)$this->params()->fromQuery('page');
        if($page) $paginator->setCurrentPageNumber($page);
        $view = new ViewModel(
            array('paginator' => $paginator)
        );
        return $view;
    }
} 